@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Groups')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Edit Group - {{ $group->group_name }}</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Group Code *</label>
                <input type="text" name="group_code" class="form-control" value="{{ old('group_code', $group->group_code) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Group Name *</label>
                <input type="text" name="group_name" class="form-control" value="{{ old('group_name', $group->group_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Group Type</label>
                <input type="text" name="group_type" class="form-control" value="{{ old('group_type', $group->group_type) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $group->location) }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $group->description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Credit Officer</label>
                <select name="credit_officer_id" class="form-select">
                    <option value="">-- Select Officer --</option>
                    @foreach($creditOfficers as $officer)
                        <option value="{{ $officer->id }}" {{ $group->credit_officer_id == $officer->id ? 'selected' : '' }}>
                            {{ $officer->first_name }} {{ $officer->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Registration Date</label>
                <input type="date" name="registration_date" class="form-control"
                       value="{{ old('registration_date', $group->registration_date) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ $group->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$group->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Update Group</button>
            <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
