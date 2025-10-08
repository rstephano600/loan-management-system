@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Loan Categories</h4>
        <a href="{{ route('loan_categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Loan Category
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search and Filters --}}
    <form method="GET" action="{{ route('loan_categories.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name or condition">
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="currency" class="form-select">
                <option value="">All Currencies</option>
                <option value="TZS" {{ request('currency') == 'TZS' ? 'selected' : '' }}>TZS</option>
                <option value="USD" {{ request('currency') == 'USD' ? 'selected' : '' }}>USD</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="frequency" class="form-select">
                <option value="">All Frequencies</option>
                @foreach(['daily','weekly','bi_weekly','monthly','quarterly'] as $freq)
                    <option value="{{ $freq }}" {{ request('frequency') == $freq ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_',' ',$freq)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-grid">
            <button class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
        </div>
    </form>

    {{-- Loan Categories Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Interest Rate (%)</th>
                    <th>Principal</th>
                    <th>Frequency</th>
                    <th>Currency</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loanCategories as $index => $loanCategory)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loanCategory->name }}</td>
                        <td>{{ $loanCategory->interest_rate }}</td>
                        <td>{{ number_format($loanCategory->principal_amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_',' ', $loanCategory->repayment_frequency)) }}</td>
                        <td>{{ $loanCategory->currency }}</td>
                        <td>
                            @if($loanCategory->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('loan_categories.show', $loanCategory) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('loan_categories.edit', $loanCategory) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('loan_categories.destroy', $loanCategory) }}" method="POST" onsubmit="return confirm('Delete this category?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>

                                <form action="{{ route('loan_categories.toggle', $loanCategory) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-secondary">
                                        {{ $loanCategory->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">No loan categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $loanCategories->links() }}
    </div>
</div>
@endsection
