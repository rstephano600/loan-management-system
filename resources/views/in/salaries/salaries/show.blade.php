@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Salary Details</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Employee:</strong> {{ $employeeSalary->employee->name ?? '-' }}
                </div>
                <div class="col-md-6">
                    <strong>Salary Level:</strong> {{ $employeeSalary->salaryLevel->name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Base Salary:</strong> {{ number_format($employeeSalary->base_salary, 2) }} {{ $employeeSalary->currency }}
                </div>
                <div class="col-md-4">
                    <strong>Bonus:</strong> {{ number_format($employeeSalary->bonus, 2) }} {{ $employeeSalary->currency }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $employeeSalary->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($employeeSalary->status) }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6"><strong>Effective From:</strong> {{ $employeeSalary->effective_from }}</div>
                <div class="col-md-6"><strong>Effective To:</strong> {{ $employeeSalary->effective_to ?? '-' }}</div>
            </div>
        </div>
    </div>

    <h5>Payment Records</h5>
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Currency</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employeeSalary->payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method ?? '-' }}</td>
                    <td>{{ $payment->currency }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">No payment records available.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('employee_salaries.edit', $employeeSalary->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('employee_salaries.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
