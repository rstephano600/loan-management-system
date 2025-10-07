@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Group Centers</h3>
        <a href="{{ route('group_centers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Center
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('group_centers.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by name, code, or location..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="group_id" class="form-select">
                <option value="">Filter by Group</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id')==$group->id?'selected':'' }}>
                        {{ $group->group_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-secondary">Filter</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive shadow-sm">
        <table class="table table-bordered align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Center Code</th>
                    <th>Center Name</th>
                    <th>Group</th>
                    <th>Location</th>
                    <th>Officer</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centers as $center)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $center->center_code }}</td>
                        <td>{{ $center->center_name }}</td>
                        <td>{{ $center->group->group_name ?? '—' }}</td>
                        <td>{{ $center->location }}</td>
                        <td>{{ $center->collection_officer->first_name ?? '' }} {{ $center->collection_officer->last_name ?? '' }}</td>
                        <td>
                            <span class="badge bg-{{ $center->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($center->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('group_centers.show', $center->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('group_centers.edit', $center->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('group_centers.destroy', $center->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this center?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No centers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $centers->links() }}
    </div>
</div>
@endsection
