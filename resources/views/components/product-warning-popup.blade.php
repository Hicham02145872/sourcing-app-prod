@props(['autoShow' => false])

<div x-data="productWarningPopup({ autoShow: {{ $autoShow ? 'true' : 'false' }} })"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     style="display: none;">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-8 z-10"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        <button type="button" @click="dismiss" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="mx-auto w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.07 14c-.66 1.15.17 2.57 1.42 2.57h16.72c1.25 0 2.08-1.42 1.42-2.57l-8.07-14c-.66-1.15-2.23-1.15-2.89 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white text-center mb-4">
            ⚠️ {{ __('Avertissement concernant les produits proposés') }}
        </h3>
        <div class="text-sm text-slate-600 dark:text-slate-400 text-center leading-relaxed mb-6 space-y-3">
            <p>
                {{ __("Veuillez noter que nous suivons attentivement les directives d'Allah.") }}
            </p>
            <p>
                {{ __("Les produits présentés proviennent de sources fiables, cependant, certains peuvent être des contrefaçons (produits non authentiques), des publicités ou des produits à ne pas acheter.") }}
            </p>
            <p class="font-semibold text-amber-600 dark:text-amber-400">
                {{ __("Nous vous recommandons de vérifier les évaluations et les avis des clients avant tout achat.") }}
            </p>
        </div>
        <div class="text-center">
            <button type="button" @click="dismiss"
                    class="px-6 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __("J'ai compris") }}
            </button>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-3">
                {{ __("Cette annonce s'affiche une fois toutes les 24 heures.") }}
            </p>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.data('productWarningPopup')) {
            Alpine.data('productWarningPopup', (config = {}) => ({
                show: false,
                autoShow: config.autoShow || false,
                storageKey: 'product_warning_last_seen',
                twentyFourHours: 86400000,

                init() {
                    if (this.autoShow) {
                        const lastSeen = localStorage.getItem(this.storageKey);
                        if (!lastSeen || (Date.now() - parseInt(lastSeen)) > this.twentyFourHours) {
                            this.show = true;
                        }
                    }
                },

                dismiss() {
                    localStorage.setItem(this.storageKey, Date.now().toString());
                    this.show = false;
                }
            }));
        }
    });
</script>
@endpush
@endonce
