<x-app-layout>
    <div class="min-h-screen bg-orange-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-orange-200 dark:border-gray-700">
            <div class="max-w-6xl mx-auto px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Contenu Welcome') }}</h1>
                        <p class="text-orange-600 dark:text-orange-400 mt-1">{{ __('Gérez votre page d\'accueil') }}</p>
                    </div>
                    <div class="text-right bg-orange-50 dark:bg-gray-700 px-4 py-3 rounded-lg border border-orange-200 dark:border-gray-600">
                        <p class="text-sm text-orange-600 dark:text-orange-400">{{ __('Last Updated') }}</p>
                        <p class="text-lg font-semibold text-orange-900 dark:text-white">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 py-8">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="mb-6 bg-white dark:bg-gray-800 border-l-4 border-orange-500 p-4 rounded shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check text-orange-500"></i>
                        <p class="text-gray-700 dark:text-gray-100">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.welcome-content.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Hero Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-orange-200 dark:border-gray-700 bg-orange-50 dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Hero Section') }}</h2>
                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">{{ __('Main content of your welcome page') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="hero_badge" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Badge') }}</label>
                            <input type="text" name="hero_badge" id="hero_badge" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['hero_badge']->value ?? '' }}" placeholder="{{ __('ex: New product') }}">
                        </div>
                        <div>
                            <label for="hero_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Titre') }}</label>
                            <textarea name="hero_title" id="hero_title" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white" placeholder="{{ __('Votre titre principal') }}">{{ $contents['hero_title']->value ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Sous-titre') }}</label>
                            <textarea name="hero_subtitle" id="hero_subtitle" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white" placeholder="{{ __('Description courte') }}">{{ $contents['hero_subtitle']->value ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="hero_button" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Texte du bouton') }}</label>
                            <input type="text" name="hero_button" id="hero_button" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['hero_button']->value ?? '' }}" placeholder="{{ __('ex: Commencer') }}">
                        </div>
                    </div>
                </div>

                <!-- How It Works -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-orange-200 dark:border-gray-700 bg-orange-50 dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('How It Works') }}</h2>
                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">{{ __('The steps of your process') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="how_it_works_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Titre') }}</label>
                                <input type="text" name="how_it_works_title" id="how_it_works_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['how_it_works_title']->value ?? '' }}">
                            </div>
                            <div>
                                <label for="how_it_works_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Sous-titre') }}</label>
                                <textarea name="how_it_works_subtitle" id="how_it_works_subtitle" rows="1" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white">{{ $contents['how_it_works_subtitle']->value ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Steps -->
                        <div class="space-y-4 mt-6">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="bg-orange-50 dark:bg-gray-700 p-4 rounded-lg border border-orange-200 dark:border-gray-600">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-6 h-6 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-semibold">{{ $i }}</div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">{{ __('Étape') }} {{ $i }}</h3>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="step{{ $i }}_title" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Titre') }}</label>
                                            <input type="text" name="step{{ $i }}_title" id="step{{ $i }}_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['step' . $i . '_title']->value ?? '' }}">
                                        </div>
                                        <div>
                                            <label for="step{{ $i }}_description" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Description') }}</label>
                                            <textarea name="step{{ $i }}_description" id="step{{ $i }}_description" rows="1" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white">{{ $contents['step' . $i . '_description']->value ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-orange-200 dark:border-gray-700 bg-orange-50 dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Benefits') }}</h2>
                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">{{ __('The benefits of your platform') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="benefits_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Titre') }}</label>
                                <input type="text" name="benefits_title" id="benefits_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['benefits_title']->value ?? '' }}">
                            </div>
                            <div>
                                <label for="benefits_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Sous-titre') }}</label>
                                <textarea name="benefits_subtitle" id="benefits_subtitle" rows="1" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white">{{ $contents['benefits_subtitle']->value ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="bg-orange-50 dark:bg-gray-700 p-4 rounded-lg border border-orange-200 dark:border-gray-600">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-3 text-sm">{{ __('Avantage') }} {{ $i }}</h3>
                                    <div class="space-y-2">
                                        <div>
                                            <label for="benefit{{ $i }}_title" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Titre') }}</label>
                                            <input type="text" name="benefit{{ $i }}_title" id="benefit{{ $i }}_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['benefit' . $i . '_title']->value ?? '' }}">
                                        </div>
                                        <div>
                                            <label for="benefit{{ $i }}_description" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Description') }}</label>
                                            <textarea name="benefit{{ $i }}_description" id="benefit{{ $i }}_description" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white">{{ $contents['benefit' . $i . '_description']->value ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Testimonials Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-orange-200 dark:border-gray-700 bg-orange-50 dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Testimonials') }}</h2>
                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">{{ __('Client reviews') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="testimonials_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Titre') }}</label>
                                <input type="text" name="testimonials_title" id="testimonials_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['testimonials_title']->value ?? '' }}">
                            </div>
                            <div>
                                <label for="testimonials_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Sous-titre') }}</label>
                                <textarea name="testimonials_subtitle" id="testimonials_subtitle" rows="1" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white">{{ $contents['testimonials_subtitle']->value ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-3 mt-4">
                            @for($i = 1; $i <= 3; $i++)
                                <div>
                                    <label for="testimonial{{ $i }}_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Témoignage') }} {{ $i }}</label>
                                    <textarea name="testimonial{{ $i }}_text" id="testimonial{{ $i }}_text" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white" placeholder="{{ __('Texte du témoignage') }}">{{ $contents['testimonial' . $i . '_text']->value ?? '' }}</textarea>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-orange-200 dark:border-gray-700 bg-orange-50 dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Call to Action') }}</h2>
                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">{{ __('Final section') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="cta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Titre') }}</label>
                            <input type="text" name="cta_title" id="cta_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['cta_title']->value ?? '' }}">
                        </div>
                        <div>
                            <label for="cta_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Sous-titre') }}</label>
                            <textarea name="cta_subtitle" id="cta_subtitle" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none dark:bg-gray-900 dark:text-white">{{ $contents['cta_subtitle']->value ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="cta_button" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Texte du bouton') }}</label>
                            <input type="text" name="cta_button" id="cta_button" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white" value="{{ $contents['cta_button']->value ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition">
                        {{ __('Save') }}
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-50 transition dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>