<div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $loanCategory->name ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Amount Disbursed</label>
    <input type="number" step="0.01" name="amount_disbursed" class="form-control" value="{{ old('amount_disbursed', $loanCategory->amount_disbursed ?? '') }}">
</div>
<div class="col-md-6">
    <label class="form-label">Principal Due</label>
    <input type="number" step="0.01" name="principal_due" class="form-control" value="{{ old('principal_due', $loanCategory->principal_due ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Insurance Fee</label>
    <input type="number" step="0.01" name="insurance_fee" class="form-control" value="{{ old('insurance_fee', $loanCategory->insurance_fee ?? 0) }}">
</div>

<div class="col-md-4">
    <label class="form-label">Officer Visit Fee</label>
    <input type="number" step="0.01" name="officer_visit_fee" class="form-control" value="{{ old('officer_visit_fee', $loanCategory->officer_visit_fee ?? 0) }}">
</div>

<div class="col-md-4">
    <label class="form-label">Interest Rate (%)</label>
    <input type="number" step="0.01" name="interest_rate" class="form-control" value="{{ old('interest_rate', $loanCategory->interest_rate ?? 20) }}">
</div>

<div class="col-md-4">
    <label class="form-label">Repayment Frequency</label>
    <select name="repayment_frequency" class="form-select">
        @foreach(['daily','weekly','bi_weekly','monthly','quarterly'] as $f)
            <option value="{{ $f }}" {{ old('repayment_frequency', $loanCategory->repayment_frequency ?? '')==$f ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_',' ', $f)) }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Currency</label>
    <input type="text" name="currency" class="form-control" value="{{ old('currency', $loanCategory->currency ?? 'TZS') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Max Term (Days)</label>
    <input type="number" name="max_term_days" class="form-control" value="{{ old('max_term_days', $loanCategory->max_term_days ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Max Term (Months)</label>
    <input type="number" name="max_term_months" class="form-control" value="{{ old('max_term_months', $loanCategory->max_term_months ?? '') }}">
</div>

<div class="col-12">
    <label class="form-label">Conditions</label>
    <textarea name="conditions" rows="2" class="form-control">{{ old('conditions', $loanCategory->conditions ?? '') }}</textarea>
</div>

<div class="col-12">
    <label class="form-label">Descriptions</label>
    <textarea name="descriptions" rows="3" class="form-control">{{ old('descriptions', $loanCategory->descriptions ?? '') }}</textarea>
</div>

<div class="col-md-3 form-check mt-3">
    <input type="hidden" name="is_active" value="0">
    <input class="form-check-input" type="checkbox" value="1" name="is_active"
        {{ old('is_active', $loanCategory->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Active</label>
</div>

<div class="col-md-3 form-check mt-3">
    <input type="hidden" name="is_new_client" value="0">
    <input class="form-check-input" type="checkbox" value="1" name="is_new_client"
        {{ old('is_new_client', $loanCategory->is_new_client ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">For New Clients</label>
</div>

