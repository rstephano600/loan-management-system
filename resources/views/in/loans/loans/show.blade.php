@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Loan Details</h4>
    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Loan Number:</strong> {{ $loan->loan_number }}</p>
            <p><strong>Client:</strong> {{ $loan->client->name ?? 'N/A' }}</p>
            <p><strong>Category:</strong> {{ $loan->category->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($loan->status) }}</span></p>
            <p><strong>Disbursement Date:</strong> {{ $loan->disbursement_date ?? '---' }}</p>
            <p><strong>Total Payable:</strong> {{ number_format($loan->total_payable,2) }} {{ $loan->category->currency ?? '' }}</p>
            <p><strong>Total Outstanding:</strong> {{ number_format($loan->total_outstanding,2) }}</p>
            <p><strong>Total Paid:</strong> {{ number_format($loan->total_paid,2) }}</p>
            <p><strong>Created By:</strong> {{ $loan->creator->name ?? 'System' }}</p>
            <hr>
            <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
