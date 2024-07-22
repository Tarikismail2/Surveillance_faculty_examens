<!DOCTYPE html>
<html>
<head>
    <style>
        /* Styles CSS pour le PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background-color: #1a202c;
            color: white;
            padding: 10px 20px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #4a5568;
        }
        .header img {
            max-height: 60px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-top: 120px; /* Ajuster pour dégager l'espace nécessaire sous l'en-tête */
            padding: 20px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2d3748;
            border-bottom: 2px solid #cbd5e0;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #edf2f7;
            color: #2d3748;
        }
        .session-info, .filiere-info {
            margin-bottom: 20px;
        }
        .session-info p, .filiere-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- <!-- Logo de l'université et de la faculté (exemple en base64) -->
        {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA... (votre code base64 ici) ..." alt="University Logo" style="float: left;">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA... (votre code base64 ici) ..." alt="Faculty Logo" style="float: right;"> --}}
        <h1>faculté des sciences el jadida</h1>
    </div>

    <div class="content">
        <h1>Planification Globale des Examens - {{ $session->type }}</h1>
        <div class="session-info">
            <div class="section-title">Détails de la Session :</div>
            <p><strong>Session :</strong> {{ $session->type }}</p>
            <p><strong>Date :</strong> {{ $session->date_debut }}</p>
        </div>

        @foreach ($exams as $examen)
            <div class="filiere-info">
                <div class="section-title">Filière : {{ $examen->module->version_etape }}</div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Durée début</th>
                            <th>Durée fin</th>
                            <th>Module</th>
                            <th>Locaux</th>
                            <th>Surveillants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $examen->date }}</td>
                            <td>{{ $examen->heure_debut }}</td>
                            <td>{{ $examen->heure_fin }}</td>
                            <td>{{ $examen->module->lib_elp }}</td>
                            <td>
                                @if ($examen->additionalSalles && count($examen->additionalSalles) > 0)
                                    @foreach ($examen->additionalSalles as $additionalSalle)
                                        {{ $additionalSalle->name }},
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($examen->enseignants && count($examen->enseignants) > 0)
                                    @foreach ($examen->enseignants as $enseignant)
                                        {{ $enseignant->name }},
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
