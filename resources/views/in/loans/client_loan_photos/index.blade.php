@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Client Loan Photos</h4>
        <a href="{{ route('client-loan-photos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Photo
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($photos->isEmpty())
        <div class="alert alert-info">No client loan photos available.</div>
    @else
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Thumbnail</th>
                    <th>Client</th>
                    <th>Loan</th>
                    <th>Description</th>
                    <th>Date Captured</th>
                    <th>Uploaded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($photos as $index => $photo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $photo->photo) }}" 
                             alt="Photo" class="img-thumbnail" width="70">
                    </td>
                    <td>{{ $photo->client->first_name }} {{ $photo->client->last_name }}</td>
                    <td>{{ $photo->loan ? 'Loan #' . $photo->loan->id : '—' }}</td>
                    <td>{{ Str::limit($photo->description, 40) }}</td>
                    <td>{{ $photo->date_captured ?? '—' }}</td>
                    <td>{{ $photo->creator?->name ?? '—' }}</td>
                    <td>
                        <a href="{{ route('client-loan-photos.show', $photo) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('client-loan-photos.edit', $photo) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('client-loan-photos.destroy', $photo) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this photo?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $photos->links() }}
    </div>
    @endif
</div>
@endsection
