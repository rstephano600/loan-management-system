@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Expense Categories</h3>
        <a href="{{ route('expense-categories.create') }}" class="btn btn-primary">+ Add Category</a>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or description">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    @if($categories->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <!-- <th>Created By</th> -->
                    <!-- <th>Updated By</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $i => $category)
                <tr>
                    <td>{{ $categories->firstItem() + $i }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $category->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <!-- <td>{{ $category->creator->name ?? '—' }}</td> -->
                    <!-- <td>{{ $category->editor->name ?? '—' }}</td> -->
                    <td>
                        <a href="{{ route('expense-categories.show', $category->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('expense-categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('expense-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $categories->links() }}
    </div>
    @else
        <p class="text-muted">No expense categories found.</p>
    @endif
</div>
@endsection
