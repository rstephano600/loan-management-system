@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Expense Details</h4>
        </div>
        <div class="card-body">
            <h5>{{ $expense->expense_title }}</h5>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</p>
            <p><strong>Category:</strong> {{ $expense->category->name ?? '—' }}</p>
            <p><strong>Total Amount:</strong> {{ $expense->currency }} {{ number_format($expense->total_amount,2) }}</p>
            <p><strong>Status:</strong> <span class="badge 
                @if($expense->status==='approved') bg-success 
                @elseif($expense->status==='pending') bg-warning 
                @else bg-danger 
                @endif">{{ ucfirst($expense->status) }}</span></p>
            <p><strong>Description:</strong> {{ $expense->description ?? '—' }}</p>
            @if($expense->attachment)
            <p><strong>Attachment:</strong> <a href="{{ asset('storage/'.$expense->attachment) }}" target="_blank">View</a></p>
            @endif

            <hr>
            <h5>Expense Items</h5>
            @if($expense->items->count())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                            <th>Supplier</th>
                            <th>Attachment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expense->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $expense->currency }} {{ number_format($item->unit_cost,2) }}</td>
                            <td>{{ $expense->currency }} {{ number_format($item->total_cost,2) }}</td>
                            <td>{{ $item->supplier_name ?? '—' }}</td>
                            <td>
                                @if($item->attachment)
                                <a href="{{ asset('storage/'.$item->attachment) }}" target="_blank">View</a>
                                @else
                                —
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted">No items found.</p>
            @endif

            <a href="{{ route('expenses.index') }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</div>
@endsection
