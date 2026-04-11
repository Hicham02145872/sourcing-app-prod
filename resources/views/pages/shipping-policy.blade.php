<x-legal-layout>
    <x-slot name="title">{{ __('Shipping Policy') }}</x-slot>

    <div class="prose prose-slate max-w-none">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-8 border-b pb-4">{{ __('Shipping Policy') }}</h1>
        
        <div class="space-y-8 text-lg text-slate-600 leading-relaxed">
            <p>
                Fast Sourcing Brothers provides global logistics solutions tailored to each destination. Our shipping timeframe depends on the target country and the complexity of the sourcing.
            </p>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="font-bold text-slate-900 mb-2">Carrier Partners</h3>
                    <p class="text-base">We work exclusively with premium carriers including DHL, FedEx, UPS, and Aramex to ensure safe and trackable delivery.</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="font-bold text-slate-900 mb-2">Tracking</h3>
                    <p class="text-base">All shipments include comprehensive tracking. Live updates are available via your client dashboard.</p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 pt-4">Delivery Commitments</h2>
            <p>
                We provide a specific delivery timeframe for each order based on the destination. If we exceed this timeframe, you are eligible for a partial or full refund as per our <a href="{{ route('refund-policy') }}" class="text-red-600 hover:underline">Refund Policy</a>.
            </p>

            <div class="pt-10 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    Last updated: April 2026
                </p>
            </div>
        </div>
    </div>
</x-legal-layout>
