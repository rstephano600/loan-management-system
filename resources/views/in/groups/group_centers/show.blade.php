@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i> Center Details: <span class="text-primary">{{ $groupCenter->center_name }}</span>
        </h2>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('group_centers.index') }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('group_centers.edit', $groupCenter->id) }}" class="btn btn-warning d-flex align-items-center">
                <i class="bi bi-pencil-square me-1"></i> Edit Center
            </a>
            {{-- Optional: Add an Export button here if needed for single center data --}}
            {{-- <button onclick="exportCenterData({{ $groupCenter->id }})" class="btn btn-success d-flex align-items-center">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Data
            </button> --}}
        </div>
    </div>

    <div class="card shadow-lg border-start border-primary border-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 text-primary">General Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Center Code:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->center_code }}</p>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Group Affiliated:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->group->group_name ?? '—' }}</p>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Collection Officer:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->collection_officer->first_name ?? '—' }} {{ $groupCenter->collection_officer->last_name ?? '' }}</p>
                </div>

                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Location:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->location }}</p>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Status:</strong></p>
                    <p class="h6 mb-0">
                        <span class="badge bg-{{ $groupCenter->status == 'active' ? 'success' : 'danger' }} fs-6">
                            {{ ucfirst($groupCenter->status) }}
                        </span>
                    </p>
                </div>
                
                <div class="col-12 mt-4">
                    <p class="mb-2"><strong>Description:</strong></p>
                    <div class="border p-3 rounded bg-light">
                        <p class="mb-0 text-muted">{{ $groupCenter->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    {{-- Placeholder for Group Center Members or Meetings Schedule --}}
    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Related Data (e.g., Meeting Schedule)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Data structure for members or meeting schedule can be added here.</p>
            </div>
        </div>
    </div>
</div>
@endsection
