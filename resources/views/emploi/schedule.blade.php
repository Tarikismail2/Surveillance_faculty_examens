<!DOCTYPE html>
<html>
<head>
    <title>Emploi du Temps</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Emploi du Temps</h1>
    <p>Département: {{ \App\Models\Department::find($idDepartment)->name }}</p>
    <p>Session: {{ \App\Models\SessionExam::find($idSession)->type }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Salle</th>
                <th>Enseignant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedule as $entry)
                <tr>
                    <td>{{ $entry->examen->date->format('d/m/Y') }}</td>
                    <td>{{ $entry->examen->heure_debut->format('H:i') }}</td>
                    <td>{{ $entry->examen->heure_fin->format('H:i') }}</td>
                    <td>{{ $entry->salle->name }}</td>
                    <td>{{ $entry->enseignant->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
