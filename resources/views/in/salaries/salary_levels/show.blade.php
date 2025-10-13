@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Salary Level Details</h3>

    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <td>{{ $salaryLevel->name }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $salaryLevel->description ?? '-' }}</td>
        </tr>
        <tr>
            <th>Default Salary</th>
            <td>{{ number_format($salaryLevel->default_salary,2) ?? '-' }}</td>
        </tr>
        <tr>
            <th>Currency</th>
            <td>{{ $salaryLevel->currency }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($salaryLevel->status) }}</td>
        </tr>
        <tr>
            <th>Created By</th>
            <td>{{ $salaryLevel->creator?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Updated By</th>
            <td>{{ $salaryLevel->updater?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $salaryLevel->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $salaryLevel->updated_at->format('d M Y') }}</td>
        </tr>
    </table>

    <a href="{{ route('salary_levels.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('salary_levels.edit', $salaryLevel->id) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
