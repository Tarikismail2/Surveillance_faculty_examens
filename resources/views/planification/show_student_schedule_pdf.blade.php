<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps des examens</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Emploi du temps des examens</h2>
            <p>Session : {{ $session_type }}</p>
            <p>Étudiant : {{ $student_name }}</p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure de début</th>
                    <th>Heure de fin</th>
                    <th>Salle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedule as $examen)
                    <tr>
                        <td>{{ $examen->date }}</td>
                        <td>{{ $examen->heure_debut }}</td>
                        <td>{{ $examen->heure_fin }}</td>
                        <td>
                            @foreach ($examen->salles as $salle)
                                {{ $salle->nom }}@if (!$loop->last), @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
