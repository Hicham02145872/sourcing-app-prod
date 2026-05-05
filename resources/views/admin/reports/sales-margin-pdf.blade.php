<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Financial Report') }}</title>
    <style>
        @page {
            margin: 40px;
        }
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #334155; /* slate-700 */
            line-height: 1.5;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #0f172a; /* slate-900 */
            margin: 0;
        }
        
        /* Header */
        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #e2e8f0; /* slate-200 */
            padding-bottom: 20px;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
        }
        .header-meta {
            color: #64748b; /* slate-500 */
            font-size: 10px;
            margin-top: 5px;
        }
        .branding-bar {
            height: 4px;
            background-color: #ea580c; /* orange-600 */
            width: 100%;
            border-radius: 2px;
            margin-bottom: 20px;
        }

        /* Summary Cards */
        .summary-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-card {
            background-color: #f8fafc; /* slate-50 */
            border: 1px solid #e2e8f0; /* slate-200 */
            border-radius: 6px;
            padding: 15px;
            text-align: center;
        }
        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b; /* slate-500 */
            margin-bottom: 8px;
            font-weight: bold;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }
        .currency-row {
            margin-bottom: 4px;
        }
        .currency-row:last-child {
            margin-bottom: 0;
        }
        .text-emerald { color: #059669; } /* emerald-600 */
        .text-red { color: #dc2626; } /* red-600 */

        /* Data Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 20px;
        }
        table.data-table th {
            background-color: #f1f5f9; /* slate-100 */
            color: #475569; /* slate-600 */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.05em;
            padding: 10px 8px;
            border-bottom: 1px solid #cbd5e1; /* slate-300 */
            text-align: left;
        }
        table.data-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0; /* slate-200 */
            color: #334155;
            vertical-align: middle;
        }
        table.data-table tr:last-child td {
            border-bottom: none;
        }
        
        .id-badge {
            font-family: monospace;
            color: #ea580c; /* orange-600 */
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8; /* slate-400 */
        }
    </style>
</head>
<body>
    <div class="branding-bar"></div>
    
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <div class="header-title">{{ __('Sales & Margin Report') }}</div>
                    <div class="header-meta">
                        {{ __('Generated on') }} {{ now()->translatedFormat('d/m/Y') }} {{ __('at') }} {{ now()->translatedFormat('H:i') }}
                    </div>
                </td>
                <td align="right">
                    <div style="font-weight: bold; color: #0f172a;">{{ config('app.name') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary Section -->
    <table class="summary-container" cellspacing="10">
        <tr>
            <td width="33%" class="summary-card">
                <div class="summary-label">{{ __('Daily Profit') }}</div>
                @forelse($dailyTotalsByCurrency ?? [] as $currency => $total)
                    <div class="currency-row">
                        <span class="summary-value {{ $total >= 0 ? 'text-emerald' : 'text-red' }}">{{ number_format($total, 2) }}</span>
                        <span style="font-size: 10px; color: #94a3b8;">{{ $currency }}</span>
                    </div>
                @empty
                    <div class="currency-row"><span class="summary-value">—</span></div>
                @endforelse
            </td>
            <td width="33%" class="summary-card">
                <div class="summary-label">{{ __('Weekly Profit') }}</div>
                @forelse($weeklyTotalsByCurrency ?? [] as $currency => $total)
                    <div class="currency-row">
                        <span class="summary-value {{ $total >= 0 ? 'text-emerald' : 'text-red' }}">{{ number_format($total, 2) }}</span>
                        <span style="font-size: 10px; color: #94a3b8;">{{ $currency }}</span>
                    </div>
                @empty
                    <div class="currency-row"><span class="summary-value">—</span></div>
                @endforelse
            </td>
            <td width="33%" class="summary-card">
                <div class="summary-label">{{ __('Monthly Profit') }}</div>
                @forelse($monthlyTotalsByCurrency ?? [] as $currency => $total)
                    <div class="currency-row">
                        <span class="summary-value {{ $total >= 0 ? 'text-emerald' : 'text-red' }}">{{ number_format($total, 2) }}</span>
                        <span style="font-size: 10px; color: #94a3b8;">{{ $currency }}</span>
                    </div>
                @empty
                    <div class="currency-row"><span class="summary-value">—</span></div>
                @endforelse
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Client') }}</th>
                <th>{{ __('Product') }}</th>
                <th class="text-right">{{ __('Sale') }}</th>
                <th class="text-right">{{ __('Costs') }}</th>
                <th class="text-right">{{ __('Net Margin') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                @php
                    $totalCosts = ($order->product_cost_price ?? 0) + ($order->shipping_cost_real ?? 0) + ($order->rejection_loss_cost ?? 0);
                    $profit = $order->net_profit_or_loss;
                @endphp
                <tr>
                    <td><span class="id-badge">#{{ $order->id }}</span></td>
                    <td>
                        {{ $order->updated_at->translatedFormat('d/m/Y') }}
                        <div style="font-size: 9px; color: #94a3b8;">{{ $order->updated_at->translatedFormat('H:i') }}</div>
                    </td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>
                        {{ \Illuminate\Support\Str::limit($order->quotation->sourcingRequest->product_name ?? 'N/A', 25) }}
                    </td>
                    <td class="text-right" style="font-family: monospace;">
                        {{ number_format($order->total_amount, 2) }} <span style="font-size: 8px;">{{ $order->quotation->currency }}</span>
                    </td>
                    <td class="text-right" style="font-family: monospace; color: #64748b;">
                        {{ number_format($totalCosts, 2) }} <span style="font-size: 8px;">{{ $order->quotation->currency }}</span>
                    </td>
                    <td class="text-right font-bold {{ $profit >= 0 ? 'text-emerald' : 'text-red' }}" style="font-family: monospace;">
                        {{ number_format($profit, 2) }} <span style="font-size: 8px;">{{ $order->quotation->currency }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ config('app.name') }} &copy; {{ date('Y') }} - {{ __('Confidential document for internal use only') }}
    </div>
</body>
</html>
