@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Loans Management</h4>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Loan
        </a>
    </div>

    {{-- Filters & Search --}}
    <form method="GET" action="{{ route('loans.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by client or loan number" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="loan_category_id" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('loan_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach(['pending','approved','active','completed','defaulted','closed'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex">
            <button class="btn btn-success me-2">Filter</button>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- Loans Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Loan No.</th>
                        <th>Client</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Total Payable</th>
                        <th>Outstanding</th>
                        <th>Disbursed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loan->loan_number }}</td>
                            <td>{{ $loan->client->first_name ?? 'N/A' }}</td>
                            <td>{{ $loan->category->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $loan->status === 'completed' ? 'success' : ($loan->status === 'active' ? 'info' : 'warning') }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($loan->total_payable, 2) }} {{ $loan->category->currency ?? '' }}</td>
                            <td>{{ number_format($loan->total_outstanding, 2) }}</td>
                            <td>{{ $loan->disbursement_date ?? '---' }}</td>
                            <td>
                                <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this loan?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">No loans found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
