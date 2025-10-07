@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center')

@section('content')
<div class="container mt-4">
    <h3>Edit Group Center</h3>
    <hr>

    <form action="{{ route('group_centers.update', $groupCenter->id) }}" method="POST" class="card shadow-sm p-4">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Center Code</label>
                <input type="text" name="center_code" class="form-control" value="{{ $groupCenter->center_code }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Center Name</label>
                <input type="text" name="center_name" class="form-control" value="{{ $groupCenter->center_name }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Group</label>
            <select name="group_id" class="form-select" required>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ $groupCenter->group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->group_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $groupCenter->location }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Collection Officer</label>
                <select name="collection_officer_id" class="form-select">
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $groupCenter->collection_officer_id == $emp->id ? 'selected' : '' }}>
                            {{ $emp->first_name }} {{ $emp->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $groupCenter->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ $groupCenter->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $groupCenter->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Center</button>
        <a href="{{ route('group_centers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
