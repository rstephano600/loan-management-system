@extends('layouts.app')

@section('title', 'Loan Details: ' . $loan->loan_number)
@section('page-title', 'Loan Details')

@section('content')
<div class="container py-4">

    {{-- Header and Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">Loan Details: {{ $loan->loan_number }}</h2>
        <div class="btn-group">
            {{-- Approve/Disburse Button (Conditional) --}}
            @if(in_array(Auth::user()->role, ['manager', 'Admin']) && $loan->status === 'pending')
                <a href="{{ route('loans.approve.edit', $loan->id) }}" class="btn btn-success d-flex align-items-center">
                    <i class="bi bi-check-circle me-2"></i> Approve/Disburse Loan
                </a>
            @endif
            {{-- Fill Collection Button --}}
            <a href="{{ route('daily_collections.create', ['loan_id' => $loan->id]) }}"
               class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-cash-coin me-2"></i> Fill Collection
            </a>
            <a href="{{ route('loan_request_continueng_client.index') }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>

    {{-- Dynamic Status Badge Logic --}}
    @php
        $statusClass = [
            'pending' => 'warning',
            'approved' => 'info',
            'active' => 'success', // Assuming 'disbursed' is now 'active'
            'completed' => 'primary',
            'defaulted' => 'danger',
            'closed' => 'secondary',
        ][$loan->status] ?? 'secondary';
    @endphp

    <div class="row">
        {{-- LOAN & CLIENT INFORMATION --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> General Loan Info</h5>
                    <span class="badge bg-{{ $statusClass }} fs-6">{{ ucfirst($loan->status) }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Loan Number:</strong>
                            <span class="fw-bold text-primary">{{ $loan->loan_number }}</span>
                        </li>

<li class="list-group-item d-flex justify-content-between align-items-center">
    <strong>Client:</strong>
    @if($loan->client_id && $loan->client)
        <a href="{{ route('clients.show', $loan->client_id) }}" class="text-decoration-none">
            {{ $loan->client->first_name }} {{ $loan->client->last_name }}
        </a>
    @else
        <span class="text-muted">N/A</span>
    @endif
</li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Group Center:</strong>
                            <span>{{ $loan->clients->groups->groupCenter->center_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Loan Category:</strong>
                            <span>{{ $loan->loanCategory->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Disbursement Date:</strong>
                            <span>{{ $loan->disbursement_date ? \Carbon\Carbon::parse($loan->disbursement_date)->format('M d, Y') : '-' }}</span>
                        </li>
                         <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Max Term (Days):</strong>
                            <span>{{ $loan->max_term_days ?? '-' }} days</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Max Term (Months):</strong>
                            <span>{{ $loan->max_term_months ?? '-' }} months</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Created By:</strong>
                            {{-- Assuming a relationship to get the creator's name --}}
                            <p class="text-muted mb-0 small">{{ $loan->creator->name ?? 'System' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- FINANCIAL BREAKDOWN --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i> Financial Overview</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        {{-- Requested / Disbursed --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <strong>Amount Requested:</strong>
                            <span class="fw-bold">{{ number_format($loan->amount_requested, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Amount Disbursed:</strong>
                            <span class="fw-bold text-success">{{ number_format($loan->amount_disbursed, 2) }}</span>
                        </li>

                        {{-- Fees and Interest --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Interest Rate:</strong>
                            <span>{{ $loan->interest_rate }}%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Estimated Interest Amount:</strong>
                            <span>{{ number_format($loan->interest_amount ?? 0, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Total Loan Fees:</strong>
                            <span class="fw-bold text-danger">{{ number_format($loan->total_fee ?? 0, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Membership Fee:</strong>
                            <span>{{ number_format($loan->membership_fee ?? 0, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Insurance Fee / Officer Visit Fee:</strong>
                            <span>{{ number_format($loan->insurance_fee ?? 0, 2) }} / {{ number_format($loan->officer_visit_fee ?? 0, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Other Fees:</strong>
                            <span>{{ number_format($loan->other_fee ?? 0, 2) }}</span>
                        </li>

                        {{-- Frequency and Total Payable --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Repayment Frequency:</strong>
                            <span>{{ ucfirst($loan->repayment_frequency ?? '-') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-primary text-white">
                            <strong>Total Repayable Amount:</strong>
                            <span class="fw-bold">{{ number_format($loan->repayable_amount ?? 0, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- PAYMENT SUMMARY --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Payment and Balance</h5>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-4 g-4 text-center">
                {{-- Total Amount Paid (Including Fees Paid) --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Total Amount Paid</small>
                        <h4 class="fw-bold text-success mb-0">{{ number_format($loan->total_amount_paid ?? 0, 2) }}</h4>
                    </div>
                </div>
                {{-- Outstanding Balance --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Outstanding Balance</small>
                        <h4 class="fw-bold text-danger mb-0">{{ number_format($loan->outstanding_balance ?? 0, 2) }}</h4>
                    </div>
                </div>
                {{-- Penalty Fee --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Total Penalty Fee</small>
                        <h4 class="fw-bold text-warning mb-0">{{ number_format($loan->penalty_fee ?? 0, 2) }}</h4>
                    </div>
                </div>
                {{-- Profit/Loss --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Profit/Loss</small>
                        @php
                            $profitLoss = $loan->profit_loss_amount ?? 0;
                            $profitLossClass = $profitLoss >= 0 ? 'success' : 'danger';
                        @endphp
                        <h4 class="fw-bold text-{{ $profitLossClass }} mb-0">{{ number_format(abs($profitLoss), 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- DUE DETAILS --}}
    <div class="card shadow-sm mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Current Due Summary</h5>
        </div>
        <div class="card-body">
             <div class="row row-cols-1 row-cols-md-3 g-4 text-center">
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Total Days Overdue</small>
                        <h4 class="fw-bold text-danger mb-0">{{ $loan->total_days_due ?? 0 }}</h4>
                    </div>
                </div>
                 <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Principal Due</small>
                        <h4 class="fw-bold text-danger mb-0">{{ number_format($loan->principal_due ?? 0, 2) }}</h4>
                    </div>
                </div>
                 <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Interest Due</small>
                        <h4 class="fw-bold text-danger mb-0">{{ number_format($loan->interest_due ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- CLOSURE DETAILS (Conditional) --}}
    @if($loan->closed_at)
    <div class="card shadow-sm mb-4 border-secondary">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-x-octagon me-2"></i> Loan Closure Details</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Closed On:</strong>
                    <span>{{ \Carbon\Carbon::parse($loan->closed_at)->format('M d, Y H:i A') }}</span>
                </li>
                <li class="list-group-item">
                    <strong>Closure Reason:</strong>
                    <p class="mb-0">{{ $loan->closure_reason ?? 'N/A' }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Preclosure Fee Applied:</strong>
                    <span>{{ number_format($loan->preclosure_fee ?? 0, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Preclosure Fee Paid:</strong>
                    <span>{{ number_format($loan->preclosure_fee_paid ?? 0, 2) }}</span>
                </li>
            </ul>
        </div>
    </div>
    @endif

</div>
@endsection