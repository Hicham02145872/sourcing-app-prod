<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Shipping Fees') }}: {{ $country->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.shipping-fees.update', $country) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Air Normal -->
                            <div>
                                <x-input-label for="air_normal_fee" :value="__('Air - Normal Product (per kg)')" />
                                <x-text-input id="air_normal_fee" name="air_normal_fee" type="number" step="0.01" class="mt-1 block w-full" :value="old('air_normal_fee', $country->shippingFee?->air_normal_fee)" />
                                <x-input-error class="mt-2" :messages="$errors->get('air_normal_fee')" />
                            </div>

                            <!-- Air Brand -->
                            <div>
                                <x-input-label for="air_brand_fee" :value="__('Air - Brand Product (per kg)')" />
                                <x-text-input id="air_brand_fee" name="air_brand_fee" type="number" step="0.01" class="mt-1 block w-full" :value="old('air_brand_fee', $country->shippingFee?->air_brand_fee)" />
                                <x-input-error class="mt-2" :messages="$errors->get('air_brand_fee')" />
                            </div>

                            <!-- Air Battery -->
                            <div>
                                <x-input-label for="air_battery_fee" :value="__('Air - Battery Product (per kg)')" />
                                <x-text-input id="air_battery_fee" name="air_battery_fee" type="number" step="0.01" class="mt-1 block w-full" :value="old('air_battery_fee', $country->shippingFee?->air_battery_fee)" />
                                <x-input-error class="mt-2" :messages="$errors->get('air_battery_fee')" />
                            </div>

                            <!-- Air Liquid -->
                            <div>
                                <x-input-label for="air_liquid_fee" :value="__('Air - Liquid Product (per kg)')" />
                                <x-text-input id="air_liquid_fee" name="air_liquid_fee" type="number" step="0.01" class="mt-1 block w-full" :value="old('air_liquid_fee', $country->shippingFee?->air_liquid_fee)" />
                                <x-input-error class="mt-2" :messages="$errors->get('air_liquid_fee')" />
                            </div>

                            <!-- Sea -->
                            <div>
                                <x-input-label for="sea_fee" :value="__('Sea (per CBM/kg)')" />
                                <x-text-input id="sea_fee" name="sea_fee" type="number" step="0.01" class="mt-1 block w-full" :value="old('sea_fee', $country->shippingFee?->sea_fee)" />
                                <x-input-error class="mt-2" :messages="$errors->get('sea_fee')" />
                            </div>

                            <!-- Train -->
                            <div>
                                <x-input-label for="train_fee" :value="__('Train (per kg)')" />
                                <x-text-input id="train_fee" name="train_fee" type="number" step="0.01" class="mt-1 block w-full" :value="old('train_fee', $country->shippingFee?->train_fee)" />
                                <x-input-error class="mt-2" :messages="$errors->get('train_fee')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                            <a href="{{ route('admin.shipping-fees.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
