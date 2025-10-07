@extends('layouts.app')
@section('title', 'Clients')
@section('page-title', 'A client')

@section('content')
<div class="container mt-4">
    <h3>Edit Client</h3>

    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('in.clients.form', ['mode' => 'edit'])

        <div class="mt-3">
            <button class="btn btn-primary">Update Client</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
