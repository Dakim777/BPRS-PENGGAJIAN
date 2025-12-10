<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px; }
        
        .rincian-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .rincian-table th, .rincian-table td { border: 1px solid #999; padding: 8px; text-align: left; }
        .rincian-table th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row { font-weight: bold; background-color: #e6e6e6; }
        .signature { margin-top: 50px; text-align: right; margin-right: 50px; }
        .signature p { margin-bottom: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>BPRS LAMPUNG</h1>
        <p>Jl. Contoh Alamat No. 123, Bandar Lampung</p>
    </div>

    <h2 style="text-align: center; margin-bottom: 20px;">SLIP GAJI KARYAWAN</h2>

    <table class="info-table">
        <tr>
            <td width="15%">NIP</td>
            <td width="35%">: {{ $salary->employee->nip }}</td>
            <td width="15%">Periode</td>
            <td width="35%">: {{ $salary->periode }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: {{ $salary->employee->nama }}</td>
            <td>Jabatan</td>
            <td>: {{ $salary->employee->jabatan }}</td>
        </tr>
    </table>

    <table class="rincian-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th class="text-end">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($salary->details as $detail)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $detail->keterangan }}</td>
                <td>{{ ucfirst($detail->jenis) }}</td>
                <td class="text-end">
                    {{ $detail->jenis == 'potongan' ? '-' : '' }} 
                    {{ number_format($detail->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-end">TOTAL GAJI BERSIH</td>
                <td class="text-end">Rp {{ number_format($salary->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p>Bandar Lampung, {{ date('d-m-Y') }}<br>Manager Keuangan</p>
        <br>
        <p><strong>( _______________________ )</strong></p>
    </div>

</body>
</html>