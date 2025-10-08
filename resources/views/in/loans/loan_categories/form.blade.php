<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name', $loanCategory->name ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Interest Rate (%)</label>
        <input type="number" step="0.01" name="interest_rate" value="{{ old('interest_rate', $loanCategory->interest_rate ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Currency</label>
        <select name="currency" class="form-select">
            <option value="TZS" {{ old('currency', $loanCategory->currency ?? '') == 'TZS' ? 'selected' : '' }}>TZS</option>
            <option value="USD" {{ old('currency', $loanCategory->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Principal Amount</label>
        <input type="number" step="0.01" name="principal_amount" value="{{ old('principal_amount', $loanCategory->principal_amount ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Min Amount</label>
        <input type="number" step="0.01" name="min_amount" value="{{ old('min_amount', $loanCategory->min_amount ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Max Amount</label>
        <input type="number" step="0.01" name="max_amount" value="{{ old('max_amount', $loanCategory->max_amount ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Installment Amount</label>
        <input type="number" step="0.01" name="installment_amount" value="{{ old('installment_amount', $loanCategory->installment_amount ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Total Repayable</label>
        <input type="number" step="0.01" name="total_repayable_amount" value="{{ old('total_repayable_amount', $loanCategory->total_repayable_amount ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Max Term (Months)</label>
        <input type="number" name="max_term_months" value="{{ old('max_term_months', $loanCategory->max_term_months ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Max Term (Days)</label>
        <input type="number" name="max_term_days" value="{{ old('max_term_days', $loanCategory->max_term_days ?? '') }}" class="form-control">
    </div>

    <div class="col-md-3">
        <label class="form-label">Repayment Frequency</label>
        <select name="repayment_frequency" class="form-select">
            @foreach(['daily','weekly','bi_weekly','monthly','quarterly'] as $freq)
                <option value="{{ $freq }}" {{ old('repayment_frequency', $loanCategory->repayment_frequency ?? '') == $freq ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ', $freq)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <label class="form-label">Conditions</label>
        <textarea name="conditions" class="form-control" rows="3" required>{{ old('conditions', $loanCategory->conditions ?? '') }}</textarea>
    </div>

    @isset($loanCategory)
        <div class="col-md-3 form-check mt-3">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $loanCategory->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    @endisset
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">{{ $buttonText }}</button>
    <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary">Cancel</a>
</div>
