<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Verkopers</title>

    <!-- CSS van Laravel (app.css) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="container">
        <h1>Overzicht van alle verkopers</h1>

        <!-- Tabel met verkopersgegevens -->
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Speciale Status</th>
                    <th>Soort</th>
                    <th>Stand Type</th>
                    <th>Dagen</th>
                    <th>Logo</th>
                    <th>Opmerking</th>
                    <th>Aangemaakt</th>
                    <th>Gewijzigd</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop door alle verkopers -->
                @foreach($verkopers as $verkoper)
                    <tr>
                        <td data-label="Naam">{{ $verkoper->Naam }}</td>
                        <td data-label="Speciale Status">{{ $verkoper->SpecialeStatus ? 'Ja' : 'Nee' }}</td>
                        <td data-label="Soort">{{ $verkoper->VerkooptSoort }}</td>
                        <td data-label="Stand Type">{{ $verkoper->StandType }}</td>
                        <td data-label="Dagen">{{ $verkoper->Dagen }}</td>
                        <td data-label="Logo">

                            @if($verkoper->Logo)
                                <img src="{{ asset('storage/' . $verkoper->Logo) }}" alt="Logo">
                            @else
                                Geen
                            @endif
                        </td>
                        <td data-label="Opmerking">{{ $verkoper->Opmerking }}</td>
                        <td data-label="Aangemaakt">{{ $verkoper->DatumAangemaakt }}</td>
                        <td data-label="Gewijzigd">{{ $verkoper->DatumGewijzigd }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>