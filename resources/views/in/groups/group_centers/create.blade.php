@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center')

@section('content')
<div class="container mt-4">
    <h3>Add New Group Center</h3>
    <hr>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Check the form below for errors.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('group_centers.store') }}" method="POST" class="card shadow-sm p-4">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Center Name</label>
                <input type="text" name="center_name" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Group</label>
            <select name="group_id" class="form-select" required>
                <option value="">Select Group</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Collection Officer</label>
                <select name="collection_officer_id" class="form-select">
                    <option value="">Select Officer</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Center</button>
        <a href="{{ route('group_centers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
