<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pro-forma Invoice - Order #{{ $sourcingOrder->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { font-size: 24px; margin: 0; }
        .header p { margin: 5px 0; }
        .content { margin-top: 30px; }
        .billed-to { margin-top: 30px; }
        .billed-to p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; font-size: 16px; }
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pro-forma Invoice</h1>
            <p><strong>Order ID:</strong> #{{ $sourcingOrder->id }}</p>
            <p><strong>Date:</strong> {{ $sourcingOrder->created_at->format('d M Y') }}</p>
        </div>

        <div class="billed-to">
            <h4>Billed To:</h4>
            <p>{{ $sourcingOrder->user->name }}</p>
            <p>{{ $sourcingOrder->user->email }}</p>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</td>
                        <td>{{ $sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity') }}</td>
                        <td>{{ number_format($sourcingOrder->quotation->unit_price, 2) }} {{ $sourcingOrder->quotation->currency }}</td>
                        <td>{{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; text-align: right;">
            <p><strong>Subtotal:</strong> {{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</p>
            <h3 class="total">Total: {{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</h3>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
