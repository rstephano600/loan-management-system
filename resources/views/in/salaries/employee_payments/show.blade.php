@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Salary Payment History for {{ $salary->employee->first_name ?? '-' }} {{ $salary->employee->last_name ?? '-' }}</h4>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <p><strong>Level:</strong> {{ $salary->salaryLevel->name ?? '-' }}</p>
            <p><strong>Base Salary:</strong> {{ number_format($salary->base_salary, 2) }} {{ $salary->currency }}</p>
            <p><strong>Bonus:</strong> {{ number_format($salary->bonus, 2) }} {{ $salary->currency }}</p>
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Amount Paid</th>
                <th>Date</th>
                <th>Method</th>
                <th>Status</th>
                <th>Attachment</th>
            </tr>
        </thead>
        <tbody>
    @forelse($salary->payments as $p)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ number_format($p->amount_paid, 2) }} {{ $p->currency }}</td>
            <td>{{ $p->payment_date }}</td>
            <td>{{ $p->payment_method }}</td>
            <td><span class="badge bg-success">{{ ucfirst($p->status) }}</span></td>
            <td>
                @if($p->attachment)
                    <a href="{{ asset('storage/'.$p->attachment) }}" target="_blank">View</a>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted">No payments yet.</td>
        </tr>
    @endforelse
</tbody>

{{-- ✅ total row --}}
@if($salary->payments->count() > 0)
<tr>
    <td colspan="6" class="text-center text-muted">
        Total Paid to Date:
        {{ number_format($salary->payments->sum('amount_paid'), 2) }}
        {{ $salary->payments->first()->currency ?? '' }}
    </td>
</tr>
@endif
</table>

    <a href="{{ route('employee_payments.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
