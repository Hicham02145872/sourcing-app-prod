<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Shipping Label</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }
        .logo {
            max-width: 350px; /* Big Logo */
            height: auto;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            font-size: 16px;
        }
        td {
            border: 2px solid #000; /* Thicker borders like the image */
            padding: 15px;
            vertical-align: middle;
            text-align: left;
        }
        .label {
            width: 35%;
            font-weight: bold;
            background-color: #f9f9f9; /* Subtle gray background for labels */
            text-transform: uppercase;
            font-size: 14px;
        }
        .value {
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
        }
        .whatsapp {
            color: #25D366; /* WhatsApp Green */
            font-weight: bold;
            font-size: 18px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        @foreach($sourcingOrder->quotation->sourcingRequest->destinations as $index => $destination)
            <div style="page-break-after: always; margin-bottom: 50px;">
                <!-- Logo -->
                <img src="{{ public_path('images/logo.png') }}" class="logo">
                
                <!-- Main Info Table -->
                <table>
                    <tr>
                        <td class="label">Country</td>
                        <td class="value">{{ $destination->country->name ?? 'N/A' }}</td>
                    </tr>
            <tr>
                <td class="label">Seller Name</td>
                <td class="value">{{ $sourcingOrder->label_seller_name ?: $sourcingOrder->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Order ID</td>
                <td class="value">#{{ $sourcingOrder->display_id }}</td>
            </tr>
            <tr>
                <td class="label">Product Name</td>
                <td class="value">{{ $sourcingOrder->label_product_name ?: ($sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td class="label">Quantity</td>
                <td class="value">{{ $destination->quantity }}</td>
            </tr>
            <tr>
                <td class="label">Recipient Address</td>
                <td class="value" style="font-weight: normal;">
                    <div style="margin-bottom: 5px;">
                        <strong>{{ $destination->service->name ?? 'Service' }}:</strong> {{ $destination->label_address ?: ($destination->address ?? 'N/A') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div style="font-weight: bold; margin-bottom: 5px;">For support or questions</div>
            <div class="whatsapp" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <a href="https://wa.me/212646522071" target="_blank" style="text-decoration: none; color: #25D366; display: flex; align-items: center; gap: 5px;">
                    <span style="font-weight: bold;">Contact Us</span>
                    <span>+212 646-522071</span>
                </a>
            </div>
        </div>
        </div>
        @endforeach
    </div>
</body>
</html>
