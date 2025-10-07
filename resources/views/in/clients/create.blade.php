@extends('layouts.app')
@section('title', 'Clients')
@section('page-title', 'A client')

@section('content')
<div class="container mt-4">
    <h3>Add New Client</h3>

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf

        @include('in.clients.form', ['mode' => 'create'])

        <div class="mt-3">
            <button class="btn btn-primary">Save Client</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
