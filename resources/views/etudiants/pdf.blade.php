<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des étudiants</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            padding: 5px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header img {
            height: 50px;
        }
        .header h2 {
            margin: 0;
            flex: 1;
            text-align: center;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: 16px;
        }
        .page-break {
            page-break-after: always;
        }
        .page-number {
            text-align: center;
            font-size: 14px;
            position: fixed;
            bottom: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>UNIVERSITE CHOUAIB DOUKKALI</h2>
            <h2>FACULTE DES SCIENCES EL JADIDA</h2>
        </div>
        @foreach ($exams as $examen)
    @foreach ($examen->modules as $module)
        <div class="container">
            <div class="header">
                <h3>Module: {{ $module->lib_elp }}</h3>
                <p>Responsable de module: {{ $examen->enseignant->name }}</p>
                <p>Salle(s): {{ implode(', ', $salles->pluck('name')->toArray()) }}</p>
                <p>Date: {{ $examen->date }}</p>
                <p>Heure d'examen: {{ $examen->heure_debut }} - {{ $examen->heure_fin }}</p>
            </div>

            @php
                
                $studentIndex = 0;
            @endphp

            @foreach ($salles as $salle)
         
                <div class="page-break">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="4">Salle: {{ $salle->name }}</th>
                            </tr>
                            <tr>
                                <th>Examen n°:</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < $salle->capacite && $studentIndex < $students->count(); $i++, $studentIndex++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $students[$studentIndex]->nom }}</td>
                                    <td>{{ $students[$studentIndex]->prenom }}</td>
                                    <td></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <div class="signature">
                        <p>Signature Enseignant:</p>
                    </div>
                </div>
            @endforeach

            @if (!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        </div>
    @endforeach
@endforeach

    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("DejaVu Sans, sans-serif", "normal");
                $size = 12;
                $pageText = "Page " . $PAGE_NUM . " / " . $PAGE_COUNT;
                $y = 15;
                $x = 520;
                $pdf->text($x, $y, $pageText, $font, $size);
            ');
        }
    </script>
</body>
</html>