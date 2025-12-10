@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Employees</h3>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
</div>

<table class="table table-striped">
    <thead>
        <tr><th>NIP</th><th>Name</th><th>Jabatan</th><th>Gaji Pokok</th><th></th></tr>
    </thead>
    <tbody>
        @foreach($employees as $e)
            <tr>
                <td>{{ $e->nip }}</td>
                <td>{{ $e->nama }}</td>
                <td>{{ $e->jabatan }}</td>
                <td>{{ number_format($e->gaji_pokok,2) }}</td>
                <td>
                    <a href="{{ route('employees.edit', $e) }}" class="btn btn-sm btn-secondary">Edit</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $employees->links() }}

@endsection
