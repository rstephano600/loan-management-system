@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Salary Level</h3>

    <form action="{{ route('salary_levels.update', $salaryLevel->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $salaryLevel->name) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $salaryLevel->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Default Salary</label>
            <input type="number" step="0.01" name="default_salary" class="form-control" value="{{ old('default_salary', $salaryLevel->default_salary) }}">
        </div>

        <div class="mb-3">
            <label>Currency</label>
            <input type="text" name="currency" class="form-control" value="{{ old('currency', $salaryLevel->currency) }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ $salaryLevel->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $salaryLevel->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('salary_levels.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
