<x-legal-layout>
    <x-slot name="title">{{ __('legal.refund.title') }}</x-slot>

    <div class="prose prose-slate max-w-none">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-8 border-b pb-4">{{ __('legal.refund.title') }}</h1>
        
        <div class="space-y-8 text-lg text-slate-600 leading-relaxed">
            <div class="bg-red-50/50 border-l-4 border-red-500 p-6 rounded-r-xl">
                <p class="font-semibold text-slate-900">
                    {{ __('legal.refund.intro') }}
                </p>
            </div>

            <ul class="space-y-4 list-none pl-0">
                <li class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('legal.refund.points.shipping_delay') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('legal.refund.points.damage_or_loss') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('legal.refund.points.spec_mismatch') }}</span>
                </li>
            </ul>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-4">{{ __('legal.refund.processing.title') }}</h3>
                <p>
                    {{ __('legal.refund.processing.body_before') }} <span class="font-bold text-red-600">{{ __('legal.refund.processing.days') }}</span> {{ __('legal.refund.processing.body_after') }}
                </p>
            </div>

            <div class="pt-10 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    {{ __('legal.common.last_updated') }}
                </p>
            </div>
        </div>
    </div>
</x-legal-layout>
