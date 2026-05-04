@extends('layouts.app')

@section('title', 'Loan Photo Records')
@section('page-title', 'Client Loan Photos')

@section('content')
<div class="container-fluid py-4">

<h3>Assign Permissions to: {{ $user->name }}</h3>

<form method="POST" action="{{ route('permissionsstore') }}">
    @csrf

    <input type="hidden" name="User_id" value="{{ $user->id }}">

    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Permission</th>
            </tr>
        </thead>

        <tbody>
            @foreach($permissions as $permission)
            <tr>
                <td>
                    <input type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->id }}"
                        {{ in_array($permission->id, $assigned) ? 'checked' : '' }}>
                </td>
                <td>{{ $permission->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit">Save Permissions</button>
</form>

@endsection