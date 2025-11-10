<!-- Enterprise Loading Spinner - Simple & User Friendly -->
<div x-data="{
    loading: false,
    startTime: null,
    minDisplayTime: 1500,
    currentMessage: 'Chargement en cours...',
    messages: [
        'Chargement en cours...',
        'Préparation de vos données...',
        'Presque prêt...'
    ],
    messageIndex: 0,
    messageInterval: null,
    updateMessage() {
        this.messageIndex = (this.messageIndex + 1) % this.messages.length;
        this.currentMessage = this.messages[this.messageIndex];
    }
}"
@loading-start.window="
    loading = true;
    startTime = Date.now();
    currentMessage = $event.detail.message || messages[0];
    messageIndex = 0;
    clearInterval(messageInterval);
    messageInterval = setInterval(() => updateMessage(), 2500);
"
@loading-stop.window="
    const elapsedTime = Date.now() - startTime;
    clearInterval(messageInterval);
    if (elapsedTime < minDisplayTime) {
        setTimeout(() => { loading = false; }, minDisplayTime - elapsedTime);
    } else {
        loading = false;
    }
"
x-show="loading"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 bg-white/98 backdrop-blur-sm flex items-center justify-center z-[9999]"
style="display: none;">

    <!-- Subtle Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-white to-blue-50/30"></div>

    <div class="relative max-w-sm mx-auto px-6">
        <!-- Main Container -->
        <div class="flex flex-col items-center gap-8">
            
            <!-- Simple Spinner -->
            <div class="relative w-20 h-20">
                <!-- Main Ring -->
                <div class="absolute inset-0 rounded-full border-4 border-blue-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-blue-600 animate-spin"></div>
                
                <!-- Center Dot -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-600 rounded-full"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="text-center space-y-5">
                
                <!-- Brand -->
                <div class="space-y-2">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Sourcing App</h2>
                </div>

                <!-- Message -->
                <div class="space-y-1.5">
                    <p class="text-base font-semibold text-gray-900" x-text="currentMessage"></p>
                    <p class="text-sm text-gray-500">Un instant s'il vous plaît...</p>
                </div>

                <!-- Simple Progress Bar -->
                <div class="relative w-64 h-1 bg-blue-100 rounded-full overflow-hidden">
                    <div class="absolute inset-0 bg-blue-600 rounded-full animate-progress"></div>
                </div>

                <!-- Status Indicator -->
                <div class="flex items-center justify-center gap-2 pt-3">
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-pulse"></div>
                    <span class="text-xs text-gray-600 font-medium">Connexion sécurisée</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes progress {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-progress {
        animation: progress 1.5s ease-in-out infinite;
    }
</style>