<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des étudiants et leurs examens</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            color: #2C3E50;
            text-align: center;
        }
        hr {
            border: 1px solid #ccc;
            margin: 10px 0;
        }
        .department-info {
            margin-bottom: 20px;
        }
        .department-info p {
            margin: 5px 0;
        }
        table {
            width: 100%; /* Assurez-vous que la table occupe toute la largeur de l'écran */
            max-width: 90%; /* Limitez la largeur pour la centrer */
            margin: 0 auto; /* Centre la table horizontalement */
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
            color: #333;
            font-weight: bold;
        }
        th, td {
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        td {
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .center-text {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>UNIVERSITE CHOUAIB DOUKKALI</h1>
    <h1>FACULTE DES SCIENCES EL JADIDA</h1>
    <hr>
    <h1>Liste des Etudiants</h1>
    <hr>
    <div class="department-info">
        @php
            $currentYear = $session ? \Carbon\Carbon::parse($session->date)->year : now()->year;
            $previousYear = $currentYear - 1;
        @endphp
        <p><strong>Année Universitaire:</strong> {{ $previousYear }}/{{ $currentYear }}</p>
        <p><strong>Session:</strong> {{ $session->type == 'S_N_1' || $session->type == 'S_N_2' ? 'Normale' : 'Rattrapage' }}</p>
        <p><strong>Centre d'Examen :</strong> El Jadida</p>
    </div>
    <h2>Filière: {{ $filiere->version_etape ?? 'Inconnu' }}</h2>

    <table>
        <thead>
            <tr>
                <th>CNE</th>
                <th>Nom de l'étudiant</th>
                @foreach($modules as $module)
                    <th>{{ $module->lib_elp }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->cne }}</td>
                    <td>{{ $student->nom }} {{ $student->prenom }}</td>
                    @foreach($modules as $module)
                        @php
                            $exam = $exams->where('id_module', $module->id)->first();
                            $salle = $exam ? $exam->sallesSupplementaires->pluck('name')->join(', ') : null;
                        @endphp
                        <td>
                            @if($salle)
                                S: {{ $salle }} / N°{{ $loop->parent->iteration }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
