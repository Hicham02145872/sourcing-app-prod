<x-legal-layout>
    <x-slot name="title">{{ __('legal.shipping.title') }}</x-slot>

    <div class="prose prose-slate max-w-none">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-8 border-b pb-4">{{ __('legal.shipping.title') }}</h1>
        
        <div class="space-y-8 text-lg text-slate-600 leading-relaxed">
            <p>
                {{ __('legal.shipping.intro') }}
            </p>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="font-bold text-slate-900 mb-2">{{ __('legal.shipping.cards.partners.title') }}</h3>
                    <p class="text-base">{{ __('legal.shipping.cards.partners.body') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="font-bold text-slate-900 mb-2">{{ __('legal.shipping.cards.tracking.title') }}</h3>
                    <p class="text-base">{{ __('legal.shipping.cards.tracking.body') }}</p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 pt-4">{{ __('legal.shipping.commitments.title') }}</h2>
            <p>
                {{ __('legal.shipping.commitments.body_before') }} <a href="{{ route('refund-policy') }}" class="text-red-600 hover:underline">{{ __('legal.refund.title') }}</a>{{ __('legal.shipping.commitments.body_after') }}
            </p>

            <div class="pt-10 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    {{ __('legal.common.last_updated') }}
                </p>
            </div>
        </div>
    </div>
</x-legal-layout>
