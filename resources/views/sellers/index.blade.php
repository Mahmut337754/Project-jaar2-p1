<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verkopers Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo-preview { max-width: 100px; max-height: 100px; }
        .table-actions { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h1>Verkopers Beheer</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('sellers.create') }}" class="btn btn-primary">Nieuwe Verkoper Toevoegen</a>
                </div>
            </div>

            <!-- success / error berichten -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Naam</th>
                                    <th>Special Status</th>
                                    <th>Verkoop Type</th>
                                    <th>Stand Type</th>
                                    <th>Dagen</th>
                                    <th>Actief</th>
                                    <th>Notities</th>
                                    <th>Aangemaakt</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sellers as $seller)
                                    <tr>
                                        <td>{{ $seller->id }}</td>
                                        <td>
                                            @if($seller->logo)
                                                <img src="{{ asset('storage/' . $seller->logo) }}" 
                                                     alt="Logo" 
                                                     class="logo-preview img-thumbnail">
                                            @else
                                                <span class="text-muted">Geen logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $seller->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $seller->special_status ? 'success' : 'secondary' }}">
                                                {{ $seller->special_status ? 'Partner' : 'Standaard' }}
                                            </span>
                                        </td>
                                        <td>{{ \App\Models\Seller::SELLING_TYPES[$seller->selling_type] ?? $seller->selling_type }}</td>
                                        <td>{{ \App\Models\Seller::BOOTH_TYPES[$seller->booth_type] ?? $seller->booth_type }}</td>
                                        <td>{{ \App\Models\Seller::DAYS_OPTIONS[$seller->days] ?? $seller->days }}</td>
                                        <td>
                                            <span class="badge bg-{{ $seller->is_active ? 'success' : 'danger' }}">
                                                {{ $seller->is_active ? 'Ja' : 'Nee' }}
                                            </span>
                                        </td>
                                        <td title="{{ $seller->notes }}">
                                            {{ Str::limit($seller->notes, 30) }}
                                        </td>
                                        <td>{{ $seller->created_at->format('d-m-Y') }}</td>
                                        <td class="table-actions">
                                            <a href="{{ route('sellers.edit', $seller) }}" 
                                               class="btn btn-sm btn-outline-primary">Bewerken</a>
                                            
                                            <form action="{{ route('sellers.destroy', $seller) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirmDelete('Weet je zeker dat je {{ $seller->name }} wilt verwijderen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Verwijderen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">Geen verkopers gevonden.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(message) { return confirm(message); } // confirm voor verwijderen

        // automatisch sluiten van alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
