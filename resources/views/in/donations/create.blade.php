@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3">Add New Donation</h4>

    <form action="{{ route('donations.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Donation Title</label>
                <input type="text" name="donation_title" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Donation Date</label>
                <input type="date" name="donation_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Recipient Name</label>
                <input type="text" name="recipient_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Recipient Type</label>
                <input type="text" name="recipient_type" class="form-control" placeholder="Organization, Individual, etc.">
            </div>
            <div class="col-md-6">
                <label class="form-label">Recipient Contact</label>
                <input type="text" name="recipient_contact" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Support Type</label>
                <input type="text" name="support_type" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Total Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Currency</label>
                <input type="text" name="currency" value="TZS" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="fw-bold">Donation Items</h5>
        <div id="items-container">
            <div class="row g-3 item-row mb-2">
                <div class="col-md-4">
                    <input type="text" name="items[0][item_name]" class="form-control" placeholder="Item Name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" min="1" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="items[0][unit_value]" class="form-control" placeholder="Unit Value">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-item" class="btn btn-outline-primary mb-3">+ Add Item</button>

        <div>
            <button type="submit" class="btn btn-success">Save Donation</button>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');

    addBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'g-3', 'item-row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="items[${index}][item_name]" class="form-control" placeholder="Item Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][quantity]" class="form-control" placeholder="Qty" min="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${index}][unit_value]" class="form-control" placeholder="Unit Value">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">Remove</button>
            </div>
        `;
        container.appendChild(newRow);
        index++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endsection
