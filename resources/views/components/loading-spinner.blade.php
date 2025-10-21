<!-- Professional Enterprise Loading Spinner -->
<div x-data="{ loading: false, startTime: null, minDisplayTime: 2000, currentMessage: 'Processing your request...' }" 
     @loading-start.window="loading = true; startTime = Date.now(); currentMessage = $event.detail.message || 'Processing your request...';" 
     @loading-stop.window="
        const elapsedTime = Date.now() - startTime;
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
     class="fixed inset-0 bg-white/98 backdrop-blur-lg flex items-center justify-center z-[9999]" 
     style="display: none;">
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgb(139, 92, 246) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="relative max-w-md mx-auto px-8">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-violet-100 p-12 relative overflow-hidden">
            <!-- Accent Border Top -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 via-purple-500 to-violet-500"></div>
            
            <!-- Animated Corner Accents -->
            <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br from-violet-100/50 to-transparent rounded-br-full"></div>
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-gradient-to-tl from-violet-100/50 to-transparent rounded-tl-full"></div>
            
            <div class="relative flex flex-col items-center space-y-8">
                <!-- Logo/Brand Section -->
                <div class="flex flex-col items-center space-y-3">
                    <div class="relative">
                        <!-- Outer Ring -->
                        <div class="absolute inset-0 rounded-full border-4 border-violet-200 animate-ping opacity-20"></div>
                        
                        <!-- Main Logo Container -->
                        <div class="relative w-20 h-20 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl shadow-lg flex items-center justify-center transform rotate-12 hover:rotate-0 transition-transform duration-500">
                            <!-- Sourcing Icon -->
                            <svg class="w-10 h-10 text-white transform -rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Brand Name -->
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Sourcing App</h2>
                        <p class="text-sm text-violet-600 font-medium mt-1">Professional Platform</p>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div class="w-full space-y-4">
                    <!-- Progress Bar Container -->
                    <div class="relative">
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <!-- Animated Dots -->
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-violet-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                <div class="w-3 h-3 bg-violet-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                <div class="w-3 h-3 bg-violet-300 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="relative w-full h-2 bg-violet-100 rounded-full overflow-hidden shadow-inner">
                            <div class="absolute inset-0 bg-gradient-to-r from-violet-500 via-purple-500 to-violet-600 rounded-full animate-progress-slide"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                        </div>
                    </div>

                    <!-- Loading Text -->
                    <div class="text-center space-y-2">
                        <p class="text-base font-semibold text-gray-900" x-text="currentMessage"></p>
                        <p class="text-sm text-gray-500">Please wait while we prepare your data...</p>
                    </div>
                </div>

                <!-- Status Indicators -->
                <div class="flex items-center justify-center space-x-6 pt-4 border-t border-violet-100 w-full">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-gray-600 font-medium">Secure</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-violet-500 rounded-full animate-pulse" style="animation-delay: 200ms"></div>
                        <span class="text-xs text-gray-600 font-medium">Connected</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 400ms"></div>
                        <span class="text-xs text-gray-600 font-medium">Processing</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Powered By Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-400 font-medium">Powered by Sourcing App Enterprise © 2024</p>
        </div>
    </div>
</div>

<style>
    /* Progress Slide Animation */
    @keyframes progress-slide {
        0% {
            transform: translateX(-100%);
        }
        50% {
            transform: translateX(0%);
        }
        100% {
            transform: translateX(100%);
        }
    }
    
    .animate-progress-slide {
        animation: progress-slide 2s ease-in-out infinite;
    }

    /* Shimmer Effect */
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

    /* Enhanced Bounce */
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0) scale(1);
        }
        50% {
            transform: translateY(-8px) scale(1.1);
        }
    }

    /* Ping Animation */
    @keyframes ping {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.4;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    /* Pulse Animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
</style>