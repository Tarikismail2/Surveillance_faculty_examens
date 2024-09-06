<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2 class="title">Planning des surveillances - {{ $session->type }}</h2>
    <p><strong>Date de début:</strong> {{ $session->date_debut }}</p>
    <p><strong>Date de fin:</strong> {{ $session->date_fin }}</p>

    <table>
        <thead>
            <tr>
                <th>Module</th>
                <th>Filière</th>
                <th>Surveillants</th>
                <th>Locaux (Salles)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($examens as $examen)
                <tr>
                    <td>{{ $examen->module->lib_elp }}</td>
                    <td>{{ $examen->module->filiere->code_etape }}</td>
                    <td>
                        @foreach($examen->surveillants as $surveillant)
                            {{ $surveillant->nom }} {{ $surveillant->prenom }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($examen->salles as $salle)
                            {{ $salle->nom }}<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
