<!-- resources/views/examens/planning_surveillant.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Surveillant</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Planning Surveillant</h2>

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
                            <td>{{ $surveillant['assignments'][$date][$creneau] ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
