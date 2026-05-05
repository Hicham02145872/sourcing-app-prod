<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Shipment Timeline Calendar') }}</h1>
                            <p class="text-xs text-slate-500 hidden sm:block">{{ __('Track all shipments journey from China to destination') }}</p>
                        </div>
                    </div>
                    
                    <!-- Date & Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end mr-2">
                            <span class="text-xs font-bold text-slate-700">{{ now()->format('l, d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ __('Casablanca (GMT+1)') }}</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                        
                        <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-orange-600 transition-colors" title="{{ __('Refresh') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Total Shipments -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Active Shipments') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($orders->count()) }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span> {{ __('In transit') }}
                    </div>
                </div>

                <!-- In Transit China -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('In Transit China') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($orders->where('status', 'in_transit_china')->count()) }}</h3>
                        </div>
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-orange-600 font-medium">
                        <span class="animate-pulse w-1.5 h-1.5 rounded-full bg-orange-500 mr-2"></span> {{ __('En route') }}
                    </div>
                </div>

                <!-- At Customs -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-purple-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('At Customs') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($orders->whereIn('status', ['customs_clearance_uae', 'customs_clearance_destination_country'])->count()) }}</h3>
                        </div>
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        {{ __('Clearance in progress') }}
                    </div>
                </div>

                <!-- Out for Delivery -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-green-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Out for Delivery') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($orders->where('status', 'out_for_delivery')->count()) }}</h3>
                        </div>
                        <div class="p-2 bg-green-50 text-green-600 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-green-600 font-medium">
                        <span class="animate-pulse w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> {{ __('Final mile') }}
                    </div>
                </div>
            </div>

            <!-- Delivery Time Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 mb-1">{{ __('Estimated Delivery Times') }}</h4>
                    <p class="text-xs text-blue-800 mb-2">
                        {{ __('Delivery times vary based on sourcing location:') }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="bg-white rounded p-2 border border-blue-100">
                            <p class="text-xs font-semibold text-blue-900">🇨🇳 {{ __('From China') }}</p>
                            <p class="text-xs text-blue-700">15-20 {{ __('working days') }}</p>
                            <p class="text-[10px] text-blue-600 italic">≈ 25 {{ __('days total') }}</p>
                        </div>
                        <div class="bg-white rounded p-2 border border-blue-100">
                            <p class="text-xs font-semibold text-blue-900">🇦🇪 {{ __('From Dubai/UAE') }}</p>
                            <p class="text-xs text-blue-700">1-7 {{ __('working days') }}</p>
                            <p class="text-[10px] text-blue-600 italic">≈ 7 {{ __('days total') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Section -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('Shipment Timeline View') }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <button id="prevMonth" class="p-1.5 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button id="today" class="px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-orange-600 hover:bg-orange-50 rounded transition-colors">
                            {{ __('Today') }}
                        </button>
                        <button id="nextMonth" class="p-1.5 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Legend -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">{{ __('Shipment Stages') }}</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-orange-500 rounded"></span>
                        <span class="text-xs text-slate-600">📦 {{ __('Departure China') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-sky-500 rounded"></span>
                        <span class="text-xs text-slate-600">🚢 {{ __('Transit China → UAE') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-500 rounded"></span>
                        <span class="text-xs text-slate-600">✈️ {{ __('Arrival UAE') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-purple-500 rounded"></span>
                        <span class="text-xs text-slate-600">🏛️ {{ __('Customs UAE') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-cyan-500 rounded"></span>
                        <span class="text-xs text-slate-600">🚚 {{ __('Transit') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-purple-500 rounded"></span>
                        <span class="text-xs text-slate-600">🏛️ {{ __('Final Customs') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded"></span>
                        <span class="text-xs text-slate-600">🎯 {{ __('Delivery') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: false, // We use custom buttons
                height: 'auto',
                firstDay: 1, // Monday
                locale: '{{ app()->getLocale() }}',
                
                events: '{{ route("admin.shipment-calendar.events") }}',
                
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    
                    // Create and show modal with product image
                    showShipmentModal(props);
                },
                
                eventDidMount: function(info) {
                    const props = info.event.extendedProps;
                    
                    // Add tooltip
                    info.el.title = `Order #${props.displayId} - ${props.clientName}\nStatus: ${props.status}`;
                    
                    // Add custom styling based on stage completion
                    if (props.isCompleted) {
                        info.el.style.opacity = '1';
                        info.el.style.fontWeight = 'bold';
                    } else if (props.isCurrent) {
                        info.el.style.opacity = '1';
                        info.el.style.fontWeight = 'bold';
                        info.el.style.border = '2px solid #fff';
                        info.el.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
                    } else {
                        info.el.style.opacity = '0.5';
                    }
                },
                
                // Styling
                eventColor: '#EF7722',
                eventBorderColor: '#EF7722',
                eventTextColor: '#ffffff',
                
                dayCellClassNames: function(arg) {
                    if (arg.isToday) {
                        return ['bg-orange-50'];
                    }
                    return [];
                },
            });
            
            calendar.render();
            
            // Custom navigation buttons
            document.getElementById('prevMonth').addEventListener('click', function() {
                calendar.prev();
            });
            
            document.getElementById('today').addEventListener('click', function() {
                calendar.today();
            });
            
            document.getElementById('nextMonth').addEventListener('click', function() {
                calendar.next();
            });
            
            // Modal function to show shipment details
            function showShipmentModal(props) {
                // Remove existing modal if any
                const existingModal = document.getElementById('shipmentModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Create modal HTML
                const modalHTML = `
                    <div id="shipmentModal" class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <!-- Header -->
                                <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
                                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        Détails de l'expédition #${props.displayId}
                                    </h3>
                                    <button onclick="document.getElementById('shipmentModal').remove()" class="text-slate-400 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Content -->
                                <div class="bg-white px-6 py-6">
                                    ${props.productImage ? `
                                        <div class="mb-4 flex justify-center">
                                            <img src="${props.productImage}" alt="${props.productName}" class="w-48 h-48 object-cover rounded-lg border-2 border-slate-200 shadow-md">
                                        </div>
                                    ` : ''}
                                    
                                    <div class="space-y-3">
                                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                            <p class="text-xs font-bold text-orange-600 uppercase tracking-wider mb-1">Produit</p>
                                            <p class="text-sm font-bold text-slate-900">${props.productName}</p>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Client</p>
                                                <p class="text-sm font-semibold text-slate-900">${props.clientName}</p>
                                            </div>
                                            
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Statut</p>
                                                <p class="text-sm font-semibold text-slate-900">${props.status.replace(/_/g, ' ').toUpperCase()}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Numéro de suivi</p>
                                            <p class="text-sm font-mono font-bold text-slate-900">${props.trackingNumber}</p>
                                        </div>
                                        
                                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                            <p class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">Étape actuelle</p>
                                            <p class="text-sm font-semibold text-slate-900">${props.stage.replace(/_/g, ' ').toUpperCase()}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3">
                                    <button onclick="document.getElementById('shipmentModal').remove()" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-md text-sm font-medium hover:bg-slate-300 transition-colors">
                                        Fermer
                                    </button>
                                    <a href="/admin/sourcing-orders/${props.orderId}" class="px-4 py-2 bg-orange-600 text-white rounded-md text-sm font-medium hover:bg-orange-700 transition-colors">
                                        Voir la commande
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Close on background click
                document.getElementById('shipmentModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.remove();
                    }
                });
            }
        });
    </script>

    <style>
        /* FullCalendar Custom Styling to match admin dashboard */
        .fc {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .fc-daygrid-day-number {
            color: #475569;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .fc-col-header-cell {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            padding: 0.75rem 0.5rem;
        }
        
        .fc-daygrid-day {
            border-color: #e2e8f0;
        }
        
        .fc-daygrid-day.fc-day-today {
            background-color: #fff7ed !important;
        }
        
        .fc-event {
            border-radius: 0.25rem;
            padding: 2px 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .fc-daygrid-event-dot {
            display: none;
        }
        
        /* Stage-specific styling */
        .completed-stage {
            opacity: 1 !important;
        }
        
        .current-stage {
            opacity: 1 !important;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .upcoming-stage {
            opacity: 0.5 !important;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
    </style>
    @endpush
</x-app-layout>
