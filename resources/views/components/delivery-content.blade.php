@props(['deliveryType' => 'indirect'])

@php
    $properties = \App\Models\DeliveryProperty::active()->forType($deliveryType)->orderBy('sort_order')->get();
    $defects = \App\Models\DeliveryDefect::active()->forType($deliveryType)->orderBy('sort_order')->get();
    $notice = \App\Models\DeliveryNotice::active()->forType($deliveryType)->first();
@endphp

@if($properties->isNotEmpty())
    <div class="space-y-2 mb-4">
        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Delivery Properties') }}</h4>
        @foreach($properties as $property)
            <div class="flex items-start gap-2 p-2 bg-green-50 rounded-lg border border-green-100">
                @if($property->icon)
                    <span class="text-base">{{ $property->icon }}</span>
                @endif
                <div>
                    <p class="text-xs font-bold text-green-800">{{ $property->title }}</p>
                    @if($property->description)
                        <p class="text-[11px] text-green-700">{{ $property->description }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($defects->isNotEmpty())
    <div class="space-y-2 mb-4">
        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Logistics Limitations') }}</h4>
        @foreach($defects as $defect)
            <div class="flex items-start gap-2 p-2 bg-red-50 rounded-lg border border-red-100">
                <div>
                    <p class="text-xs font-bold text-red-800">{{ $defect->title }}</p>
                    @if($defect->description)
                        <p class="text-[11px] text-red-700">{{ $defect->description }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($notice)
    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4">
        <h4 class="text-[10px] font-bold text-amber-700 uppercase tracking-widest mb-1">{{ __('Discharge Notice') }}</h4>
        <p class="text-xs text-amber-800">{{ $notice->body_text }}</p>
    </div>
@endif
