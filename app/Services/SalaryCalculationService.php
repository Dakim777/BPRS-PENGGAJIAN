<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holiday; // <--- JANGAN LUPA IMPORT INI
use Carbon\Carbon;

class SalaryCalculationService
{
    public function calculateForEmployee(Employee $employee, string $periode): array
    {
        // 1. Setup Periode & Carbon
        [$year, $month] = explode('-', $periode);
        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // 2. AMBIL DATA HARI LIBUR NASIONAL
        $holidays = Holiday::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->pluck('tanggal')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        // 3. Hitung Hari Kerja Efektif (Senin-Jumat) - (Hari Libur Nasional)
        $workingDays = $startOfMonth->diffInDaysFiltered(function (Carbon $date) use ($holidays) {
            // Hari kerja = BUKAN weekend DAN BUKAN tanggal merah
            return !$date->isWeekend() && !in_array($date->format('Y-m-d'), $holidays);
        }, $endOfMonth) + 1; // +1 karena diffInDays eksklusif
        
        // Fallback agar tidak division by zero
        $workingDays = $workingDays > 0 ? $workingDays : 20;

        // --- SISA KODE KE BAWAH TETAP SAMA SEPERTI SEBELUMNYA ---
        
        // 4. Ambil Data Absensi
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        // 5. Base Amounts
        $gajiPokok = (float) $employee->gaji_pokok;
        $tunjanganTetap = (float) $employee->tunjangan;
        $potonganTetap = (float) $employee->potongan;

        $perDaySalary = $gajiPokok / $workingDays; 
        $overtimeRate = 1.5; 
        $overtimePerHourNominal = ($gajiPokok / ($workingDays * 8)) * $overtimeRate;

        // Variables Calculator
        $totalLembur = 0;
        $totalPotonganAbsen = 0;
        $absentCount = 0;
        $details = [];

        $details[] = ['jenis' => 'tunjangan', 'keterangan' => 'Gaji Pokok', 'nominal' => $gajiPokok];
        
        if ($tunjanganTetap > 0) {
            $details[] = ['jenis' => 'tunjangan', 'keterangan' => 'Tunjangan Tetap', 'nominal' => $tunjanganTetap];
        }

        foreach ($attendances as $att) {
            if ($att->status === 'lembur') {
                $lemburNominal = 0;
                if ($att->jam_masuk && $att->jam_keluar) {
                    $masuk = Carbon::parse($att->jam_masuk);
                    $keluar = Carbon::parse($att->jam_keluar);
                    if ($keluar->lt($masuk)) $keluar->addDay();
                    $durasiJam = $keluar->diffInHours($masuk);
                    $lemburJam = max(0, $durasiJam - 8); 
                    if ($lemburJam > 0) $lemburNominal = $lemburJam * $overtimePerHourNominal;
                } else {
                    $lemburNominal = $perDaySalary * 0.25;
                }
                if ($lemburNominal > 0) $totalLembur += $lemburNominal;
            }

            if ($att->status === 'absen') {
                $absentCount++;
                $potonganHari = $perDaySalary;
                $totalPotonganAbsen += $potonganHari;
                $details[] = ['jenis' => 'potongan', 'keterangan' => "Potongan Absen tgl {$att->tanggal}", 'nominal' => $potonganHari];
            }
        }

        if ($totalLembur > 0) {
            $details[] = ['jenis' => 'tunjangan', 'keterangan' => 'Upah Lembur', 'nominal' => $totalLembur];
        }

        if ($potonganTetap > 0) {
            $details[] = ['jenis' => 'potongan', 'keterangan' => 'Potongan Tetap (BPJS/Lainnya)', 'nominal' => $potonganTetap];
        }

        $totalTunjangan = $tunjanganTetap + $totalLembur;
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
            'details_list' => $details 
        ];
    }
}