<!DOCTYPE html>
<html>
<head>
    <title>Activity History</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
    <h1>Activity History</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timeline as $event)
                <tr>
                    <td>{{ $event['date']->format('Y-m-d H:i') }}</td>
                    <td>{{ $event['type'] }}</td>
                    <td>{{ $event['title'] }}</td>
                    <td>{{ $event['description'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
