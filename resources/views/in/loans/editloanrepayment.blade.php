@extends('layouts.workingside')

@section('title','Edit Loan Repayment')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloanrepayment', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-md-6">

                    <label>Payment Date</label>

                    <input type="date"
                           name="payment_date"
                           value="{{ $data->payment_date }}"
                           class="form-control">

                </div>

                <div class="col-md-6">

                    <label>Amount Paid</label>

                    <input type="number"
                           step="0.01"
                           name="amount_paid"
                           value="{{ $data->amount_paid }}"
                           class="form-control">

                </div>

                <div class="col-md-6">

                    <label>Payment Method</label>

                    <input type="text"
                           name="payment_method"
                           value="{{ $data->payment_method }}"
                           class="form-control">

                </div>

                <div class="col-md-6">

                    <label>Reference Number</label>

                    <input type="text"
                           name="reference_number"
                           value="{{ $data->reference_number }}"
                           class="form-control">

                </div>

                <div class="col-md-12">

                    <label>Remarks</label>

                    <textarea name="remarks"
                              rows="4"
                              class="form-control">{{ $data->remarks }}</textarea>

                </div>

            </div>

            <div class="mt-4">

                <button type="submit"
                        class="arbif-btn-submit">

                    Update Repayment

                </button>

            </div>

        </form>

    </div>

</div>

@endsection