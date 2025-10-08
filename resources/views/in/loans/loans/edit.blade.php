@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Edit Loan</h4>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Client</label>
                        <select name="client_id" class="form-select">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $loan->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Loan Category</label>
                        <select name="loan_category_id" class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $loan->loan_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Disbursement Date</label>
                        <input type="date" name="disbursement_date" class="form-control" value="{{ $loan->disbursement_date }}">
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending','approved','active','completed','defaulted','closed'] as $status)
                                <option value="{{ $status }}" {{ $loan->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary">Update Loan</button>
                <a href="{{ route('loans.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
