<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning des Surveillants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .surveillant-name {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Planning des Surveillants</h1>
    <table>
        <thead>
            <tr>
                <th class="surveillant-name">Surveillants</th>
                @foreach ($dates as $date)
                    <th colspan="{{ count($creneauxHoraires) }}">{{ $date }}</th>
                @endforeach
            </tr>
            <tr>
                <th></th>
                @foreach ($dates as $date)
                    @foreach ($creneauxHoraires as $creneau)
                        <th>{{ $creneau }}</th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($surveillantsAssignments as $surveillantId => $surveillantData)
                <tr>
                    <td>{{ $surveillantData['name'] }}</td>
                    @foreach ($dates as $date)
                        @foreach ($creneauxHoraires as $creneau)
                            <td>
                                {{ $surveillantData['assignments'][$date][$creneau] ?? '-' }}
                            </td>
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
