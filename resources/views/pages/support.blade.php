<x-legal-layout>
    <x-slot name="title">{{ __('legal.support.title') }}</x-slot>

    <div class="prose prose-slate max-w-none">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-8 border-b pb-4">{{ __('legal.support.contact_title') }}</h1>
        
        <div class="grid md:grid-cols-2 gap-12 text-lg text-slate-600 leading-relaxed">
            <div class="space-y-6">
                <p>{{ __('legal.support.intro') }}</p>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ __('legal.support.labels.email_us') }}</p>
                            <a href="mailto:support@fastsourcingbrothers.com" class="text-base text-red-600 hover:underline">support@fastsourcingbrothers.com</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414A8 8 0 106.343 17.657l4.243-4.243m7.071 3.243a8 8 0 11-11.314 0 8 8 0 0111.314 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ __('legal.support.labels.our_hq') }}</p>
                            <p class="text-base">5830 E 2nd St, Casper, WY 82609, USA</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-4">{{ __('legal.support.fastest_response.title') }}</h3>
                    <p class="text-slate-400 mb-6 italic text-sm">{{ __('legal.support.fastest_response.quote') }}</p>
                    <p class="font-bold text-lg">Hicham Altit</p>
                    <p class="text-red-500 text-sm">{{ __('legal.support.fastest_response.role') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-legal-layout>
