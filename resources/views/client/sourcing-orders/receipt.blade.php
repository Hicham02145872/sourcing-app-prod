<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
            }
            .print\:bg-white {
                background: white !important;
            }
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .black-white-gradient {
            background: linear-gradient(135deg, #000000 0%, #2d3748 100%);
        }
        
        .accent-border {
            border-left: 4px solid #000000;
        }
        
        .corporate-shadow {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        .elegant-border {
            border: 1px solid #e5e7eb;
        }
        
        .dark-border {
            border: 1px solid #374151;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="max-w-5xl mx-auto my-8 print:my-0">
        <!-- Action Bar - Noir & Blanc Corporate -->
        <div class="no-print mb-6 bg-white rounded-xl corporate-shadow elegant-border p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ __('Commercial Invoice') }}</h1>

                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.history.back()" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-arrow-left text-sm"></i>
                        {{ __('Back') }}
                    </button>
                    <button onclick="window.print()" class="flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-black hover:bg-gray-800 rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-print text-sm"></i>
                        {{ __('Print Invoice') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Invoice Container -->
        <div class="bg-white rounded-xl corporate-shadow elegant-border overflow-hidden print:shadow-none print:border-0">
            
            <!-- Header Section - Noir Élégant -->
            <div class="black-white-gradient px-8 py-10 text-white">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                            <i class="fas fa-building text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2 tracking-tight">{{ __('INVOICE') }}</h1>
                            <p class="text-gray-200 opacity-90 font-light">{{ __('Official Commercial Document') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/10 backdrop-blur-sm px-6 py-4 rounded-xl border border-white/20">
                            <div class="text-sm text-gray-200 mb-1 uppercase tracking-wider font-medium">{{ __('Date Issued') }}</div>
                            <div class="text-lg font-semibold">{{ now()->format('F d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company & Client Information -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- From Company -->
                    <div class="bg-gray-50 rounded-xl p-6 elegant-border">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-8 bg-black rounded-full"></div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('From') }}</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="text-xl font-bold text-gray-900">CORPORATE ENTERPRISE</div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-map-marker-alt text-gray-800 text-sm"></i>
                                <span class="text-sm">123 Business Avenue, Suite 100</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-city text-gray-800 text-sm"></i>
                                <span class="text-sm">New York, NY 10001, USA</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-phone text-gray-800 text-sm"></i>
                                <span class="text-sm">+1 (555) 123-4567</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bill To -->
                    <div class="bg-gray-50 rounded-xl p-6 elegant-border">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-8 bg-black rounded-full"></div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('Bill To') }}</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="text-xl font-bold text-gray-900">{{ $sourcingOrder->user->name }}</div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-envelope text-gray-800 text-sm"></i>
                                <span class="text-sm">{{ $sourcingOrder->user->email }}</span>
                            </div>

                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-calendar text-gray-800 text-sm"></i>
                                <span class="text-sm">{{ __('Member since:') }} {{ $sourcingOrder->user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status & Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-xl p-4 elegant-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                                <i class="fas fa-tag text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ __('Order Status') }}</p>
                                <p class="text-lg font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $sourcingOrder->status) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 elegant-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ __('Total Quantity') }}</p>
                                <p class="text-lg font-bold text-gray-900">{{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 elegant-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ __('Currency') }}</p>
                                <p class="text-lg font-bold text-gray-900">{{ $sourcingOrder->quotation->currency }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-8 bg-black rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Order Details') }}</h3>
                    </div>
                    
                    <div class="overflow-hidden rounded-xl elegant-border">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left p-6 text-sm font-bold text-gray-700 uppercase tracking-wider">{{ __('Product Description') }}</th>
                                    <th class="text-center p-6 text-sm font-bold text-gray-700 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                    <th class="text-right p-6 text-sm font-bold text-gray-700 uppercase tracking-wider">{{ __('Unit Price') }}</th>
                                    <th class="text-right p-6 text-sm font-bold text-gray-700 uppercase tracking-wider">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-6">
                                        <div class="font-semibold text-gray-900 text-lg">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</div>

                                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                            <i class="fas fa-industry text-gray-800 text-xs"></i>
                                            <span>{{ __('Category:') }} {{ $sourcingOrder->quotation->sourcingRequest->category->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-center">
                                        <div class="inline-flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full border border-gray-300">
                                            <i class="fas fa-cube text-gray-700 text-xs"></i>
                                            <span class="font-semibold text-gray-900">{{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }}</span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-right font-semibold text-gray-700">
                                        {{ number_format($sourcingOrder->total_amount / max($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity'), 1), 2) }} {{ $sourcingOrder->quotation->currency }}
                                    </td>
                                    <td class="p-6 text-right font-bold text-gray-900 text-lg">
                                        {{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals Section -->
                <div class="flex justify-end mb-8">
                    <div class="w-96">
                        <div class="bg-gray-50 rounded-xl elegant-border p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 font-medium">{{ __('Subtotal') }}</span>
                                    <span class="text-gray-900 font-semibold">{{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-t border-gray-200">
                                    <span class="text-gray-600 font-medium">{{ __('Tax (0%)') }}</span>
                                    <span class="text-gray-900 font-semibold">0.00 {{ $sourcingOrder->quotation->currency }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-t border-gray-200">
                                    <span class="text-gray-600 font-medium">{{ __('Shipping') }}</span>
                                    <span class="text-gray-900 font-semibold">0.00 {{ $sourcingOrder->quotation->currency }}</span>
                                </div>
                                <div class="flex justify-between items-center py-4 border-t-2 border-gray-800 bg-white px-4 -mx-4 rounded-lg">
                                    <span class="text-lg font-bold text-gray-900">{{ __('TOTAL DUE') }}</span>
                                    <span class="text-xl font-bold text-gray-900">{{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment & Notes Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 border-t-2 border-gray-200 pt-8">
                    <div class="bg-gray-50 rounded-xl p-6 elegant-border">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('Payment Terms') }}</h3>
                        </div>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-gray-800 text-xs"></i>
                                <span>{{ __('Payment due within 30 days of invoice date') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-gray-800 text-xs"></i>
                                <span>{{ __('Late payments subject to 1.5% monthly interest') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-university text-gray-800 text-xs"></i>
                                <span>{{ __('Bank transfer preferred') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-credit-card text-gray-800 text-xs"></i>
                                <span>{{ __('Wire transfers accepted') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 elegant-border">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sticky-note text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('Notes') }}</h3>
                        </div>
                        <div class="text-sm text-gray-600 space-y-2">
                            <p>{{ __('Thank you for your business. We appreciate your trust in our sourcing services.') }}</p>
                            <p>{{ __('For any inquiries regarding this invoice, please contact our accounts department.') }}</p>
                            <div class="flex items-center gap-2 mt-3 text-gray-800">
                                <i class="fas fa-envelope text-sm"></i>
                                <span class="text-sm font-medium">accounts@corporate-enterprise.com</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-800">
                                <i class="fas fa-phone text-sm"></i>
                                <span class="text-sm font-medium">+1 (555) 123-4567</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white text-sm"></i>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-900">CORPORATE ENTERPRISE</span> • {{ __('All rights reserved.') }}
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 text-center md:text-right">
                        {{ __('Invoice generated electronically on') }} {{ now()->format('F d, Y \a\t H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
0
</body>
</html>