<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Verkoper - Verkopers Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo-preview {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h1>Nieuwe Verkoper Toevoegen</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('sellers.index') }}" class="btn btn-outline-secondary">Terug naar Overzicht</a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('sellers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Verkoper Naam *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="special_status" 
                                               name="special_status" 
                                               value="1"
                                               {{ old('special_status') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="special_status">
                                            Speciale Status (Partner)
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="selling_type" class="form-label">Verkoop Type *</label>
                                    <select class="form-select @error('selling_type') is-invalid @enderror" 
                                            id="selling_type" 
                                            name="selling_type" 
                                            required>
                                        <option value="">Selecteer Verkoop Type</option>
                                        @foreach(\App\Models\Seller::SELLING_TYPES as $key => $value)
                                            <option value="{{ $key }}" 
                                                    {{ old('selling_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selling_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="booth_type" class="form-label">Stand Type *</label>
                                    <select class="form-select @error('booth_type') is-invalid @enderror" 
                                            id="booth_type" 
                                            name="booth_type" 
                                            required>
                                        <option value="">Selecteer Stand Type</option>
                                        @foreach(\App\Models\Seller::BOOTH_TYPES as $key => $value)
                                            <option value="{{ $key }}" 
                                                    {{ old('booth_type') == $key ? 'selected' : '' }}>
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
                                <div class="mb-3">
                                    <label for="days" class="form-label">Dagen *</label>
                                    <select class="form-select @error('days') is-invalid @enderror" 
                                            id="days" 
                                            name="days" 
                                            required>
                                        <option value="">Selecteer Aantal Dagen</option>
                                        @foreach(\App\Models\Seller::DAYS_OPTIONS as $key => $value)
                                            <option value="{{ $key }}" 
                                                    {{ old('days') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

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
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Actief
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notities</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Verkoper Aanmaken</button>
                            <a href="{{ route('sellers.index') }}" class="btn btn-outline-secondary">Annuleren</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>