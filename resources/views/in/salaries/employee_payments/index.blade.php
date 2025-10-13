@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Employee Salary Payments</h4>

    
    {{-- Salary Payment Alerts --}}
    @if($dueSalaries->count() > 0)
        <div class="alert alert-warning">
            <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Salary Payment Alerts</h5>
            <p class="mb-2">The following employees have pending salaries (last paid ≥ 30 days ago):</p>

            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Level</th>
                            <th>Base Salary</th>
                            <th>Bonus</th>
                            <th>Last Payment Date</th>
                            <th>Days Since Last Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dueSalaries as $index => $salary)
                            @php
                                $last = $salary->lastPayment ? \Carbon\Carbon::parse($salary->lastPayment->payment_date) : null;
                                $daysAgo = $last ? $last->diffInDays(now()) : null;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $salary->employee->name ?? 'Unknown' }}</td>
                                <td>{{ $salary->salaryLevel->name ?? '-' }}</td>
                                <td>{{ number_format($salary->base_salary, 2) }} {{ $salary->currency }}</td>
                                <td>{{ number_format($salary->bonus, 2) }}</td>
                                <td>{{ $last ? $last->format('Y-m-d') : 'Never Paid' }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ $daysAgo ?? 'N/A' }} days
                                    </span>
                                </td>
                                <td>
                                   <a href="{{ route('employee_payments.create', $salary->id) }}" class="btn btn-sm btn-primary">Pay now</a>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i> All salaries are up-to-date.
        </div>
    @endif


    <form class="mb-3" method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search employee..." value="{{ $search }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Level</th>
                <th>Base Salary</th>
                <th>Bonus</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $salary)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $salary->employee->first_name ?? '-' }} {{ $salary->employee->last_name ?? '-' }}</td>
                    <td>{{ $salary->salaryLevel->name ?? '-' }}</td>
                    <td>{{ number_format($salary->base_salary, 2) }}</td>
                    <td>{{ number_format($salary->bonus, 2) }}</td>
                    <td><strong>{{ number_format($salary->base_salary + $salary->bonus, 2) }}</strong></td>
                    <td>
                        <a href="{{ route('employee_payments.create', $salary->id) }}" class="btn btn-sm btn-primary">Pay</a>
                        <a href="{{ route('employee_payments.show', $salary->id) }}" class="btn btn-sm btn-info text-white">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No active salaries found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $salaries->links() }}


</div>
@endsection
