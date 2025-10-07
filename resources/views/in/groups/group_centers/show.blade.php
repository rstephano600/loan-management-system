@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $groupCenter->center_name }} Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Center Code:</strong> {{ $groupCenter->center_code }}</p>
            <p><strong>Group:</strong> {{ $groupCenter->group->group_name ?? '—' }}</p>
            <p><strong>Location:</strong> {{ $groupCenter->location }}</p>
            <p><strong>Officer:</strong> {{ $groupCenter->collection_officer->first_name ?? '' }} {{ $groupCenter->collection_officer->last_name ?? '' }}</p>
            <p><strong>Description:</strong> {{ $groupCenter->description }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $groupCenter->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($groupCenter->status) }}
                </span>
            </p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('group_centers.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('group_centers.edit', $groupCenter->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
