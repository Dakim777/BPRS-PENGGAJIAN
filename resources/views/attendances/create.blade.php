@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">Input Absensi Harian</div>
    <div class="card-body">
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Karyawan</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->nip }} - {{ $emp->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Kehadiran</label>
                <select name="status" class="form-select" id="statusSelect" required>
                    <option value="hadir">Hadir</option>
                    <option value="lembur">Lembur</option>
                    <option value="sakit">Sakit</option>
                    <option value="cuti">Cuti</option>
                    <option value="absen">Absen (Alpha)</option>
                </select>
            </div>

            <div class="row" id="timeInputs">
                <div class="col-6 mb-3">
                    <label class="form-label">Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="form-control">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Jam Keluar</label>
                    <input type="time" name="jam_keluar" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Simpan Absensi</button>
        </form>
    </div>
</div>
@endsection