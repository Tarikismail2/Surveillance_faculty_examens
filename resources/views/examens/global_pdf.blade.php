<!DOCTYPE html>
<html>
<head>
    <style>
        /* Styles CSS pour le PDF */
        body {
            font-family: Arial, sans-serif;
            position: relative; /* Position relative pour le positionnement des logos */
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px; /* Hauteur de l'en-tête */
            background-color: #f2f2f2;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header img {
            max-height: 60px; /* Taille maximale des logos */
        }
        .session-info, .filiere-info, .exam-info {
            margin-top: 100px; /* Pour dégager l'espace nécessaire sous l'en-tête */
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
    <div>
        <!-- Logo de l'université en base64 -->
        {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA... (votre code base64 ici) ..." alt="University Logo" style="float: left;">
        <!-- Logo de la faculté en base64 -->
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA... (votre code base64 ici) ..." alt="Faculty Logo" style="float: right;"> --}}
        <h1>faculté des sciences el jadida</h1>
    </div>

    <h1>Planification Globale des Examens - {{ $session->type }}</h1>
    <div class="session-info">
        <span class="section-title">Détails de la Session :</span>
        <p><strong>Session :</strong> {{ $session->type }}</p>
        <p><strong>Date :</strong> {{ $session->date_debut }}</p>
    </div>

    @foreach ($exams as $examen)
        <div class="filiere-info">
            <span class="section-title">Filière : {{ $examen->module->version_etape }}</span>
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
</body>
</html>
