<!-- Modern Enterprise Loading Spinner - Blade -->
<div x-data="{
    loading: false,
    startTime: null,
    minDisplayTime: 2000,
    currentMessage: 'Préparation des données...',
    messages: [
        'Préparation des données...',
        'Synchronisation des informations...',
        'Optimisation de la plateforme...',
        'Finalisation...'
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
    messageInterval = setInterval(() => updateMessage(), 3000);
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
class="fixed inset-0 bg-white/95 backdrop-blur-lg flex items-center justify-center z-[9999]"
style="display: none;">

    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-violet-50 to-purple-50"></div>

    <div class="relative max-w-md mx-auto px-6">
        <!-- Main Container -->
        <div class="flex flex-col items-center gap-10">
            
            <!-- Spinner -->
            <div class="relative w-24 h-24">
                <!-- Ring 1 -->
                <div class="absolute inset-0 rounded-full border-3 border-transparent border-t-violet-500 border-r-violet-400 animate-spin" style="animation-duration: 2s;"></div>
                
                <!-- Ring 2 -->
                <div class="absolute inset-3 rounded-full border-3 border-transparent border-t-violet-300 animate-spin" style="animation-duration: 3s; animation-direction: reverse;"></div>
                
                <!-- Ring 3 -->
                <div class="absolute inset-6 rounded-full border-2 border-transparent border-t-violet-500 animate-spin" style="animation-duration: 1.5s;"></div>
                
                <!-- Core -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-3 h-3 bg-gradient-to-br from-violet-500 to-purple-600 rounded-full shadow-lg shadow-violet-500/40"></div>
                </div>
            </div>

            <!-- Content Card -->
            <div class="text-center space-y-6 animate-fade-in">
                
                <!-- Header -->
                <div class="space-y-3">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Sourcing App</h2>
                    <p class="text-sm text-violet-600 font-medium">Plateforme Professionnelle</p>
                </div>

                <!-- Message Section -->
                <div class="space-y-2">
                    <p class="text-base font-semibold text-gray-900 min-h-6" x-text="currentMessage"></p>
                    <p class="text-sm text-gray-500">Veuillez patienter pendant que nous préparons votre expérience...</p>
                </div>

                <!-- Progress Bar -->
                <div class="relative w-full h-1 bg-violet-100 rounded-full overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-violet-500 via-purple-500 to-violet-600 rounded-full animate-progress-bar"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"></div>
                </div>

                <!-- Status Pills -->
                <div class="flex items-center justify-center gap-3 pt-4 border-t border-violet-100">
                    <div class="flex items-center gap-2 px-3 py-2 bg-violet-50 rounded-full">
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-gray-600 font-medium">Sécurisé</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-violet-50 rounded-full">
                        <div class="w-1.5 h-1.5 bg-violet-500 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                        <span class="text-xs text-gray-600 font-medium">Connecté</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-violet-50 rounded-full">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                        <span class="text-xs text-gray-600 font-medium">En cours</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-xs text-gray-400 font-medium">© 2024 Sourcing App Enterprise</p>
        </div>
    </div>
</div>

<style>
    @keyframes progress-bar {
        0% {
            transform: translateX(-100%);
        }
        50% {
            transform: translateX(200%);
        }
        100% {
            transform: translateX(200%);
        }
    }

    .animate-progress-bar {
        animation: progress-bar 2s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-shimmer {
        animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
</style>