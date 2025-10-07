@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Groups')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Add New Group</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('groups.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Group Name *</label>
                <input type="text" name="group_name" class="form-control" value="{{ old('group_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Group Type</label>
                <input type="text" name="group_type" class="form-control" value="{{ old('group_type') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Registration Date</label>
                <input type="date" name="registration_date" class="form-control" value="{{ old('registration_date') }}">
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Save Group</button>
            <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
