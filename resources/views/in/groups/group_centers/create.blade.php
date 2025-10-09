@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center Creation')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i> Add New Group Center</h4>
        </div>
        <div class="card-body">

            <form action="{{ route('group_centers.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    
                    {{-- Center Name --}}
                    <div class="col-md-6">
                        <label for="center_name" class="form-label fw-bold">Center Name <span class="text-danger">*</span></label>
                        <input type="text" name="center_name" id="center_name" class="form-control @error('center_name') is-invalid @enderror" 
                               value="{{ old('center_name') }}" required placeholder="e.g., Central Plaza Meeting Point">
                        @error('center_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Location --}}
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-bold">Location</label>
                        <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location') }}" placeholder="e.g., Street A, Building B">
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Collection Officer --}}
                    <div class="col-md-6">
                        <label for="collection_officer_id" class="form-label fw-bold">Collection Officer</label>
                        <select name="collection_officer_id" id="collection_officer_id" class="form-select @error('collection_officer_id') is-invalid @enderror">
                            <option value="">-- Select Officer --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('collection_officer_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('collection_officer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Details about meeting schedules or center operations.">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('group_centers.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="bi bi-save me-1"></i> Save Center
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
