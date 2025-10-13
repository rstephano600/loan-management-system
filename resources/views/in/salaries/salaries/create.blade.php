@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Add Employee Salary</h4>

    @if(session('warning'))
<div class="alert alert-warning">
    {{ session('warning') }}
    <form method="POST" action="{{ route('employee_salaries.store') }}">
        @csrf
        @foreach(old() as $key => $value)
            @if(is_array($value))
                @foreach($value as $subkey => $subvalue)
                    <input type="hidden" name="{{ $key }}[{{ $subkey }}]" value="{{ $subvalue }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        <input type="hidden" name="confirm_multiple" value="1">
        <button type="submit" class="btn btn-warning mt-2">Yes, continue anyway</button>
    </form>
</div>
@endif


    <form action="{{ route('employee_salaries.store') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">Select Employee</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Salary Level</label>
                <select name="salary_level_id" class="form-select">
                    <option value="">None</option>
                    @foreach ($salaryLevels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }} ( {{ $level->default_salary }} {{$level->currency}} )</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label">Bonus</label>
                <input type="number" name="bonus" step="0.01" class="form-control" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Effective From</label>
                <input type="date" name="effective_from" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Effective To</label>
                <input type="date" name="effective_to" class="form-control">
            </div>
        </div>

        <!-- <hr>
        <h5>Salary Payments</h5>

        <table class="table table-bordered" id="paymentsTable">
            <thead class="table-light">
                <tr>
                    <th>Amount Paid</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="number" name="payments[0][amount_paid]" step="0.01" class="form-control" value="0"></td>
                    <td><input type="date" name="payments[0][payment_date]" class="form-control" value="0" ></td>
                    <td><input type="text" name="payments[0][payment_method]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" id="addPaymentRow" class="btn btn-outline-primary btn-sm">+ Add Payment</button> -->

        <div class="mt-4">
            <button class="btn btn-success">Save Salary</button>
            <a href="{{ route('employee_salaries.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
let rowIndex = 1;
document.getElementById('addPaymentRow').addEventListener('click', function () {
    const table = document.getElementById('paymentsTable').querySelector('tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="number" name="payments[${rowIndex}][amount_paid]" step="0.01" class="form-control" required></td>
        <td><input type="date" name="payments[${rowIndex}][payment_date]" class="form-control" required></td>
        <td><input type="text" name="payments[${rowIndex}][payment_method]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
    `;
    table.appendChild(newRow);
    rowIndex++;
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
