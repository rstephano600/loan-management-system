@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-3">Edit Loan Category</h4>

    <form action="{{ route('loan_categories.update', $loanCategory) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        @include('in.loans.loan_categories.form', ['buttonText' => 'Update'])
    </form>
</div>
@endsection
