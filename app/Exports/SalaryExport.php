<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalaryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        // Ambil data gaji berdasarkan periode
        return Salary::with('employee')
            ->where('periode', $this->periode)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Periode',
            'NIP',
            'Nama Karyawan',
            'Jabatan',
            'Gaji Pokok',
            'Total Tunjangan',
            'Total Potongan',
            'Gaji Bersih (Take Home Pay)',
            'Status Pembayaran',
            'Tanggal Transfer'
        ];
    }

    public function map($salary): array
    {
        return [
            $salary->periode,
            $salary->employee->nip,
            $salary->employee->nama,
            $salary->employee->jabatan,
            $salary->gaji_pokok,
            $salary->total_tunjangan,
            $salary->total_potongan,
            $salary->gaji_bersih,
            $salary->status_pembayaran,
            $salary->tanggal_pembayaran ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Tebalkan baris pertama (Header)
            1 => ['font' => ['bold' => true]],
        ];
    }
}