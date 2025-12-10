@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Riwayat Penggajian</h2>
    
    <div class="d-flex gap-2">
        {{-- TOMBOL EXPORT EXCEL --}}
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </button>

        {{-- TOMBOL HITUNG BARU --}}
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calculateModal">
            + Hitung Gaji Baru
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Gaji Bersih</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $salary)
                <tr>
                    <td>{{ $salary->periode }}</td>
                    <td>{{ $salary->employee->nip }}</td>
                    <td>{{ $salary->employee->nama }}</td>
                    <td>Rp {{ number_format($salary->gaji_bersih, 0, ',', '.') }}</td>
                    <td>
                        @if($salary->status_pembayaran == 'paid')
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        {{-- Tombol Bayar --}}
                        @if($salary->status_pembayaran == 'pending')
                        <form action="{{ route('salaries.updateStatus', $salary->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status_pembayaran" value="paid">
                            <button class="btn btn-sm btn-outline-success" onclick="return confirm('Tandai sudah dibayar?')">Bayar</button>
                        </form>
                        @endif

                        {{-- Tombol Cetak PDF --}}
                        <a href="{{ route('salaries.pdf', $salary->id) }}" class="btn btn-sm btn-danger" target="_blank">
                            PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data gaji.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $salaries->links() }}
    </div>
</div>

{{-- MODAL 1: CALCULATE GAJI --}}
<div class="modal fade" id="calculateModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('salaries.calculate') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Hitung Gaji Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Karyawan</label>
                        <select name="employee_id" class="form-select" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->nama }} ({{ $emp->nip }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Periode Bulan</label>
                        <input type="month" name="periode" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Preview Perhitungan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: EXPORT EXCEL (BARU) --}}
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('salaries.excel') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-excel"></i> Export Laporan Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Laporan ini berisi rekapitulasi gaji seluruh karyawan pada periode yang dipilih.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Periode Gaji</label>
                        <input type="month" name="periode" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Download .xlsx</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection