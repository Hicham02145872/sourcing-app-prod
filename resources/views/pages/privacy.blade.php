<x-legal-layout>
    <x-slot name="title">{{ __('legal.privacy.title') }}</x-slot>

    <div class="prose prose-slate max-w-none">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-8 border-b pb-4">{{ __('legal.privacy.title') }}</h1>
        
        <div class="space-y-8 text-lg text-slate-600 leading-relaxed">
            <section>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('legal.privacy.sections.collection.title') }}</h2>
                <p>{{ __('legal.privacy.sections.collection.body') }}</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('legal.privacy.sections.usage.title') }}</h2>
                <p>{{ __('legal.privacy.sections.usage.body') }}</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('legal.privacy.sections.security.title') }}</h2>
                <p>{{ __('legal.privacy.sections.security.body') }}</p>
            </section>

            <div class="pt-10 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    {{ __('legal.common.last_updated') }}
                </p>
            </div>
        </div>
    </div>
</x-legal-layout>
