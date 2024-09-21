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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            font-size: 26px;
            margin: 0;
            color: #2c3e50;
        }
        .header p {
            font-size: 16px;
            margin: 5px 0;
            color: #7f8c8d;
        }
        .session-info {
            font-size: 14px;
            margin-top: 10px;
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        .table th {
            background-color:#777;
            color: #ffffff;
            font-weight: 700;
        }
        .table td {
            background-color: #ffffff;
            color: #333;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #e9ecef;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 8px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Emploi du temps de l'étudiant : {{ $student_name }}</h2>
            <div class="session-info">
                @php
                    $currentYear = now()->year;
                    $previousYear = $currentYear - 1;
                @endphp
                <p><strong>Année Universitaire:</strong> {{ $previousYear }}/{{ $currentYear }}</p>
                <p><strong>Session :</strong> 
                    @if($session_type == 'S_N_1') Automne, Normale
                    @elseif($session_type == 'S_N_2') Printemps, Normale
                    @elseif($session_type == 'S_R_1') Automne, Rattrapage
                    @elseif($session_type == 'S_R_2') Printemps, Rattrapage
                    @endif
                </p>
                <p><strong>Centre d'Examen :</strong> El Jadida</p>
            </div>
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
                                {{ $salle->name }}@if (!$loop->last), @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
