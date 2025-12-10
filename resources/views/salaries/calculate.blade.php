@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-info">
        <strong>Preview Perhitungan</strong> - Data ini belum disimpan ke database. Silakan periksa rincian di bawah ini.
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Slip Gaji: {{ $employee->nama }} ({{ $result['periode'] }})</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td>NIP</td><td>: {{ $employee->nip }}</td></tr>
                        <tr><td>Hari Kerja Efektif</td><td>: {{ $result['working_days_month'] }} Hari</td></tr>
                        <tr><td>Jumlah Alpha</td><td>: {{ $result['absent_count'] }} Hari</td></tr>
                    </table>
                </div>
                <div class="col-md-6 text-end">
                    <h3>Total Terima: Rp {{ number_format($result['gaji_bersih'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <hr>

            {{-- Tabel Rincian dari BE 'details_list' --}}
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['details_list'] as $detail)
                        <tr class="{{ $detail['jenis'] == 'potongan' ? 'text-danger' : 'text-success' }}">
                            <td>{{ ucfirst($detail['jenis']) }}</td>
                            <td>{{ $detail['keterangan'] }}</td>
                            <td class="text-end">
                                {{ $detail['jenis'] == 'potongan' ? '-' : '+' }} 
                                Rp {{ number_format($detail['nominal'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="fw-bold">
                    <tr>
                        <td colspan="2" class="text-end">Total Gaji Bersih</td>
                        <td class="text-end">Rp {{ number_format($result['gaji_bersih'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Batal</a>
                
                {{-- Form Konfirmasi Simpan --}}
                <form action="{{ route('salaries.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="periode" value="{{ $result['periode'] }}">
                    
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-save"></i> Simpan Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection