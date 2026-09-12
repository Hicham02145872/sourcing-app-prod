<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.sourcing-orders.show', $sourcingOrder) }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        <div class="h-6 w-px bg-slate-200 mx-1"></div>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">
                                {{ __('Edit Shipping Label') }} — {{ $sourcingOrder->reference_id }}
                            </h1>
                            <p class="text-xs text-slate-500">{{ $sourcingOrder->quotation->sourcingRequest->product_name ?? __('Unknown product') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.sourcing-orders.shipping-label', $sourcingOrder) }}"
                           class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-900 text-white hover:bg-slate-800 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                            {{ __('Download PNG') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('admin.sourcing-orders.update-label', $sourcingOrder) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Order-level Label Overrides') }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ __('These values will appear on the shipping label. Leave empty to use the original values.') }}</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="label_seller_name" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Seller Name') }}
                            </label>
                            <input type="text" name="label_seller_name" id="label_seller_name"
                                   value="{{ old('label_seller_name', $sourcingOrder->label_seller_name ?: $sourcingOrder->user->name) }}"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="label_product_name" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Product Name') }}
                            </label>
                            <input type="text" name="label_product_name" id="label_product_name"
                                   value="{{ old('label_product_name', $sourcingOrder->label_product_name ?: ($sourcingOrder->quotation->sourcingRequest->product_name ?? '')) }}"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                @foreach($sourcingOrder->quotation->sourcingRequest->destinations as $index => $destination)
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                            <span class="fi fi-{{ strtolower($destination->country->code ?? '') }} border border-slate-200 rounded-sm"></span>
                            <h3 class="text-sm font-semibold text-slate-900">
                                {{ __('Destination') }} {{ $index + 1 }}: {{ $destination->country->name ?? 'N/A' }}
                            </h3>
                            <span class="ml-auto text-xs text-slate-500">{{ $destination->service->name ?? '' }}</span>
                        </div>
                        <div class="p-6 space-y-4">
                            <input type="hidden" name="destinations[{{ $index }}][id]" value="{{ $destination->id }}">

                            <div>
                                <label for="dest_{{ $destination->id }}_label_address" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                    {{ __('Label Address (Override)') }}
                                </label>
                                <textarea name="destinations[{{ $index }}][label_address]" id="dest_{{ $destination->id }}_label_address"
                                          rows="3"
                                          class="w-full px-3 py-2 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ old('destinations.' . $index . '.label_address', $destination->label_address ?: ($destination->address ?? '')) }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-xs text-slate-500 bg-slate-50 rounded p-3">
                                <div>
                                    <span class="font-medium text-slate-700">{{ __('Quantity') }}:</span>
                                    <span class="font-mono">{{ $destination->quantity }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-700">{{ __('Service') }}:</span>
                                    <span>{{ $destination->service->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex items-center gap-3 justify-end">
                    <a href="{{ route('admin.sourcing-orders.show', $sourcingOrder) }}"
                       class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                        {{ __('Save & Download PNG') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
