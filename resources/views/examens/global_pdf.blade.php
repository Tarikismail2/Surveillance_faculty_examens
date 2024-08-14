<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0;
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* En-tête pour la première page */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            padding: 10px 20px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 9999; /* Assurez-vous que l'en-tête est au-dessus du contenu */
            page-break-after: avoid; /* Éviter la coupure après l'en-tête */
        }

        .header img {
            max-height: 80px;
            display: block;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-align: center;
            flex-grow: 1;
        }

        /* Marge pour le contenu afin de ne pas chevaucher l'en-tête */
        .content {
            margin-top: 120px; /* Assure que le contenu ne chevauche pas l'en-tête */
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
            page-break-inside: avoid; /* Éviter la coupure à l'intérieur des tables */
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

        /* Styles pour les pages suivantes */
        @media print {
            .page-break {
                page-break-before: always;
            }
            .no-print {
                display: none; /* Masquer le contenu non imprimable */
            }
        }

        /* En-tête seulement sur la première page */
        .header-not-first {
            display: none;
        }
    </style>
</head>
<body>
    <!-- En-tête uniquement sur la première page -->
    {{-- <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/fslogo.png'))) }}" alt="Logo">
        <h1>Faculté des Sciences El Jadida</h1>
    </div> --}}

    <!-- Contenu principal -->
    <div class="content">
        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/fslogo.png'))) }}" alt="Logo">
            <h1>Faculté des Sciences El Jadida</h1>
        </div>
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
                            <th>Heure Début</th>
                            <th>Heure Fin</th>
                            <th>Filière</th>
                            <th>Module</th>
                            <th>Locaux</th>
                            <th>Responsable du module</th>
                            <th>Surveillants</th>
                            <th>Session</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $examen->date }}</td>
                            <td>{{ $examen->heure_debut }}</td>
                            <td>{{ $examen->heure_fin }}</td>
                            <td>{{ $examen->module->lib_elp }}</td>
                            <td>
                                @if ($examen->module)
                                    {{ $examen->module->version_etape }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($examen->additionalSalles && count($examen->additionalSalles) > 0)
                                    @foreach ($examen->additionalSalles as $additionalSalle)
                                        {{ $additionalSalle->name }}@if (!$loop->last), @endif
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($examen->enseignants && count($examen->enseignants) > 0)
                                    @foreach ($examen->enseignants as $enseignant)
                                        {{ $enseignant->name }}@if (!$loop->last), @endif
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ $examen->enseignant ? $examen->enseignant->name : 'N/A' }}</td>
                            <td>
                                {{ $examen->session ? $examen->session->type : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Ajouter une rupture de page après chaque ensemble de données si nécessaire -->
            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach

        <!-- En-tête seulement sur la première page -->
        <div class="header-not-first">
            <!-- En-tête uniquement sur la première page -->
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/fslogo.png'))) }}" alt="Logo">
            <h1>Faculté des Sciences El Jadida</h1>
        </div>
    </div>
</body>
</html>
