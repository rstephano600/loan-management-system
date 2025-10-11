@extends('layouts.app')
@section('title', 'Continuing Clients Loan Details')
@section('page-title', 'Continuing Clients Loan Details')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Loan Requests</h3>
        <a href="{{ route('loan_request_continueng_client.create') }}" class="btn btn-primary">+ New Loan Request</a>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Loan Number</th>
                        <th>Client</th>
                        <th>Category</th>
                        <th>Amount Requested</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loan->loan_number }}</td>
                            <td>{{ $loan->client?->first_name }} {{ $loan->client?->last_name }}</td>
                            <td>{{ $loan->loanCategory?->name }}</td>
                            <td>{{ number_format($loan->amount_requested, 2) }} {{ $loan->loanCategory?->currency }}</td>
                            <td>
                                <span class="badge bg-{{ $loan->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('loan_request_continueng_client.show', $loan->id) }}" class="btn btn-sm btn-info"> <i class="bi bi-eye"></i></a>
                                <a href="{{ route('loan_request_continueng_client.edit', $loan->id) }}" class="btn btn-sm btn-secondary"> <i class="bi bi-pencil"></i></a>
                                <form action="{{ route('loan_request_continueng_client.destroy', $loan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this loan request?')"><i class="bi bi-trash"></i></button>
                                </form>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No loan requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
