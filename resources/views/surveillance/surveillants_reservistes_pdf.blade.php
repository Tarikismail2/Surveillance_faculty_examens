<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Surveillants Réservistes - {{ ucfirst($demi_journee) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1, h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 30px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        @media print {
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <h1>Liste des Surveillants Réservistes ({{ ucfirst($demi_journee) }})</h1>
    <h2>Date: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} | Session: {{ $session->type ?? 'N/A' }}</h2>

    <table>
        <thead>
            <tr>
                <th>Nom Complet</th>
                <th>Département</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservistes as $reserviste)
                <tr>
                    <td>{{ $reserviste->enseignant->name }}</td>
                    <td>{{ $reserviste->enseignant->department->name ?? 'N/A' }}</td> <!-- Utilisation de la relation -->
                    <td>{{ $reserviste->enseignant->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Aucun surveillant trouvé pour cette date et cette demi-journée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
