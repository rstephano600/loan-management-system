@extends('layouts.app')
@section('title', 'Create a New Loan Details')
@section('page-title', 'Create a New Loan Details') 

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">New Loan Request (New Client)</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the errors below:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('loan_request_new_client.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="client_id" class="form-label">Select Client <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->first_name }} {{ $client->last_name }}
                                    @if($client->group)
                                        (Group: {{ $client->group->group_name }})
                                    @endif
                                    @if($client->groupCenter)
                                        - Center: {{ $client->groupCenter->center_name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="loan_category_id" class="form-label">Loan Category <span class="text-danger">*</span></label>
                        <select name="loan_category_id" id="loan_category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }} ({{ number_format($category->amount_disbursed, 2) }} {{ $category->currency ?? 'TZS' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('loan_request_new_client.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
