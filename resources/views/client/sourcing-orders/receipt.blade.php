<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice - Order #') }}{{ $sourcingOrder->id }}</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: white;
            }
        }
        
        .enterprise-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
        }
        
        .accent-line {
            height: 4px;
            background: linear-gradient(90deg, #3730a3 0%, #6366f1 100%);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="max-w-4xl mx-auto my-12 bg-white shadow-2xl">
        <!-- Header Section -->
        <div class="enterprise-header text-white p-10">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-5xl font-bold mb-2">{{ __('INVOICE') }}</div>
                    <div class="text-blue-200 text-sm font-medium tracking-wider">{{ __('COMMERCIAL INVOICE') }}</div>
                </div>
                <div class="text-right">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm px-6 py-4 rounded-lg border border-white border-opacity-30">
                        <div class="text-xs text-blue-200 mb-1 uppercase tracking-wide">{{ __('Invoice Number') }}</div>
                        <div class="text-2xl font-bold">#{{ $sourcingOrder->id }}</div>
                        <div class="text-xs text-blue-200 mt-3 uppercase tracking-wide">{{ __('Date Issued') }}</div>
                        <div class="text-sm font-medium">{{ now()->format('F d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accent-line"></div>

        <!-- Company & Client Information -->
        <div class="p-10">
            <div class="grid grid-cols-2 gap-12 mb-12">
                <!-- Supplier Info -->
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b-2 border-indigo-600">
                        {{ __('Supplier Information') }}
                    </div>
                    <div class="space-y-1">
                        <div class="text-xl font-bold text-gray-900">{{ __('Your Company Name') }}</div>
                        <div class="text-sm text-gray-600">{{ __('123 Business Avenue, Suite 100') }}</div>
                        <div class="text-sm text-gray-600">{{ __('New York, NY 10001') }}</div>
                        <div class="text-sm text-gray-600 mt-3">
                            <span class="font-semibold">{{ __('Email:') }}</span> contact@yourcompany.com
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">{{ __('Phone:') }}</span> +1 (555) 123-4567
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">{{ __('Tax ID:') }}</span> XX-XXXXXXX
                        </div>
                    </div>
                </div>

                <!-- Client Info -->
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b-2 border-indigo-600">
                        {{ __('Bill To') }}
                    </div>
                    <div class="space-y-1">
                        <div class="text-xl font-bold text-gray-900">{{ $sourcingOrder->user->name }}</div>
                        <div class="text-sm text-gray-600 mt-3">
                            <span class="font-semibold">{{ __('Email:') }}</span> {{ $sourcingOrder->user->email }}
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">{{ __('Customer ID:') }}</span> {{ $sourcingOrder->user->id }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b-2 border-indigo-600">
                    {{ __('Invoice Details') }}
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-300">
                            <th class="text-left p-4 text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Description') }}</th>
                            <th class="text-center p-4 text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Quantity') }}</th>
                            <th class="text-right p-4 text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200">
                            <td class="p-4">
                                <div class="font-semibold text-gray-900">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ __('Order Reference:') }} #{{ $sourcingOrder->id }}</div>
                            </td>
                            <td class="p-4 text-center text-gray-700 font-medium">
                                {{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }}
                            </td>
                            <td class="p-4 text-right font-semibold text-gray-900">
                                {{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="flex justify-end mb-8">
                <div class="w-80">
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-gray-600 font-medium">{{ __('Subtotal') }}</span>
                            <span class="text-gray-900 font-semibold">{{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm border-t border-gray-200">
                            <span class="text-gray-600 font-medium">{{ __('Tax (0%)') }}</span>
                            <span class="text-gray-900 font-semibold">0.00 {{ $sourcingOrder->quotation->currency }}</span>
                        </div>
                        <div class="flex justify-between py-4 text-lg font-bold border-t-2 border-gray-800 bg-gray-50 px-4 -mx-4">
                            <span class="text-gray-900">{{ __('TOTAL DUE') }}</span>
                            <span class="text-indigo-700">{{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Terms & Notes -->
            <div class="border-t-2 border-gray-200 pt-8 grid grid-cols-2 gap-8">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ __('Payment Terms') }}</div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p>• {{ __('Payment due within 30 days') }}</p>
                        <p>• {{ __('Late payments subject to 1.5% monthly fee') }}</p>
                        <p>• {{ __('Make checks payable to: Your Company Name') }}</p>
                    </div>
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ __('Notes') }}</div>
                    <div class="text-sm text-gray-600">
                        {{ __('Thank you for your business. For any inquiries regarding this invoice, please contact our accounts department.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-10 py-6 border-t border-gray-200">
            <div class="flex justify-between items-center text-xs text-gray-500">
                <div>
                    © {{ now()->format('Y') }} {{ __('Your Company Name') }}. {{ __('All rights reserved.') }}
                </div>
                <div class="text-right">
                    {{ __('This is a computer-generated invoice and requires no signature.') }}
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="p-6 text-center no-print bg-white border-t border-gray-200">
            <button onclick="window.print()" class="px-8 py-3 bg-indigo-700 hover:bg-indigo-800 text-white font-semibold rounded-lg shadow-lg transition duration-200 transform hover:scale-105 uppercase tracking-wider text-sm">
                {{ __('Print Invoice') }}
            </button>
        </div>
    </div>

</body>
</html>