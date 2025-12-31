<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shipping Label - Order #{{ $sourcingOrder->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
        .label-container {
            width: 100mm; /* Standard label width, adjustable */
            margin: 0 auto;
            background: white;
        }
        .thick-border {
            border: 2px solid black;
        }
        .cell-padding {
            padding: 8px 12px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center py-10">

    <!-- Print Button -->
    <div class="no-print mb-6 flex gap-4">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-colors flex items-center gap-2">
            <i class="fas fa-print"></i> Print Label
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow transition-colors flex items-center gap-2">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

        @foreach($sourcingOrder->quotation->sourcingRequest->destinations as $index => $destination)
            <!-- Label Content -->
            <div class="label-container bg-white p-6 shadow-xl relative mb-8" style="width: 100%; max-width: 500px; page-break-after: always;">
                
                <!-- Logo Section -->
                <div class="text-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="FSB Logo" class="h-20 mx-auto mb-2 object-contain"> 
                </div>

                <!-- Info Table -->
                <div class="border-2 border-black">
                    <!-- Country -->
                    <div class="flex border-b-2 border-black">
                        <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                            Country
                        </div>
                        <div class="w-2/3 p-3 font-semibold text-lg flex items-center">
                            {{ $destination->country->name ?? 'N/A' }}
                        </div>
                    </div>

            <!-- Seller Name -->
            <div class="flex border-b-2 border-black">
                <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                    Seller Name
                </div>
                <div class="w-2/3 p-3 font-semibold text-lg flex items-center">
                    {{ $sourcingOrder->user->name }}
                </div>
            </div>

            <!-- Order ID -->
            <div class="flex border-b-2 border-black">
                <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                    Order ID
                </div>
                <div class="w-2/3 p-3 font-semibold text-lg flex items-center">
                    {{ $sourcingOrder->id }}
                </div>
            </div>

            <!-- Product Name -->
            <div class="flex border-b-2 border-black">
                <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                    Product Name
                </div>
                <div class="w-2/3 p-3 font-semibold text-lg flex items-center">
                    {{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A' }}
                </div>
            </div>

            <!-- Quantity -->
            <div class="flex border-b-2 border-black">
                <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                    Quantity
                </div>
                    <!-- Quantity -->
                    <div class="flex border-b-2 border-black">
                        <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                            Quantity
                        </div>
                        <div class="w-2/3 p-3 font-semibold text-lg flex items-center">
                            {{ $destination->quantity }}
                        </div>
                    </div>

                    <!-- Recipient Address -->
                    <div class="flex">
                        <div class="w-1/3 border-r-2 border-black p-3 font-bold text-lg flex items-center bg-gray-50">
                            Recipient Address
                        </div>
                        <div class="w-2/3 p-3 font-semibold text-lg flex flex-col justify-center gap-2 break-words">
                            <div class="mb-1">
                                <span class="font-bold text-gray-700">{{ $destination->service->name ?? 'Service' }}:</span>
                                <span>{{ $destination->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <p class="text-lg font-bold mb-1">For support or questions</p>
                    <a href="https://wa.me/212646522071" target="_blank" class="flex items-center justify-center gap-2 text-xl font-bold text-green-600 hover:text-green-700 transition-colors">
                        <span>Contact Us</span>
                        <span>+212 646-522071</span>
                    </a>
                </div>

            </div>
        @endforeach

    <script>
        // Auto print if opened in new window/tab? Optional. 
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
