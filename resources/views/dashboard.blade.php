@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-dark">Dashboard Ringkasan</h2>
        <p class="text-secondary">
            Selamat datang, <strong>{{ Auth::user()->name }}</strong>! 
            Berikut adalah laporan ringkas periode <strong>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</strong>.
        </p>
    </div>
</div>

{{-- SECTION 1: KARTU STATISTIK UTAMA --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 text-white-50">Total Karyawan</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalEmployees }}</h2>
                    </div>
                    <i class="bi bi-people-fill fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 text-white-50">Estimasi Gaji Bulan Ini</h6>
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($totalExpenditure, 0, ',', '.') }}</h4>
                    </div>
                    <i class="bi bi-cash-stack fs-1 text-white-50"></i>
                </div>
                <div class="mt-2 text-white-50 small">
                    {{ $pendingSalaries > 0 ? "$pendingSalaries karyawan belum dibayar" : "Semua lunas" }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 text-white-50">Hadir Hari Ini</h6>
                        <h2 class="mb-0 fw-bold">{{ $attendanceToday }}</h2>
                    </div>
                    <i class="bi bi-calendar-check fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-danger text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 text-white-50">Total Alpha (Bulan Ini)</h6>
                        <h2 class="mb-0 fw-bold">{{ $absentEmployees->count() }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: TABEL DETAIL --}}
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">📅 Aktivitas Absensi Terakhir</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $act)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $act->employee->nama }}</div>
                                <small class="text-muted">{{ $act->employee->jabatan }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($act->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                @if($act->status == 'hadir') <span class="badge bg-success">Hadir</span>
                                @elseif($act->status == 'sakit') <span class="badge bg-warning text-dark">Sakit</span>
                                @elseif($act->status == 'absen') <span class="badge bg-danger">Alpha</span>
                                @elseif($act->status == 'lembur') <span class="badge bg-primary">Lembur</span>
                                @else <span class="badge bg-secondary">{{ ucfirst($act->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $act->jam_masuk ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Belum ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('attendances.index') }}" class="text-decoration-none">Lihat Semua Absensi &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">⚡ Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('employees.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah Karyawan Baru
                    </a>
                    <a href="{{ route('attendances.create') }}" class="btn btn-outline-success text-start">
                        <i class="bi bi-fingerprint me-2"></i> Input Absensi Manual
                    </a>
                    <a href="{{ route('salaries.index') }}" class="btn btn-outline-dark text-start">
                        <i class="bi bi-calculator me-2"></i> Hitung Gaji Bulan Ini
                    </a>
                </div>
            </div>
        </div>

        @if($absentEmployees->count() > 0)
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-danger text-white py-2">
                <h6 class="mb-0"><i class="bi bi-bell-fill me-2"></i> Karyawan Alpha Terbanyak</h6>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($absentEmployees as $abs)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $abs->employee->nama }}</span>
                    <span class="badge bg-danger rounded-pill">{{ \Carbon\Carbon::parse($abs->tanggal)->format('d M') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection