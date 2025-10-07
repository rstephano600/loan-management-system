<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Client Type</label>
        <select name="client_type" class="form-select @error('client_type') is-invalid @enderror">
            <option value="">-- Select Type --</option>
            <option value="individual" {{ old('client_type', $client->client_type ?? '') == 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="business" {{ old('client_type', $client->client_type ?? '') == 'business' ? 'selected' : '' }}>Business</option>
        </select>
        @error('client_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
               value="{{ old('first_name', $client->first_name ?? '') }}">
        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
               value="{{ old('last_name', $client->last_name ?? '') }}">
        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $client->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $client->phone ?? '') }}">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="">-- Select Status --</option>
            <option value="active" {{ old('status', $client->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $client->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="blacklisted" {{ old('status', $client->status ?? '') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Country</label>
        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
               value="{{ old('country', $client->country ?? '') }}">
        @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
