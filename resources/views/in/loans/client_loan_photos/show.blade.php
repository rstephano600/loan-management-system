@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Photo Details</h4>
    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <img src="{{ asset('storage/' . $clientLoanPhoto->photo) }}" 
                         alt="Client Loan Photo" class="img-fluid rounded">
                </div>
                <div class="col-md-7">
                    <h5>Client:</h5>
                    <p>{{ $clientLoanPhoto->client->first_name }} {{ $clientLoanPhoto->client->last_name }}</p>

                    <h5>Loan:</h5>
                    <p>{{ $clientLoanPhoto->loan ? 'Loan #' . $clientLoanPhoto->loan->id : '—' }}</p>

                    <h5>Description:</h5>
                    <p>{{ $clientLoanPhoto->description ?? '—' }}</p>

                    <h5>Date Captured:</h5>
                    <p>{{ $clientLoanPhoto->date_captured ?? '—' }}</p>

                    <h5>Uploaded By:</h5>
                    <p>{{ $clientLoanPhoto->creator?->name ?? '—' }}</p>

                    <div class="mt-3">
                        <a href="{{ route('client-loan-photos.edit', $clientLoanPhoto) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('client-loan-photos.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
