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
        
        <!-- Logo -->
        <img src="{{ public_path('images/logo.png') }}" class="logo">

        <!-- Main Info Table -->
        <table>
            <tr>
                <td class="label">Country</td>
                <td class="value">{{ $sourcingOrder->quotation->sourcingRequest->destinations->first()->country->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Seller Name</td>
                <td class="value">{{ config('app.name', 'Fast Sourcing Brothers') }}</td>
            </tr>
            <tr>
                <td class="label">Order ID</td>
                <td class="value">#{{ $sourcingOrder->display_id }}</td>
            </tr>
            <tr>
                <td class="label">Product Name</td>
                <td class="value">{{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Quantity</td>
                <td class="value">{{ $sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity') }}</td>
            </tr>
            <tr>
                <td class="label">Recipient Address</td>
                <td class="value" style="font-weight: normal;">
                    <strong>{{ $sourcingOrder->user->name }}</strong><br>
                    {{ $sourcingOrder->user->phone ?? '' }}<br>
                    <br>
                    {{ $sourcingOrder->quotation->sourcingRequest->address ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div style="font-weight: bold; margin-bottom: 5px;">For support or questions</div>
            <div class="whatsapp" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAAAumlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjarVNLUFPRFHu/T9q8Fy0KhCR0P9o8L3lAgBAIYQIEBAgYlE+lRX2P8hA+597f+5G2ozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOjozOW+K0fAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAEZ0FNQQAAsY8L/GEFAAAACXBIWXMAAA7EAAAOxAGVKw4bAAADgVBMVEUAAAAiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIhG2v3vAAAA2HRSTlMABQMPEhQcHyIjJCguMDIzNDU2OTs8Pj9AQUNERUZHSElKTE1PUFJTVFVWV1hZWltcXV5fYGFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6e3x9fn+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+b66b+wAAAndJREFUSMft1ddTE0EYxvH9W3qR3oQAoYQSEkIChKZ0AQWkF+m9d9F7L6L0IqL03nsXvffO4yQMEAgYIILPO7t3e/bt2313l9ndLhRKtUaL1W63O5xOp8vl9Xn9AYfT6bDb7VarcF+tVqFzB4JBi8/j9QcCoTD/w0uX/D6rx+0OhiL8n9dCIXfA7bF5AmH+ZzQaizuczqA/EonG4vF4IpFM8j+TyWQy6A/4HU6XNxZLpTOfMmDf7KwF8KVyJBaPe1xOZyCVyWazH7MLH/K5xU+fcgvZfCqD+eNwuwPpbC63vLKSyy4t5fPfdnZ3d/f+7Oexm5v8Ui6TzQcS8US+sLK6tra6upJfXdvY+La5ubm19X1rc3NjfW11ZSGfSCYTqfzy+sbGxsbm5ubW9729vX+5/2x//8f21ubmxvrKQiKRTG1ufd/Z2dnd29vfPzg4PDw6+nN8fHp6dnp8fHR4cLC/t7uzvbW5kUim0rv7BweHh0cnJ6dnZ+cXF5eXl1dX19fX15eXVxeX52dnpyfHR4eH+/v7u5lUOpPfPzo6Pjk9Ozu/vLy6vrm5vb27u7u/u725vrq8vDw/Oz05OTr6/T2TyeX3j09Oz88vLq+ub25v7+4fHh4enx4fH+4e7m5vbq6vLi/Oz05Pjo++5/OFRGZ//+j45PTs/Pz84vLq6ur6+vrm5ubm+vry6vLi/Pzs9Pj46GB/by+TSCQS2d2Dw8Ojk5OT07Pzi/8F/H9eX11eXpyfnp4cHx4c7O/u7mQSiXh8YeX75sbG5tbW9929/f2Dw8Ojo+PTr9nP47PTo8Ovp897+/vfd7a3Njc2VpcX84l4bMHqcnt8gVAknc3ll5b+AH1gS8zIpUJpAAAAAElFTkSuQmCC" alt="WhatsApp" width="24" height="24">
                <span class="text-gray-600 font-bold">+212 646-522071</span>
            </div>
        </div>

    </div>
</body>
</html>
