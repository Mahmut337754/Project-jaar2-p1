<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Verkopers</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Overzicht van alle verkopers</h1>

        <table class="table-auto border-collapse border border-gray-400 w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-400 px-4 py-2">Naam</th>
                    <th class="border border-gray-400 px-4 py-2">Speciale Status</th>
                    <th class="border border-gray-400 px-4 py-2">Soort</th>
                    <th class="border border-gray-400 px-4 py-2">Stand Type</th>
                    <th class="border border-gray-400 px-4 py-2">Dagen</th>
                    <th class="border border-gray-400 px-4 py-2">Logo</th>
                    <th class="border border-gray-400 px-4 py-2">Opmerking</th>
                    <th class="border border-gray-400 px-4 py-2">Aangemaakt</th>
                    <th class="border border-gray-400 px-4 py-2">Gewijzigd</th>
                </tr>
            </thead>
            <tbody>
                @foreach($verkopers as $verkoper)
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->Naam }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->SpecialeStatus ? 'Ja' : 'Nee' }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->VerkooptSoort }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->StandType }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->Dagen }}</td>
                        <td class="border border-gray-400 px-4 py-2">
                            @if($verkoper->Logo)
                                <img src="{{ asset('storage/' . $verkoper->Logo) }}" alt="Logo" class="h-12">
                            @else
                                Geen
                            @endif
                        </td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->Opmerking }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->DatumAangemaakt }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $verkoper->DatumGewijzigd }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
