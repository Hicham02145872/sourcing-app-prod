<div class="space-y-6">
    <!-- 1. Order Status & Workflow -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Workflow & Status') }}
            </h3>
            <span class="text-xs text-slate-500">{{ __('Last update') }}: {{ $sourcingOrder->updated_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:flex-1">
                    <label for="status" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Update Status') }}</label>
                    <select wire:model="status" id="status" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-slate-900 focus:border-slate-900 bg-slate-50">
                        @php
                            $statusLabels = [
                                'pending_payment' => __('Pending Payment'),
                                'paid' => __('Paid'),
                                'shipment_preparing' => __('Shipment Preparing'),
                                'in_transit_china' => __('In Transit China'),
                                'arrival_uae' => __('Arrival UAE'),
                                'customs_clearance_uae' => __('Customs Clearance UAE'),
                                'in_transit_uae' => __('In Transit UAE'),
                                'arrival_destination_country' => __('Arrival Destination Country'),
                                'customs_clearance_destination_country' => __('Customs Clearance Destination Country'),
                                'out_for_delivery' => __('Out for Delivery'),
                                'delivered' => __('Delivered'),
                                'delivery_failed' => __('Delivery Failed'),
                                'shipment_delayed' => __('Shipment Delayed'),
                                'shipment_returned' => __('Shipment Returned'),
                                'shipment_canceled' => __('Shipment Canceled'),
                                'order_completed' => __('Order Completed'),
                                'on_hold' => __('On Hold'),
                                'refunded' => __('Refunded'),
                                'waiting_for_refund' => __('Waiting for Refund'),
                                'refund_approved' => __('Refund Approved'),
                                'refund_rejected' => __('Refund Rejected'),
                            ];
                        @endphp
                        @foreach (App\Models\SourcingOrder::STATUSES as $statusOption)
                            <option value="{{ $statusOption }}">
                                {{ $statusLabels[$statusOption] ?? ucfirst(str_replace('_', ' ', $statusOption)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button wire:click="updateStatus" wire:loading.attr="disabled" class="w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm h-[38px] flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="updateStatus">{{ __('Update') }}</span>
                    <span wire:loading wire:target="updateStatus">{{ __('Processing...') }}</span>
                    <svg wire:loading wire:target="updateStatus" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
            
            @if(auth()->user()->isSuperAdmin())
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Assignment (Super Admin)') }}</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <select wire:change="assignTo($event.target.value)" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                <option value="">{{ __('Not assigned') }}</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $sourcingOrder->assigned_to_admin_id == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($sourcingOrder->assigned_to_admin_id)
                            <div class="flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-100 rounded text- emerald-700 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="font-semibold">{{ $sourcingOrder->assignedAdmin->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if((auth()->user()->isSuperAdmin() || $sourcingOrder->assigned_to_admin_id === auth()->id()) && !$sourcingOrder->hasMultipleDestinations())
                <!-- Shipping Company Assignment (hidden when multiple destinations: use per-destination assignment below) -->
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Shipping Company') }}</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <select wire:change="assignShippingCompany($event.target.value)" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                <option value="">{{ __('Not assigned') }}</option>
                                @foreach($shippingCompanies as $company)
                                    <option value="{{ $company->id }}" {{ $sourcingOrder->shipping_company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($sourcingOrder->shipping_company_id && $sourcingOrder->shippingCompany)
                            <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-100 rounded text-blue-700 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                <span class="font-semibold">{{ $sourcingOrder->shippingCompany->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- 2. Shipping & Tracking (always visible for admin order detail) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden relative group">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                {{ __('Shipment & Tracking') }}
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-4 p-3 bg-blue-50 border border-blue-100 rounded flex justify-between items-center">
                <span class="text-xs font-bold text-blue-800 uppercase tracking-wider">{{ __('Client Tracking ID (FSB)') }}</span>
                <span class="font-mono text-sm font-bold text-blue-900 bg-white px-2 py-0.5 rounded border border-blue-200">
                    FSB{{ str_pad($sourcingOrder->id, 6, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            @if(!$sourcingOrder->hasMultipleDestinations() && empty($tracking_carrier))
            <div class="mb-3 flex items-start gap-2 p-2.5 rounded-lg bg-amber-50 border border-amber-100">
                <span class="flex-shrink-0 text-amber-600" aria-hidden="true">→</span>
                <p class="text-xs text-amber-800">{{ __('Select the carrier used for this shipment so we can detect the correct tracking.') }}</p>
            </div>
            @endif

            @if($sourcingOrder->hasMultipleDestinations())
                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-3">{{ __('When the order has multiple destinations, enter tracking and carrier for each row. Save to update.') }}</p>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-inner bg-slate-50/30">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-100 border-b-2 border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Destination') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Tracking Number') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Carrier') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Shipping Company') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach($sourcingOrder->quotation->sourcingRequest->destinations as $dest)
                                    @php
                                        $destLabel = $dest->country?->name ?? __('Destination #:n', ['n' => $dest->id]);
                                        if ($dest->service_type ?? null) { $destLabel .= ' · ' . $dest->service_type; }
                                        if (isset($dest->quantity)) { $destLabel .= ' (x' . $dest->quantity . ')'; }
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-slate-800 whitespace-nowrap align-middle">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-200 text-slate-600 text-xs font-bold mr-2">{{ $loop->iteration }}</span>
                                            {{ $destLabel }}
                                        </td>
                                        <td class="px-4 py-3 align-middle">
                                            <input type="text" wire:model.defer="destinationTrackings.{{ $dest->id }}.tracking_number" class="w-full min-w-[120px] px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Ex: ME49508327">
                                        </td>
                                        <td class="px-4 py-3 align-middle">
                                            <input type="text" wire:model.defer="destinationTrackings.{{ $dest->id }}.tracking_carrier" class="w-full min-w-[100px] px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Ex: DHL">
                                        </td>
                                        <td class="px-4 py-3 align-middle">
                                            <select wire:model.defer="destinationTrackings.{{ $dest->id }}.shipping_company_id" class="w-full min-w-[140px] px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                                <option value="">{{ __('Not assigned') }}</option>
                                                @foreach($shippingCompanies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
                        <button type="button" wire:click="saveDestinationTrackings" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-70">
                            <svg wire:loading.remove wire:target="saveDestinationTrackings" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <svg wire:loading wire:target="saveDestinationTrackings" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span wire:loading.remove wire:target="saveDestinationTrackings">{{ __('Save tracking per destination') }}</span>
                            <span wire:loading wire:target="saveDestinationTrackings">{{ __('Processing...') }}</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tracking_number" class="block text-xs font-semibold text-slate-600 mb-1">{{ __('Tracking Number') }}</label>
                        <input type="text" wire:model.defer="tracking_number" id="tracking_number" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" placeholder="Ex: ME49508327">
                    </div>
                    <div>
                        <label for="tracking_carrier" class="block text-xs font-semibold text-slate-600 mb-1">{{ __('Carrier') }}</label>
                        @if(!empty($carrierOptionsForCompany))
                            <select wire:model.defer="tracking_carrier" id="tracking_carrier" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-slate-900 focus:border-slate-900 bg-white">
                                <option value="">{{ __('Choose carrier') }}...</option>
                                @foreach($carrierOptionsForCompany as $opt)
                                    <option value="{{ $opt['key'] }}">{{ $opt['label'] }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" wire:model.defer="tracking_carrier" id="tracking_carrier" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-slate-900 focus:border-slate-900 bg-slate-50" placeholder="Ex: Faster.ae, DHL...">
                        @endif
                    </div>
                </div>
            @endif

            {{-- Multi-destination deep tracking results --}}
            @if(!empty($deepTrackingResults))
                <div class="mt-4 space-y-3">
                    @foreach($deepTrackingResults as $destId => $dtResult)
                        @php
                            $dtEvents = $dtResult['events'] ?? [];
                            $dtLatest = $dtEvents[0] ?? null;
                            $dtStatus = $dtResult['current_status_fr'] ?? $dtResult['current_status'] ?? $dtLatest['status_fr'] ?? $dtLatest['status_en'] ?? $dtLatest['status'] ?? ($dtResult['error'] ?? '—');
                            $dtLocation = $dtLatest['location'] ?? '';
                            $dtLabel = $dtResult['dest_label'] ?? __('Destination');
                        @endphp
                        <div class="p-3 border rounded-lg {{ !empty($dtResult['success']) ? 'border-emerald-200 bg-emerald-50/50' : 'border-amber-200 bg-amber-50/50' }}" x-data="{}">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-sm font-semibold text-slate-800">{{ $dtLabel }}</span>
                                @if(!empty($dtResult['success']))
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-500">{{ $dtResult['provider'] ?? '' }} · {{ $dtStatus }}</span>
                                        <button type="button" @click="$refs.trackingModal_{{ $destId }}.showModal()" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ __('Details') }}
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-amber-700">{{ $dtResult['error'] ?? __('No data') }}</span>
                                @endif
                            </div>

                            @if(!empty($dtResult['success']))
                                <dialog x-ref="trackingModal_{{ $destId }}" class="rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden backdrop:bg-slate-900/50 p-0"
                                    @click="if ($event.target === $refs.trackingModal_{{ $destId }}) $refs.trackingModal_{{ $destId }}.close()">
                                    <div class="bg-white p-0" @click.stop>
                                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                                {{ $dtLabel }}
                                            </h3>
                                            <button type="button" @click="$refs.trackingModal_{{ $destId }}.close()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        <div class="p-6 overflow-y-auto max-h-[70vh]">
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 mb-1">{{ __('Last Status') }}</p>
                                                        <p class="text-sm font-semibold text-slate-900">{{ $dtStatus }}</p>
                                                    </div>
                                                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Last Location') }}</p>
                                                        <p class="text-sm font-semibold text-slate-900">{{ $dtLocation ?: __('N/A') }}</p>
                                                    </div>
                                                </div>
                                                @if(!empty($dtResult['provider']))
                                                    <p class="text-xs text-slate-500">{{ __('Carrier') }}: <span class="font-semibold text-slate-700">{{ $dtResult['provider'] }}</span></p>
                                                @endif
                                                @if(count($dtEvents) > 0)
                                                    <div class="pt-2 border-t border-slate-200">
                                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('History') }} ({{ count($dtEvents) }})</p>
                                                        <ul class="space-y-2">
                                                            @foreach(array_slice($dtEvents, 0, 10) as $ev)
                                                                <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 py-2 px-3 bg-slate-50 rounded-lg text-xs">
                                                                    <span class="font-medium text-slate-800">{{ $ev['status_fr'] ?? $ev['status_en'] ?? $ev['status'] ?? '—' }}</span>
                                                                    @if(!empty($ev['location']))
                                                                        <span class="text-slate-500 flex items-center gap-1">
                                                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                                            {{ $ev['location'] }}
                                                                        </span>
                                                                    @endif
                                                                    @if(!empty($ev['date']))
                                                                        <span class="text-slate-400 text-[10px]">{{ $ev['date'] }}</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        @if(count($dtEvents) > 10)
                                                            <p class="text-[10px] text-slate-400 mt-2">{{ __('And :count more events', ['count' => count($dtEvents) - 10]) }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </dialog>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Single-destination deep tracking result --}}
            @if(isset($deepTrackingResult) && !$sourcingOrder->hasMultipleDestinations())
                @php
                    $events = $deepTrackingResult['events'] ?? [];
                    $latestEvent = $events[0] ?? null;
                    $lastStatus = $deepTrackingResult['current_status_fr'] ?? $deepTrackingResult['current_status'] ?? $latestEvent['status_fr'] ?? $latestEvent['status_en'] ?? $latestEvent['status'] ?? ($deepTrackingResult['error'] ?? '—');
                    $lastLocation = $latestEvent['location'] ?? '';
                @endphp
                <div class="mt-4" x-data="{}">
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button"
                                @click="$refs.trackingModal.showModal()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ __('View Tracking Details') }}
                    </button>
                        @if(!empty($deepTrackingResult['success']))
                            <span class="text-xs text-slate-500">{{ $deepTrackingResult['provider'] ?? '' }} · {{ $lastStatus }}{{ $lastLocation ? ' · ' . $lastLocation : '' }}</span>
                        @endif
                    </div>
                    <dialog x-ref="trackingModal" class="rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden backdrop:bg-slate-900/50 p-0"
                        @click="if ($event.target === $refs.trackingModal) $refs.trackingModal.close()">
                    <div class="bg-white p-0" @click.stop>
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                {{ __('Tracking Details') }}
                            </h3>
                            <button type="button" @click="$refs.trackingModal.close()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto max-h-[70vh]">
                            @if(!empty($deepTrackingResult['success']))
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 mb-1">{{ __('Last Status') }}</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $lastStatus }}</p>
                                        </div>
                                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Last Location') }}</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $lastLocation ?: __('N/A') }}</p>
                                        </div>
                                    </div>
                                    @if(!empty($deepTrackingResult['provider']))
                                        <p class="text-xs text-slate-500">{{ __('Carrier') }}: <span class="font-semibold text-slate-700">{{ $deepTrackingResult['provider'] }}</span></p>
                                    @endif
                                    @if(count($events) > 0)
                                        <div class="pt-2 border-t border-slate-200">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('History') }} ({{ count($events) }})</p>
                                            <ul class="space-y-2">
                                                @foreach(array_slice($events, 0, 10) as $ev)
                                                    <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 py-2 px-3 bg-slate-50 rounded-lg text-xs">
                                                        <span class="font-medium text-slate-800">{{ $ev['status_fr'] ?? $ev['status_en'] ?? $ev['status'] ?? '—' }}</span>
                                                        @if(!empty($ev['location']))
                                                            <span class="text-slate-500 flex items-center gap-1">
                                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                                {{ $ev['location'] }}
                                                            </span>
                                                        @endif
                                                        @if(!empty($ev['date']))
                                                            <span class="text-slate-400 text-[10px]">{{ $ev['date'] }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @if(count($events) > 10)
                                                <p class="text-[10px] text-slate-400 mt-2">{{ __('And :count more events', ['count' => count($events) - 10]) }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                    <p class="text-sm font-semibold text-amber-800">{{ __('Tracking Unavailable') }}</p>
                                    <p class="text-xs text-amber-700 mt-1">{{ $deepTrackingResult['error'] ?? __('No data available.') }}</p>
                                    @if(($deepTrackingResult['status'] ?? '') === 'pending')
                                        <p class="text-xs text-amber-600 mt-2">{{ __('Refresh in progress. Please try again in a few moments.') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </dialog>
                </div>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" wire:click="fetchTrackingStatus" wire:loading.attr="disabled" class="px-4 py-2 bg-slate-100 text-slate-600 border border-slate-200 text-sm font-medium rounded transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg wire:loading.remove wire:target="fetchTrackingStatus" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" /></svg>
                    <svg wire:loading wire:target="fetchTrackingStatus" class="animate-spin h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    {{ __('Deep Tracking') }}
                </button>

                @if(!$sourcingOrder->hasMultipleDestinations())
                    <button type="button" wire:click="updateTracking" wire:loading.attr="disabled" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                        <span wire:loading.remove wire:target="updateTracking">{{ __('Save Tracking') }}</span>
                        <span wire:loading wire:target="updateTracking">{{ __('Processing...') }}</span>
                        <svg wire:loading wire:target="updateTracking" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
