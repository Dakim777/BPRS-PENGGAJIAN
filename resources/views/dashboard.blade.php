@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Total Employees</h5>
            <p class="display-6">{{ $totalEmployees ?? 0 }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Attendances This Month</h5>
            <p class="display-6">{{ $totalAttendances ?? 0 }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Salaries This Period</h5>
            <p class="display-6">{{ $totalSalaries ?? 0 }}</p>
        </div>
    </div>
</div>
@endsection
