<x-legal-layout>
    <x-slot name="title">{{ __('Refund Policy') }}</x-slot>

    <div class="prose prose-slate max-w-none">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-8 border-b pb-4">{{ __('Refund Policy') }}</h1>
        
        <div class="space-y-8 text-lg text-slate-600 leading-relaxed">
            <div class="bg-red-50/50 border-l-4 border-red-500 p-6 rounded-r-xl">
                <p class="font-semibold text-slate-900">
                    At Fast Sourcing Brothers, we guarantee your investment. You are eligible for a 100% full refund in the following cases:
                </p>
            </div>

            <ul class="space-y-4 list-none pl-0">
                <li class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>If the shipping exceeds the promised timeframe for your specific country.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>If the inventory is damaged or lost during transit.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>If the sourced products do not match the agreed-upon specifications.</span>
                </li>
            </ul>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Processing Time</h3>
                <p>
                    Refunds will be processed back to the original payment method within <span class="font-bold text-red-600">7-10 business days</span> following the approval of your request.
                </p>
            </div>

            <div class="pt-10 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    Last updated: April 2026
                </p>
            </div>
        </div>
    </div>
</x-legal-layout>
