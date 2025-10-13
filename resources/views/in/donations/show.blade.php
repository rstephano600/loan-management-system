
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $donation->donation_title }}</h4>
            <a href="{{ route('donations.index') }}" class="btn btn-light btn-sm">← Back</a>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Donation Date:</strong> {{ $donation->donation_date }}
                </div>
                <div class="col-md-4">
                    <strong>Amount:</strong> {{ number_format($donation->amount, 2) }} {{ $donation->currency }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($donation->status) }}</span>
                </div>
            </div>

            <div class="mb-3">
                <strong>Description:</strong>
                <p class="mt-1">{{ $donation->description ?? '—' }}</p>
            </div>

            <div class="border rounded p-3 mb-4">
                <h5 class="text-primary">Recipient Details</h5>
                <p><strong>Name:</strong> {{ $donation->recipient_name }}</p>
                <p><strong>Type:</strong> {{ $donation->recipient_type ?? '—' }}</p>
                <p><strong>Contact:</strong> {{ $donation->recipient_contact ?? '—' }}</p>
                <p><strong>Support Type:</strong> {{ $donation->support_type ?? '—' }}</p>
            </div>

            @if($donation->items->count())
            <div class="table-responsive mb-3">
                <h5 class="text-primary">Donation Items</h5>
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit Value</th>
                            <th>Total Value</th>
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donation->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_value, 2) }}</td>
                            <td>{{ number_format($item->total_value, 2) }}</td>
                            <td>{{ $item->currency }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($donation->attachment)
            <div>
                <strong>Attachment:</strong>
                <a href="{{ asset('storage/'.$donation->attachment) }}" target="_blank" class="btn btn-outline-secondary btn-sm ms-2">View File</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
