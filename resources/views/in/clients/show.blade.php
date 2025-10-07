@extends('layouts.app')
@section('title', 'Clients')
@section('page-title', 'A client')

@section('content')
<div class="container mt-4">
    <h3>Client Details</h3>

    <div class="card">
        <div class="card-body">
            <h5>{{ $client->client_type == 'individual' ? $client->first_name . ' ' . $client->last_name : $client->business_name }}</h5>
            <p><strong>Email:</strong> {{ $client->email }}</p>
            <p><strong>Phone:</strong> {{ $client->phone }}</p>
            <p><strong>Address:</strong> {{ $client->address_line1 }}, {{ $client->city }}, {{ $client->country }}</p>
            <p><strong>Status:</strong> {{ ucfirst($client->status) }}</p>
            <p><strong>Credit Rating:</strong> {{ $client->credit_rating ?? 'N/A' }}</p>
            <p><strong>KYC Completed:</strong> {{ $client->kyc_completed ? 'Yes' : 'No' }}</p>

            <div class="mt-3">
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
