<?php

namespace App\Services;

use App\Models\TrackingTranslation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TrackingTranslationService
{
    public function translate(string $provider, string $text, string $targetLang = 'fr'): string
    {
        if (empty(trim($text))) {
            return $text;
        }

        // 1. Check Local Cache
        $cached = TrackingTranslation::getCached($provider, $text);
        if ($cached) {
            return $cached;
        }

        // 2. Perform External Translation
        try {
            // Using stichoza/google-translate-php if available, or a simple fallback
            // For now, assuming we use a free package or API. 
            // Since the python script used deep-translator (free), we can use similar logic here.
            
            // We'll use a wrapper around Google Translate or just return text if fails
            $translated = GoogleTranslate::trans($text, $targetLang);

            // 3. Cache Result
            TrackingTranslation::cache($provider, $text, $translated);

            return $translated;

        } catch (\Exception $e) {
            Log::warning("Translation failed for [$provider] '$text': " . $e->getMessage());
            return $text; // Fallback to original
        }
    }
}
