<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps des examens</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            font-size: 24px;
            margin: 0;
            color: #2c3e50;
        }
        .header p {
            font-size: 16px;
            margin: 5px 0;
            color: #7f8c8d;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #8f9193;
            color: #000;
            font-weight: 700;
        }
        .table td {
            background-color: #fff;
            color: #333;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #e9ecef;
        }
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 8px;
            }
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
