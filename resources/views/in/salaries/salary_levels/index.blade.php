@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Salary Levels</h3>

    <div class="mb-3 d-flex justify-content-between">
        <form action="{{ route('salary_levels.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by name or description" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <a href="{{ route('salary_levels.create') }}" class="btn btn-success">+ Add Salary Level</a>
    </div>

    @if($salaryLevels->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Default Salary</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaryLevels as $level)
            <tr>
                <td>{{ $level->name }}</td>
                <td>{{ number_format($level->default_salary,2) }}</td>
                <td>{{ $level->currency }}</td>
                <td>{{ ucfirst($level->status) }}</td>
                <td>
                    <a href="{{ route('salary_levels.show', $level->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('salary_levels.edit', $level->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('salary_levels.destroy', $level->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $salaryLevels->links() }}

    @else
        <p>No Salary Levels found.</p>
    @endif
</div>
@endsection
