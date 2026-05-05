<!DOCTYPE html>
<html>
<head>
    <title>Sourcing Orders</title>
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
    <h1>Sourcing Orders</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sourcingOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->quotation->sourcingRequest->product_name }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ number_format($order->total_amount, 2) }} {{ $order->quotation->currency }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
