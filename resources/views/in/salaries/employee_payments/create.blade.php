@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Pay Employee Salary</h4>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <h5>{{ $salary->employee->name }}</h5>
            <p class="mb-2">
                <strong>Level:</strong> {{ $salary->salaryLevel->name ?? '-' }}<br>
                <strong>Total Payable:</strong>
                {{ number_format($salary->base_salary + $salary->bonus, 2) }} {{ $salary->currency }}
            </p>

            <form action="{{ route('employee_payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="employee_salary_id" value="{{ $salary->id }}">

                <div class="mb-3">
                    <label class="form-label">Amount Paid</label>
                    <input type="number" name="amount_paid" step="0.01" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <input type="text" name="payment_method" class="form-control" placeholder="Cash / Bank / Mpesa">
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachment (optional)</label>
                    <input type="file" name="attachment" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Submit Payment</button>
                <a href="{{ route('employee_payments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
