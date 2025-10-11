@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Pending Loan Approvals</h3>

    @if ($loans->isEmpty())
        <div class="alert alert-info mt-3">No pending loan approvals.</div>
    @else
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Loan Number</th>
                            <th>Client</th>
                            <th>Category</th>
                            <th>Requested</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loan->loan_number }}</td>
                                <td>{{ $loan->client?->first_name }} {{ $loan->client?->last_name }}</td>
                                <td>{{ $loan->loanCategory?->name }}</td>
                                <td>{{ number_format($loan->amount_requested, 2) }} {{ $loan->loanCategory?->currency }}</td>
                                <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('loan-approvals.show', $loan->id) }}" class="btn btn-sm btn-info">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
