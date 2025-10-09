@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Upload Client Loan Photo</h4>

    <form action="{{ route('client-loan-photos.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="client_id" class="form-label">Client</label>
                <select name="client_id" class="form-select" required>
                    <option value="">-- Select Client --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="client_loan_id" class="form-label">Client Loan (optional)</label>
                <select name="client_loan_id" class="form-select">
                    <option value="">-- Select Loan --</option>
                    @foreach($loans as $loan)
                        <option value="{{ $loan->id }}">{{ $loan->client->first_name }} {{ $loan->client->last_name }} ({{ $loan->loan_number }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="date_captured" class="form-label">Date Captured</label>
                <input type="date" name="date_captured" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-success">Upload</button>
                <a href="{{ route('client-loan-photos.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
