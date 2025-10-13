@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Donations</h4>
        <a href="{{ route('donations.create') }}" class="btn btn-primary">+ Add Donation</a>
    </div>

    <form method="GET" action="{{ route('donations.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by title, recipient, or support type" value="{{ request('search') }}">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    @if ($donations->count())
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Recipient</th>
                    <th>Amount</th>
                    <th>Support Type</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($donations as $donation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $donation->donation_title }}</td>
                    <td>{{ $donation->recipient_name }}</td>
                    <td>{{ number_format($donation->amount, 2) }} {{ $donation->currency }}</td>
                    <td>{{ $donation->support_type ?? '-' }}</td>
                    <td>{{ $donation->donation_date }}</td>
                    <td>{{ $donation->createdBy->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $donation->status == 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-info text-white">View</a>
                        <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this donation?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $donations->links() }}
    @else
    <div class="alert alert-info">No donations found.</div>
    @endif
</div>
@endsection
