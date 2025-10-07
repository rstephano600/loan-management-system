@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Groups')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Group Details - {{ $group->group_name }}</h4>

    <div class="card">
        <div class="card-body">
            <p><strong>Group Code:</strong> {{ $group->group_code }}</p>
            <p><strong>Group Type:</strong> {{ $group->group_type ?? '—' }}</p>
            <p><strong>Location:</strong> {{ $group->location ?? '—' }}</p>
            <p><strong>Description:</strong> {{ $group->description ?? '—' }}</p>
            <p><strong>Credit Officer:</strong> {{ $group->creditOfficer->first_name ?? '—' }}</p>
            <p><strong>Registration Date:</strong> {{ $group->registration_date ?? '—' }}</p>
            <p><strong>Status:</strong>
                @if($group->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </p>
        </div>
    </div>
    <h4>Group Members</h4>

@if($group->members->count())
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Member Code</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group->members as $member)
                <tr>
                    <td>{{ $member->member_code }}</td>
                    <td>{{ $member->employee->first_name }} {{ $member->employee->last_name }}</td>
                    <td>{{ $member->role_in_group ?? '-' }}</td>
                    <td>
                        <form action="{{ route('group_members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Remove this member?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No members in this group yet.</p>
@endif


    <div class="mt-4">
        <a href="{{ route('group_members.create', $group->id) }}" class="btn btn-primary">
    + Add Member
      </a>

        <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
