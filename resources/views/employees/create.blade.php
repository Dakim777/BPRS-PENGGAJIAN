@extends('layouts.app')

@section('content')
<h3>Add Employee</h3>
<form action="{{ route('employees.store') }}" method="post">
    @csrf
    <div class="mb-3">
        <label>NIP</label>
        <input name="nip" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Gaji Pokok</label>
        <input name="gaji_pokok" class="form-control" type="number" step="0.01">
    </div>
    <button class="btn btn-primary">Save</button>
</form>
@endsection
