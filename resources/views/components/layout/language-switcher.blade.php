<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group">
        <svg class="h-6 w-6 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50" style="display: none;" x-transition>
        <a href="{{ route('language.switch', 'en') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors @if(app()->getLocale() == 'en') bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-white @endif">
            <span class="fi fi-gb"></span>
            <span>English</span>
        </a>
        <a href="{{ route('language.switch', 'fr') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors @if(app()->getLocale() == 'fr') bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-white @endif">
            <span class="fi fi-fr"></span>
            <span>Français</span>
        </a>
    </div>
</div>