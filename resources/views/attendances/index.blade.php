@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Attendances</h3>
    <a href="{{ route('attendances.create') }}" class="btn btn-primary">Add Attendance</a>
</div>

<table class="table table-striped">
    <thead>
        <tr><th>Tanggal</th><th>Employee</th><th>Status</th><th>Jam Masuk</th><th>Jam Keluar</th></tr>
    </thead>
    <tbody>
        @foreach($attendances as $a)
            <tr>
                <td>{{ $a->tanggal->format('Y-m-d') }}</td>
                <td>{{ $a->employee->nama }} ({{ $a->employee->nip }})</td>
                <td>{{ $a->status }}</td>
                <td>{{ $a->jam_masuk }}</td>
                <td>{{ $a->jam_keluar }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $attendances->links() }}

@endsection
