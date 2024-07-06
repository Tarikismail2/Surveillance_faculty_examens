<!-- resources/views/examens/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des Examens</title>
    <style>
        /* Styles CSS pour le PDF */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Calendrier des Examens - {{ $enseignant->name }}</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure de Début</th>
                <th>Heure de Fin</th>
                <th>Module</th>
                <th>Salle</th>
            </tr>
        </thead>
        <tbody>
            @foreach($examens as $examen)
                <tr>
                    <td>{{ $examen->date }}</td>
                    <td>{{ $examen->heure_debut }}</td>
                    <td>{{ $examen->heure_fin }}</td>
                    <td>{{ $examen->module->name }}</td>
                    <td>{{ $examen->salle->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
