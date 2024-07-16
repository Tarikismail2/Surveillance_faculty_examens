<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        header, footer {
            text-align: center;
            padding: 10px;
            background-color: #f4f4f4;
        }
        header h1, footer p {
            margin: 0;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .schedule-table th {
            background-color: #f4f4f4;
        }
        .schedule-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .alert {
            padding: 15px;
            background-color: #f44336;
            color: white;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Emploi du Temps de Surveillance de {{$name_enseignant}}</h1>
            <h3>Session : {{$session_type}}</h3>
        </header>

        <main>
            @if (session('error'))
                <div class="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure de début</th>
                        <th>Heure de fin</th>
                        <th>Salle</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($schedule))
                    @foreach ($schedule->sortBy(['examen.date', 'examen.heure_debut']) as $item)
                        <tr>
                            <td>{{ $item->examen->date }}</td>
                            <td>{{ $item->examen->heure_debut }}</td>
                            <td>{{ $item->examen->heure_fin }}</td>
                            <td>{{ $item->salle->name }}</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </main>

        <footer>
            <p>Faculté des Sciences el Jadida - Université Chouaib Dokkali</p>
        </footer>
    </div>
</body>
</html>
