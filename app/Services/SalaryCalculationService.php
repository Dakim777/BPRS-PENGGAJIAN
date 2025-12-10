<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class SalaryCalculationService
{
    /**
     * Calculate salary for an employee for a given period (YYYY-MM)
     */
    public function calculateForEmployee(Employee $employee, string $periode): array
    {
        // 1. Setup Periode & Carbon
        [$year, $month] = explode('-', $periode);
        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // 2. Hitung Hari Kerja Efektif (Senin-Jumat) dalam bulan ini
        // Jika ingin Sabtu masuk, ubah filter ini. Default: 5 hari kerja.
        $workingDays = $startOfMonth->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $endOfMonth) + 1; // +1 karena diffInDays eksklusif
        
        // Fallback jika 0 (sangat jarang) agar tidak division by zero
        $workingDays = $workingDays > 0 ? $workingDays : 20;

        // 3. Ambil Data Absensi
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        // 4. Base Amounts
        $gajiPokok = (float) $employee->gaji_pokok;
        $tunjanganTetap = (float) $employee->tunjangan;
        $potonganTetap = (float) $employee->potongan;

        // Rate Harian & Lembur
        $perDaySalary = $gajiPokok / $workingDays; 
        $overtimeRate = 1.5; 
        // Rumus Depnaker/Umum: Upah sejam = 1/173 * Gaji Sebulan (atau Gaji / Total Jam Kerja Sebulan)
        // Kita pakai pendekatan user sebelumnya: Gaji / (HariKerja * 8 jam)
        $overtimePerHourNominal = ($gajiPokok / ($workingDays * 8)) * $overtimeRate;

        // 5. Variables Calculator
        $totalLembur = 0;
        $totalPotonganAbsen = 0;
        $absentCount = 0;
        
        // Array untuk menyimpan rincian detail (masuk ke tabel salary_details)
        $details = [];

        // Tambahkan Gaji Pokok ke Details
        $details[] = ['jenis' => 'tunjangan', 'keterangan' => 'Gaji Pokok', 'nominal' => $gajiPokok];
        
        if ($tunjanganTetap > 0) {
            $details[] = ['jenis' => 'tunjangan', 'keterangan' => 'Tunjangan Tetap', 'nominal' => $tunjanganTetap];
        }

        foreach ($attendances as $att) {
            // Logika Lembur
            if ($att->status === 'lembur') {
                $lemburNominal = 0;
                
                if ($att->jam_masuk && $att->jam_keluar) {
                    $masuk = Carbon::parse($att->jam_masuk);
                    $keluar = Carbon::parse($att->jam_keluar);
                    
                    // Jika keluar lebih kecil dari masuk (misal masuk 20:00, keluar 04:00), tambah 1 hari
                    if ($keluar->lt($masuk)) {
                        $keluar->addDay();
                    }

                    $durasiJam = $keluar->diffInHours($masuk);
                    $lemburJam = max(0, $durasiJam - 8); // Lembur dihitung setelah 8 jam kerja

                    if ($lemburJam > 0) {
                        $lemburNominal = $lemburJam * $overtimePerHourNominal;
                    }
                } else {
                    // Flat bonus jika jam tidak lengkap
                    $lemburNominal = $perDaySalary * 0.25;
                }

                if ($lemburNominal > 0) {
                    $totalLembur += $lemburNominal;
                }
            }

            // Logika Absen (Alpha)
            if ($att->status === 'absen') {
                $absentCount++;
                $potonganHari = $perDaySalary;
                $totalPotonganAbsen += $potonganHari;
                
                $details[] = [
                    'jenis' => 'potongan', 
                    'keterangan' => "Potongan Absen tgl {$att->tanggal}", 
                    'nominal' => $potonganHari
                ];
            }
        }

        // Masukkan Total Lembur ke Details
        if ($totalLembur > 0) {
            $details[] = ['jenis' => 'tunjangan', 'keterangan' => 'Upah Lembur', 'nominal' => $totalLembur];
        }

        // Masukkan Potongan Tetap ke Details
        if ($potonganTetap > 0) {
            $details[] = ['jenis' => 'potongan', 'keterangan' => 'Potongan Tetap (BPJS/Lainnya)', 'nominal' => $potonganTetap];
        }

        // 6. Final Calculation
        $totalTunjangan = $tunjanganTetap + $totalLembur; // Gaji pokok dipisah di field DB, tapi bisa dianggap tunjangan dasar
        $totalPotongan = $potonganTetap + $totalPotonganAbsen;
        $gajiBersih = ($gajiPokok + $totalTunjangan) - $totalPotongan;

        return [
            'gaji_pokok' => $gajiPokok,
            'total_tunjangan' => $totalTunjangan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'overtime' => $totalLembur,
            'absent_count' => $absentCount,
            'working_days_month' => $workingDays,
            'periode' => $periode,
            'details_list' => $details // Penting untuk SalaryController
        ];
    }
}