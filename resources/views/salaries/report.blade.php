@extends('layouts.app')

@section('content')
<h3>Salary Report</h3>

<table class="table table-striped">
    <thead><tr><th>Periode</th><th>Employee</th><th>Gaji Bersih</th></tr></thead>
    <tbody>
        @foreach($salaries as $s)
            <tr>
                <td>{{ $s->periode }}</td>
                <td>{{ $s->employee->nama }} ({{ $s->employee->nip }})</td>
                <td>{{ number_format($s->gaji_bersih,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
