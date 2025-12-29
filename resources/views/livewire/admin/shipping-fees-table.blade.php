<div>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium">{{ __('Shipping Rates per Country') }}</h3>
        
        <div class="relative w-72">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search country...') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm pl-10">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div wire:loading class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">{{ __('Country') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Air (Normal)') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Air (Brand)') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Air (Battery)') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Air (Liquid)') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Sea') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Train') }}</th>
                    <th scope="col" class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody wire:loading.class="opacity-50 transition-opacity">
                @forelse($countries as $country)
                    <tr class="bg-white border-b hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600">
                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center gap-2">
                            @if($country->code)
                                <span class="fi fi-{{ strtolower($country->code) }}"></span>
                            @endif
                            {{ $country->name }}
                        </th>
                        <td class="py-4 px-6">
                            {{ $country->shippingFee?->air_normal_fee ? number_format($country->shippingFee->air_normal_fee, 2) . ' ' . $country->shippingFee->currency : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $country->shippingFee?->air_brand_fee ? number_format($country->shippingFee->air_brand_fee, 2) . ' ' . $country->shippingFee->currency : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $country->shippingFee?->air_battery_fee ? number_format($country->shippingFee->air_battery_fee, 2) . ' ' . $country->shippingFee->currency : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $country->shippingFee?->air_liquid_fee ? number_format($country->shippingFee->air_liquid_fee, 2) . ' ' . $country->shippingFee->currency : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $country->shippingFee?->sea_fee ? number_format($country->shippingFee->sea_fee, 2) . ' ' . $country->shippingFee->currency : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $country->shippingFee?->train_fee ? number_format($country->shippingFee->train_fee, 2) . ' ' . $country->shippingFee->currency : '-' }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.shipping-fees.edit', $country) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">{{ __('Edit Rates') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No countries found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $countries->links() }}
    </div>
</div>
