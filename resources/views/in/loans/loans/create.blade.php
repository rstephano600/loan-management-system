@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Create New Loan</h4>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('loans.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }}{{ $client->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Loan Category</label>
                        <select name="loan_category_id" class="form-select" id="loan_category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-interest="{{ $category->interest_rate }}" data-principal="{{ $category->principal_amount }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="loan-info" class="bg-light p-3 rounded mb-3 d-none">
                    <h6>Loan Details</h6>
                    <p><strong>Principal:</strong> <span id="principal"></span></p>
                    <p><strong>Interest Rate:</strong> <span id="rate"></span>%</p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Disbursement Date</label>
                        <input type="date" name="disbursement_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="active">Active</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary">Save Loan</button>
                <a href="{{ route('loans.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('loan_category').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const rate = selected.getAttribute('data-interest');
    const principal = selected.getAttribute('data-principal');
    if(rate && principal) {
        document.getElementById('loan-info').classList.remove('d-none');
        document.getElementById('rate').innerText = rate;
        document.getElementById('principal').innerText = parseFloat(principal).toLocaleString();
    } else {
        document.getElementById('loan-info').classList.add('d-none');
    }
});
</script>
@endsection
