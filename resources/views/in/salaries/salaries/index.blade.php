@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Employee Salaries</h4>

    <a href="{{ route('employee_salaries.create') }}" class="btn btn-primary mb-3">Add New Salary</a>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Salary Level</th>
                <th>Base Salary</th>
                <th>Bonus</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Effective From</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $salary)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $salary->employee->first_name ?? '-' }} {{ $salary->employee->last_name ?? '-' }}</td>
                    <td>{{ $salary->salaryLevel->name ?? '-' }}</td>
                    <td>{{ number_format($salary->base_salary, 2) }}</td>
                    <td>{{ number_format($salary->bonus, 2) }}</td>
                    <td>{{ $salary->currency }}</td>
                    <td><span class="badge bg-{{ $salary->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($salary->status) }}</span></td>
                    <td>{{ $salary->effective_from }}</td>
                    <td>
                        <a href="{{ route('employee_salaries.show', $salary->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('employee_salaries.edit', $salary->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('employee_salaries.destroy', $salary->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this salary?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $salaries->links() }}
</div>
@endsection
