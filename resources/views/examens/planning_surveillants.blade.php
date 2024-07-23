<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning des Surveillants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #1a202c;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        th {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .surveillant-name {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: bold;
            color: #6a6d70;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 60px;
            max-width: 150px;
            object-fit: contain;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ url('images/fslogo.png') }}" alt="Logo">
        <h1>Planning des Surveillants</h1>
    </div>

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
                    <td class="surveillant-name">{{ $surveillantData['name'] }}</td>
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
