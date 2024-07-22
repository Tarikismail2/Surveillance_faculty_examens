<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        header, footer {
            text-align: center;
            padding: 15px;
            background-color: #343a40;
            color: #fff;
        }
        header h1 {
            margin: 0;
            font-size: 1.5em;
        }
        header h3 {
            margin: 5px 0;
            font-size: 1.2em;
            color: #ced4da;
        }
        footer p {
            margin: 0;
            font-size: 0.9em;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .schedule-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .schedule-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .alert {
            padding: 15px;
            background-color: #dc3545;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Emploi du Temps des Examens</h1>
            <h3>Session : {{ $session_type }}</h3>
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
                    @foreach ($schedule as $item)
                        <tr>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->heure_debut }}</td>
                            <td>{{ $item->heure_fin }}</td>
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
