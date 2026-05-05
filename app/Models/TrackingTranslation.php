<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingTranslation extends Model
{
    protected $fillable = [
        'provider',
        'source_text',
        'translated_text',
        'source_hash',
    ];

    public static function getCached(string $provider, string $text): ?string
    {
        $hash = sha1($provider . $text);
        return static::where('source_hash', $hash)->value('translated_text');
    }

    public static function cache(string $provider, string $source, string $translation): void
    {
        $hash = sha1($provider . $source);
        static::updateOrCreate(
            ['source_hash' => $hash],
            [
                'provider' => $provider,
                'source_text' => $source,
                'translated_text' => $translation
            ]
        );
    }
}
