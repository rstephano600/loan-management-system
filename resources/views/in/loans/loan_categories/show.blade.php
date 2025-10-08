@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-3">{{ $loanCategory->name }} Details</h4>

    <div class="card p-4 shadow-sm">
        <table class="table table-borderless">
            <tr><th>Name</th><td>{{ $loanCategory->name }}</td></tr>
            <tr><th>Interest Rate</th><td>{{ $loanCategory->interest_rate }}%</td></tr>
            <tr><th>Principal Amount</th><td>{{ number_format($loanCategory->principal_amount, 2) }} {{ $loanCategory->currency }}</td></tr>
            <tr><th>Term</th><td>{{ $loanCategory->max_term_months }} months / {{ $loanCategory->max_term_days ?? '-' }} days</td></tr>
            <tr><th>Repayment Frequency</th><td>{{ ucfirst(str_replace('_',' ', $loanCategory->repayment_frequency)) }}</td></tr>
            <tr><th>Installment Amount</th><td>{{ number_format($loanCategory->installment_amount, 2) }} {{ $loanCategory->currency }}</td></tr>
            <tr><th>Total Repayable</th><td>{{ number_format($loanCategory->total_repayable_amount, 2) }} {{ $loanCategory->currency }}</td></tr>
            <tr><th>Conditions</th><td>{{ $loanCategory->conditions }}</td></tr>
            <tr><th>Status</th>
                <td>
                    @if($loanCategory->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="mt-3">
            <a href="{{ route('loan_categories.edit', $loanCategory) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
