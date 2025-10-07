@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Groups')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Groups Management</h4>

    {{-- Search bar --}}
    <form action="{{ route('groups.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search group name or code..."
               value="{{ $search }}">
        <button class="btn btn-primary">Search</button>
    </form>

    <div class="mb-3 text-end">
        <a href="{{ route('groups.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New Group
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Group Code</th>
                    <th>Group Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Officer</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $key => $group)
                <tr>
                    <td>{{ $groups->firstItem() + $key }}</td>
                    <td>{{ $group->group_code }}</td>
                    <td>{{ $group->group_name }}</td>
                    <td>{{ $group->group_type ?? '—' }}</td>
                    <td>{{ $group->location ?? '—' }}</td>
                    <td>{{ $group->creditOfficer->first_name ?? '—' }}</td>
                    <td>{{ $group->registration_date ?? '—' }}</td>
                    <td>
                        @if($group->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center">No groups found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $groups->links() }}
    </div>
</div>
@endsection
