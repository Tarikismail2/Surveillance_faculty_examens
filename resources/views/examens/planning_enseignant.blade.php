<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning des Surveillants</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            color: #333;
            background-color: #f4f4f9;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        h3 {
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #e9ecef;
            color: #495057;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .highlight {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Planning des Surveillants</h2>

    @foreach ($surveillantsAssignments as $surveillant)
        <h3>{{ $surveillant['name'] }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    @foreach ($creneauxHoraires as $creneau)
                        <th>{{ $creneau }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dates as $date)
                    <tr>
                        <td>{{ $date }}</td>
                        @foreach ($creneauxHoraires as $creneau)
                            <td>
                                {{ $surveillant['assignments'][$date][$creneau] ?? '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>

</html>
