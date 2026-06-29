@props(['src' => '', 'alt' => ''])

<div
    x-data="{ open: false }"
    @keydown.window.escape="open = false"
    class="inline-block"
>
    <div @click="open = true" class="cursor-pointer" role="button" tabindex="0" @keydown.enter="open = true">
        {{ $slot }}
    </div>

    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-4"
            @click="open = false"
        >
            <div class="relative max-w-[90vw] max-h-[90vh]" @click.stop>
                <button
                    type="button"
                    @click="open = false"
                    class="absolute -top-3 -right-3 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-slate-700 hover:text-slate-900 transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img
                    src="{{ $src }}"
                    alt="{{ $alt }}"
                    class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain"
                >
            </div>
        </div>
    </template>
</div>
