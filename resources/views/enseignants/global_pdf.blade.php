<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning des Examens</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .session-info {
            margin-bottom: 20px;
        }
        .surveillant-section {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h2 class="header">Faculté des Sciences - El Jadida</h2>

    <div class="session-info">
        <p><strong>Date de début de la session :</strong> {{ $session->date_debut }}</p>
        <p><strong>Session :</strong> {{ $session->type }}</p>
    </div>

    @foreach($groupedExams as $date => $examsByDate)
        <h3>Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>

        <!-- Séance du matin -->
        <h4>1ère et 2ème Séance - Matin</h4>
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Salle</th>
                    <th>Surveillants</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($examsByDate['morning'] as $salle => $exams)
                    <tr>
                        <td>
                            @foreach($exams as $examen)
                                {{ $examen->module->lib_elp }} <br>
                                Responsable: {{ $examen->enseignant->name }} <br><br>
                            @endforeach
                        </td>
                        <td>{{ $salle }}</td>
                        <td>
                            @foreach($exams as $examen)
                                @foreach($examen->surveillants as $surveillant)
                                    {{ $surveillant->name }} <br>
                                @endforeach
                            @endforeach
                        </td>
                        <td>--</td> <!-- Observations -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Séance de l'après-midi -->
        <h4>1ère et 2ème Séance - Après-midi</h4>
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Salle</th>
                    <th>Surveillants</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($examsByDate['afternoon'] as $salle => $exams)
                    <tr>
                        <td>
                            @foreach($exams as $examen)
                                {{ $examen->module->lib_elp }} <br>
                                Responsable: {{ $examen->enseignant->name }} <br><br>
                            @endforeach
                        </td>
                        <td>{{ $salle }}</td>
                        <td>
                            @foreach($exams as $examen)
                                @foreach($examen->surveillants as $surveillant)
                                    {{ $surveillant->name }} <br>
                                @endforeach
                            @endforeach
                        </td>
                        <td>--</td> <!-- Observations -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Surveillants réservistes -->
        <div class="surveillant-section">
            <h4>Surveillants Réservistes</h4>
            <ul>
                @foreach($reservists[$date] as $reservist)
                    <li>{{ $reservist->enseignant->name }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach
</body>
</html>
