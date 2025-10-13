@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Expenses</h3>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Add Expense</a>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by title or category">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    @if($expenses->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $i => $expense)
                <tr>
                    <td>{{ $expenses->firstItem() + $i }}</td>
                    <td>{{ $expense->expense_title }}</td>
                    <td>{{ $expense->category->name ?? '—' }}</td>
                    <td>{{ $expense->currency }} {{ number_format($expense->total_amount, 2) }}</td>
                    <td>
                        <span class="badge 
                            @if($expense->status==='approved') bg-success 
                            @elseif($expense->status==='pending') bg-warning 
                            @else bg-danger 
                            @endif">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end">
        {{ $expenses->links() }}
    </div>
    @else
        <p class="text-muted">No expenses found.</p>
    @endif
</div>
@endsection
