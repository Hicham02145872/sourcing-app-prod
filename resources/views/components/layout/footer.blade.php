<footer class="mt-auto border-t bg-white dark:bg-gray-800 dark:border-gray-700">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Tous droits réservés.</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Confidentialité</a>
                <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Conditions</a>
                <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Support</a>
            </div>
        </div>
    </div>
</footer>