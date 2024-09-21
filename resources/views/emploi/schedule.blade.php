<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Emploi du Temps - Département</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .department-info {
            margin-bottom: 10px;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>UNIVERSITE CHOUAIB DOUKKALI</h1>
    <h1>FACULTE DES SCIENCES EL JADIDA</h1>
    <hr>
    <div class="department-info">
        @php
        $session = \App\Models\SessionExam::find($id_session); 
        $department = \App\Models\Department::find($id_department);
        $currentYear = $session ? \Carbon\Carbon::parse($session->date_debut)->year : now()->year;
        $previousYear = $currentYear - 1;
    @endphp
    
    <p><strong>Année Universitaire:</strong> {{ $previousYear }}/{{ $currentYear }}</p>
    <p><strong>Département :</strong> {{ $department ? $department->name : 'N/A' }}</p>
    @if ($session)
        @if ($session->type == 'S_N_1' || $session->type == 'S_N_2')
            <p><strong>Session :</strong> {{ $session->type == 'S_N_1' ? 'Automne' : 'Printemps' }} - Normale</p>
        @elseif($session->type == 'S_R_1' || $session->type == 'S_R_2')
            <p><strong>Session :</strong> {{ $session->type == 'S_R_1' ? 'Automne' : 'Printemps' }} - Rattrapage</p>
        @endif
    @endif
    <p><strong>Centre d'Examen :</strong> El Jadida</p>    
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Nom/Prénom</th>
                @foreach ($dates as $date)
                    <th colspan="2">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($dates as $date)
                    <th>MA</th>
                    <th>AM</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($enseignants as $enseignant)
                <tr>
                    <td>{{ $enseignant->name }}</td>
                    @foreach ($dates as $date)
                        @php
                            $morningEntry1 = $schedule
                                ->where('id_enseignant', $enseignant->id)
                                ->where('examen.date', $date)
                                ->where('examen.heure_debut', '08:30:00')
                                ->first();
                            $morningEntry2 = $schedule
                                ->where('id_enseignant', $enseignant->id)
                                ->where('examen.date', $date)
                                ->where('examen.heure_debut', '10:15:00')
                                ->first();

                            $afternoonEntry1 = $schedule
                                ->where('id_enseignant', $enseignant->id)
                                ->where('examen.date', $date)
                                ->where('examen.heure_debut', '14:30:00')
                                ->first();
                            $afternoonEntry2 = $schedule
                                ->where('id_enseignant', $enseignant->id)
                                ->where('examen.date', $date)
                                ->where('examen.heure_debut', '16:15:00')
                                ->first();

                            // Check if the teacher is a reservist
                            $isMorningReservist = $reservistes
                                ->where('id_enseignant', $enseignant->id)
                                ->where('date', $date)
                                ->where('demi_journee', 'matin')
                                ->first();
                            $isAfternoonReservist = $reservistes
                                ->where('id_enseignant', $enseignant->id)
                                ->where('date', $date)
                                ->where('demi_journee', 'apres-midi')
                                ->first();
                        @endphp

                        <!-- Matinée -->
                        <td>
                            @if ($morningEntry1)
                                {{ $morningEntry1->salle->name }}|
                            @endif
                            @if ($morningEntry2)
                                {{ $morningEntry2->salle->name }}
                            @endif
                            @if (!$morningEntry1 && !$morningEntry2 && $isMorningReservist)
                                R
                            @elseif (!$morningEntry1 && !$morningEntry2)
                                -
                            @endif
                        </td>

                        <!-- Après-midi -->
                        <td>
                            @if ($afternoonEntry1)
                                {{ $afternoonEntry1->salle->name }}|
                            @endif
                            @if ($afternoonEntry2)
                                {{ $afternoonEntry2->salle->name }}
                            @endif
                            @if (!$afternoonEntry1 && !$afternoonEntry2 && $isAfternoonReservist)
                                R
                            @elseif (!$afternoonEntry1 && !$afternoonEntry2)
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
