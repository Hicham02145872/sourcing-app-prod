<?php

namespace App\Notifications\Concerns;

use App\Models\User;

trait UsesNotifiableLocaleRoutes
{
    protected function localizedClientRoute(object $notifiable, string $routeName, array $params = []): string
    {
        $locale = User::normalizeUrlLocale($notifiable->preferred_locale ?? null);

        return route($routeName, array_merge(['locale' => $locale], $params));
    }
}

