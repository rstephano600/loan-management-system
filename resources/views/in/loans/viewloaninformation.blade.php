@extends('layouts.workingside')

@section('title', 'View Loan Information')

@section('page-title', 'Loan Information')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="d-flex justify-content-between align-items-center mb-4 no-print">

            <h4 class="mb-0">
                <button >
                @can('approve-loans')
                <a href="{{ route('approveloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Approve This Loan</span></a>
                @endcan
                </button>
            </h4>

            <div>

                <button onclick="window.print()"
                        class="arbif-btn-submit">

                    <i class="fas fa-print"></i>
                    Print

                </button>

                <a href="{{ url()->previous() }}"
                   class="arbif-btn-cancel">

                    <i class="fas fa-arrow-left"></i>
                    Back

                </a>

            </div>

        </div>
    </div>
</div>



<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="d-flex justify-content-between align-items-center mb-4 no-print">

            <h4 class="mb-0">

                <i class="fas fa-file-invoice-dollar"></i>

                Loan Profile

            </h4>

            <div>

                <button onclick="window.print()"
                        class="arbif-btn-submit">

                    <i class="fas fa-print"></i>
                    Print

                </button>

                <a href="{{ url()->previous() }}"
                   class="arbif-btn-cancel">

                    <i class="fas fa-arrow-left"></i>
                    Back

                </a>

            </div>

        </div>


        <div id="printArea">

            {{-- HEADER --}}

            <div class="text-center mb-4">

                <img src="{{ asset('images/arbif.png') }}"
                     alt="ARBIF Logo"
                     style="height:100px;">

                <h2 class="mt-3 mb-1">
                    AIR-BIF MICROFINANCE
                </h2>

                <h4>
                    Loan Information Profile
                </h4>

            </div>


            {{-- LOAN NUMBER --}}

            <div class="text-center mb-4">

                <span class="badge bg-primary p-3">

                    Loan Number:
                    {{ $data->loan_number ?? 'N/A' }}

                </span>

            </div>


            <div class="row">

                {{-- CLIENT INFORMATION --}}

                <div class="col-md-6">

                    <h5 class="border-bottom pb-2">
                        Client Information
                    </h5>

                    <table class="table table-bordered">

                        <tr>
                            <th width="40%">Client Name</th>
                            <td>
                                {{ optional($data->client)->FullName ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Phone</th>
                            <td>
                                {{ optional($data->client)->PhoneNumber ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Group Center</th>
                            <td>
                                {{ optional($data->groupCenter)->center_name ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Group</th>
                            <td>
                                {{ optional($data->group)->group_name ?? 'N/A' }}
                            </td>
                        </tr>

                    </table>

                </div>


                {{-- LOAN DETAILS --}}

                <div class="col-md-6">

                    <h5 class="border-bottom pb-2">
                        Loan Details
                    </h5>

                    <table class="table table-bordered">

                        <tr>
                            <th width="40%">Loan Category</th>
                            <td>
                                {{ optional($data->loanCategory)->name ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Application Date</th>
                            <td>
                                {{ $data->application_date ? \Carbon\Carbon::parse($data->application_date)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Expected End Date</th>
                            <td>
                                {{ $data->expected_end_date ? \Carbon\Carbon::parse($data->expected_end_date)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-success">
                                    {{ $data->status }}
                                </span>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>


            {{-- FINANCIAL INFORMATION --}}

            <div class="mt-4">

                <h5 class="border-bottom pb-2">
                    Financial Information
                </h5>

                <table class="table table-bordered">

                    <tr>

                        <th>Amount Requested</th>
                        <th>Amount Disbursed</th>
                        <th>Interest Rate</th>
                        <th>Interest Amount</th>

                    </tr>

                    <tr>

                        <td>
                            {{ number_format($data->amount_requested ?? 0,2) }}
                        </td>

                        <td>
                            {{ number_format($data->amount_disbursed ?? 0,2) }}
                        </td>

                        <td>
                            {{ $data->interest_rate ?? 0 }}%
                        </td>

                        <td>
                            {{ number_format($data->interest_amount ?? 0,2) }}
                        </td>

                    </tr>

                </table>

            </div>


            {{-- FEES --}}

            <div class="mt-4">

                <h5 class="border-bottom pb-2">
                    Fees Information
                </h5>

                <table class="table table-bordered">

                    <tr>

                        <th>Insurance Fee</th>
                        <th>Officer Visit Fee</th>
                        <th>Penalty Fee</th>
                        <th>Preclosure Fee</th>

                    </tr>

                    <tr>

                        <td>
                            {{ number_format($data->insurance_fee ?? 0,2) }}
                        </td>

                        <td>
                            {{ number_format($data->officer_visit_fee ?? 0,2) }}
                        </td>

                        <td>
                            {{ number_format($data->penalty_fee ?? 0,2) }}
                        </td>

                        <td>
                            {{ number_format($data->preclosure_fee ?? 0,2) }}
                        </td>

                    </tr>

                </table>

            </div>


            {{-- REPAYMENT INFORMATION --}}

            <div class="mt-4">

                <h5 class="border-bottom pb-2">
                    Repayment Information
                </h5>

                <table class="table table-bordered">

                    <tr>

                        <th>Repayment Frequency</th>
                        <th>Client Installment</th>
                        <th>Total Paid</th>
                        <th>Outstanding Balance</th>

                    </tr>

                    <tr>

                        <td>
                            {{ $data->repayment_frequency }}
                        </td>

                        <td>
                            {{ number_format($data->client_payable_frequency ?? 0,2) }}
                        </td>

                        <td>
                            {{ number_format($data->amount_paid ?? 0,2) }}
                        </td>

                        <td>

                            <strong class="text-danger">

                                {{ number_format($data->outstanding_balance ?? 0,2) }}

                            </strong>

                        </td>

                    </tr>

                </table>

            </div>


            {{-- NOTES --}}

            <div class="mt-4">

                <h5 class="border-bottom pb-2">
                    Remarks / Notes
                </h5>

                <div class="border rounded p-3">

                    {!! nl2br(e($data->remarks ?? 'No remarks available')) !!}

                </div>

            </div>


            {{-- FOOTER --}}

            <div class="row mt-5">

                <div class="col-md-6">

                    <strong>Prepared By:</strong>

                    <br>

                    {{ Auth()->user()->name }}

                </div>

                <div class="col-md-6 text-end">

                    <strong>Date:</strong>

                    <br>

                    {{ now()->format('d M Y H:i A') }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

@media print {

    .no-print,
    .sidebar,
    .navbar,
    footer {
        display: none !important;
    }

    .arbif-card {
        border: none !important;
        box-shadow: none !important;
    }

}

.table th {
    background: #f8f9fa;
}

</style>

@endpush