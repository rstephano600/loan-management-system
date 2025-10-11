@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Loan Categories</h3>
        <a href="{{ route('loan_categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Category
        </a>
    </div>

    {{-- ✅ Search & Filters --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search name or condition"
                value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="currency" class="form-select">
                <option value="">All Currencies</option>
                <option value="TZS" {{ request('currency')=='TZS' ? 'selected' : '' }}>TZS</option>
                <option value="USD" {{ request('currency')=='USD' ? 'selected' : '' }}>USD</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="frequency" class="form-select">
                <option value="">All Frequencies</option>
                @foreach(['daily','weekly','bi_weekly','monthly','quarterly'] as $f)
                    <option value="{{ $f }}" {{ request('frequency')==$f ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $f)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
        </div>
    </form>

    {{-- ✅ Table --}}
    <div class="table-responsive shadow-sm">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Amount ({{ config('app.currency','TZS') }})</th>
                    <th>Interest %</th>
                    <th>Frequency</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($loanCategories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name ?? '-' }}</td>
                        <td>{{ number_format($cat->amount_disbursed,2) }}</td>
                        <td>{{ $cat->interest_rate }}%</td>
                        <td>{{ ucfirst(str_replace('_',' ', $cat->repayment_frequency)) }}</td>
                        <td>
                            <span class="badge bg-{{ $cat->is_active ? 'success' : 'secondary' }}">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $cat->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('loan_categories.show',$cat) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('loan_categories.edit',$cat) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('loan_categories.destroy',$cat) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No loan categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $loanCategories->withQueryString()->links() }}
    </div>
</div>
@endsection
