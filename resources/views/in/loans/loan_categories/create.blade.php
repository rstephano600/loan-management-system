@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-3">Add Loan Category</h4>

    <form action="{{ route('loan_categories.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        @include('in.loans.loan_categories.form', ['buttonText' => 'Save'])
    </form>
</div>
@endsection
