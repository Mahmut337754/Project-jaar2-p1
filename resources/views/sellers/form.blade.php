<div class="row">
    <div class="col-md-6">
        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Seller Name *</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $seller->name ?? '') }}" 
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Special Status -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="special_status" 
                       name="special_status" 
                       value="1"
                       {{ old('special_status', $seller->special_status ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="special_status">
                    Special Status (Partner)
                </label>
            </div>
        </div>

        <!-- Selling Type -->
        <div class="mb-3">
            <label for="selling_type" class="form-label">Selling Type *</label>
            <select class="form-select @error('selling_type') is-invalid @enderror" 
                    id="selling_type" 
                    name="selling_type" 
                    required>
                <option value="">Select Selling Type</option>
                @foreach(\App\Models\Seller::SELLING_TYPES as $key => $value)
                    <option value="{{ $key }}" 
                            {{ old('selling_type', $seller->selling_type ?? '') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('selling_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Booth Type -->
        <div class="mb-3">
            <label for="booth_type" class="form-label">Booth Type *</label>
            <select class="form-select @error('booth_type') is-invalid @enderror" 
                    id="booth_type" 
                    name="booth_type" 
                    required>
                <option value="">Select Booth Type</option>
                @foreach(\App\Models\Seller::BOOTH_TYPES as $key => $value)
                    <option value="{{ $key }}" 
                            {{ old('booth_type', $seller->booth_type ?? '') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('booth_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <!-- Days -->
        <div class="mb-3">
            <label for="days" class="form-label">Days *</label>
            <select class="form-select @error('days') is-invalid @enderror" 
                    id="days" 
                    name="days" 
                    required>
                <option value="">Select Days</option>
                @foreach(\App\Models\Seller::DAYS_OPTIONS as $key => $value)
                    <option value="{{ $key }}" 
                            {{ old('days', $seller->days ?? '') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('days')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Logo -->
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" 
                   class="form-control @error('logo') is-invalid @enderror" 
                   id="logo" 
                   name="logo" 
                   accept="image/*">
            @error('logo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            @if(isset($seller) && $seller->logo)
                <div class="mt-2">
                    <p>Current Logo:</p>
                    <img src="{{ asset('storage/' . $seller->logo) }}" 
                         alt="Current logo" 
                         class="logo-preview img-thumbnail">
                </div>
            @endif
        </div>

        <!-- Is Active -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       {{ old('is_active', $seller->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Active
                </label>
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" 
                      id="notes" 
                      name="notes" 
                      rows="3">{{ old('notes', $seller->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>