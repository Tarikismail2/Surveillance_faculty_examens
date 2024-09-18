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
        @php
            $currentYear = $session ? \Carbon\Carbon::parse($session->date)->year : now()->year;
            $previousYear = $currentYear - 1;
        @endphp
        <p><strong>Année Universitaire:</strong> {{ $previousYear }}/{{ $currentYear }}</p>
        @if ($session->type == 'S_N_1' || $session->type == 'S_N_2')
            @if ($session->type == 'S_N_1')
                <p><strong>Semestres:</strong> Automne</p>
            @else
                <p><strong>Session:</strong> Printemps</p>
            @endif
            <p><strong>Session :</strong> Normale</p>
        @elseif($session->type == 'S_R_1' || $session->type == 'S_R_2')
            @if ($session->type == 'S_R_1')
                <p><strong>Session:</strong> Automne</p>
            @else
                <p><strong>Session:</strong> Printemps</p>
            @endif
            <p><strong>Session :</strong> Rattrapage</p>
        @endif
        <p><strong>Centre d'Examen :</strong> El Jadida</p>
    </div>

    @foreach ($groupedExams as $date => $examsByDate)
    @if(isset($examsByDate['morning']) && !empty($examsByDate['morning']))
        <h3>Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>

        <!-- Séance du matin -->
        <h4>1ère et 2ème Séance - Matin</h4>
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Salle 1</th>
                    <th>Surveillants 1</th>
                    <th>Salle 2</th>
                    <th>Surveillants 2</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examsByDate['morning'] as $exams)
                    <tr>
                        <td>
                            @foreach ($exams as $examen)
                                @foreach ($examen->modules as $module)
                                    {{ $module->lib_elp ?? 'N/A' }} <br>
                                    <strong>Responsable:</strong> {{ $examen->enseignant->name }} <br><br>
                                @endforeach
                            @endforeach
                        </td>

                        <!-- Salle 1 et Surveillants 1 -->
                        <td>
                            @if (isset($exams[0]))
                                @foreach ($exams[0]->salles as $salle)
                                    {{ $salle->name }}
                                @endforeach
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            @if (isset($exams[0]))
                                @foreach ($exams[0]->surveillants as $surveillant)
                                    {{ $surveillant->enseignant->name ?? 'N/A' }} <br>
                                @endforeach
                            @else
                                --
                            @endif
                        </td>

                        <!-- Salle 2 et Surveillants 2 -->
                        <td>
                            @if (isset($exams[1]))
                                @foreach ($exams[1]->salles as $salle)
                                    {{ $salle->name }}
                                @endforeach
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            @if (isset($exams[1]))
                                @foreach ($exams[1]->surveillants as $surveillant)
                                    {{ $surveillant->enseignant->name ?? 'N/A' }} <br>
                                @endforeach
                            @else
                                --
                            @endif
                        </td>

                        <td>--</td> <!-- Observations -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Surveillants réservistes du matin -->
        <div class="surveillant-section">
            <h4>Surveillants Réservistes - Matin</h4>
            <ul>
                @if (isset($reservists[$date]) && !empty($reservists[$date]->where('demi_journee', 'matin')))
                    @foreach ($reservists[$date]->where('demi_journee', 'matin') as $reservist)
                        <li>{{ $reservist->enseignant->name }}</li>
                    @endforeach
                @else
                    <li>Aucun surveillant réserviste disponible pour le matin.</li>
                @endif
            </ul>
        </div>
    @endif

    <!-- Séance de l'après-midi -->
    @if(isset($examsByDate['afternoon']) && !empty($examsByDate['afternoon']))
        <h4>1ère et 2ème Séance - Après-midi</h4>
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Salle 1</th>
                    <th>Surveillants 1</th>
                    <th>Salle 2</th>
                    <th>Surveillants 2</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examsByDate['afternoon'] as $exams)
                    <tr>
                        <td>
                            @foreach ($exams as $examen)
                                @foreach ($examen->modules as $module)
                                    {{ $module->lib_elp ?? 'N/A' }} <br>
                                    <strong>Responsable:</strong> {{ $examen->enseignant->name }} <br><br>
                                @endforeach
                            @endforeach
                        </td>

                        <!-- Salle 1 et Surveillants 1 -->
                        <td>
                            @if (isset($exams[0]))
                                @foreach ($exams[0]->salles as $salle)
                                    {{ $salle->name }}
                                @endforeach
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            @if (isset($exams[0]))
                                @foreach ($exams[0]->surveillants as $surveillant)
                                    {{ $surveillant->enseignant->name ?? 'N/A' }} <br>
                                @endforeach
                            @else
                                --
                            @endif
                        </td>

                        <!-- Salle 2 et Surveillants 2 -->
                        <td>
                            @if (isset($exams[1]))
                                @foreach ($exams[1]->salles as $salle)
                                    {{ $salle->name }}
                                @endforeach
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            @if (isset($exams[1]))
                                @foreach ($exams[1]->surveillants as $surveillant)
                                    {{ $surveillant->enseignant->name ?? 'N/A' }} <br>
                                @endforeach
                            @else
                                --
                            @endif
                        </td>

                        <td>--</td> <!-- Observations -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Surveillants réservistes de l'après-midi -->
        <div class="surveillant-section">
            <h4>Surveillants Réservistes - Après-midi</h4>
            <ul>
                @if (isset($reservists[$date]) && !empty($reservists[$date]->where('demi_journee', 'apres-midi')))
                    @foreach ($reservists[$date]->where('demi_journee', 'apres-midi') as $reservist)
                        <li>{{ $reservist->enseignant->name }}</li>
                    @endforeach
                @else
                    <li>Aucun surveillant réserviste disponible pour l'après-midi.</li>
                @endif
            </ul>
        </div>
    @endif
@endforeach
</body>
</html>
