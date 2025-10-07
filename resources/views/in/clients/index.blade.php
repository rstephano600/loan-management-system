@extends('layouts.app')
@section('title', 'Clients')
@section('page-title', 'A client')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Client Management</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, email or phone">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Filter by Status --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Search</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('clients.create') }}" class="btn btn-success w-100">Add Client</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client Type</th>
                        <th>Full Name / Business</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Credit Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($client->client_type) }}</td>
                            <td>
                                {{ $client->client_type == 'individual' 
                                    ? $client->first_name . ' ' . $client->last_name 
                                    : $client->business_name }}
                            </td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $client->status == 'active' ? 'success' : ($client->status == 'inactive' ? 'secondary' : 'danger') }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td>{{ $client->credit_rating ?? '-' }}</td>
                            <td>
                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this client?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No clients found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
