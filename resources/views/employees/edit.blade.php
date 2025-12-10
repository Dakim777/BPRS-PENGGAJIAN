@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Edit Data Karyawan: {{ $employee->nama }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $employee->nip) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $employee->nama) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
            </div>

            <hr>
            <h6 class="text-muted">Informasi Keuangan (Basis Gaji)</h6>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gaji Pokok (Rp)</label>
                    <input type="number" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok', $employee->gaji_pokok) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tunjangan Tetap (Rp)</label>
                    <input type="number" name="tunjangan" class="form-control" value="{{ old('tunjangan', $employee->tunjangan) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Potongan Tetap (Rp)</label>
                    <input type="number" name="potongan" class="form-control" value="{{ old('potongan', $employee->potongan) }}">
                    <small class="text-muted">Contoh: BPJS</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Data</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection