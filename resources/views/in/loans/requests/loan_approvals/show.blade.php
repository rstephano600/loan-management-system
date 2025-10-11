@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Approve Loan Request</h3>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Loan Number</dt>
                <dd class="col-sm-8">{{ $loan->loan_number }}</dd>

                <dt class="col-sm-4">Client</dt>
                <dd class="col-sm-8">{{ $loan->client?->first_name }} {{ $loan->client?->last_name }}</dd>

                <dt class="col-sm-4">Category</dt>
                <dd class="col-sm-8">{{ $loan->loanCategory?->name }}</dd>

                <dt class="col-sm-4">Amount Requested</dt>
                <dd class="col-sm-8">{{ number_format($loan->amount_requested, 2) }} {{ $loan->loanCategory?->currency }}</dd>

                <!-- <dt class="col-sm-4">Insurance Fee</dt>
                <dd class="col-sm-8">{{ number_format($loan->loanCategory?->insurance_fee, 2) }}</dd>

                <dt class="col-sm-4">Officer Visit Fee</dt>
                <dd class="col-sm-8">{{ number_format($loan->loanCategory?->officer_visit_fee, 2) }}</dd> -->

            </dl>

            <div class="d-flex justify-content-end mt-4">
                <form action="{{ route('loan-approvals.approve', $loan->id) }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Approve Loan
                    </button>
                </form>

                <form action="{{ route('loan-approvals.reject', $loan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="reason" value="Rejected by manager">
                    <button type="submit" class="btn btn-danger">
                        Reject Loan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <a href="{{ route('loan-approvals.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
