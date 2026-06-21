<div x-data="verificationPopup()"
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
        <div class="mx-auto w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white text-center mb-3">
            ⚠️ Important Verification Notice
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-400 text-center leading-relaxed mb-6">
            Veuillez vérifier la qualité du produit sur Amazon afin d'éviter les produits contrefaits ou de mauvaise qualité. Consultez les avis clients, les évaluations du vendeur et les photos des acheteurs avant de finaliser votre demande.
        </p>
        <div class="text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 rounded-full text-sm font-semibold" x-text="countdownText"></span>
        </div>
    </div>
</div>
