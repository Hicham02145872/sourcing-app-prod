<?php

namespace App\Notifications\Concerns;

use App\Models\User;

trait UsesNotifiableLocaleRoutes
{
    protected function localizedClientRoute(object $notifiable, string $routeName, array $params = []): string
    {
        $locale = User::normalizeUrlLocale($notifiable->preferred_locale ?? null);

        if (isset($params['sourcingRequest']) && ! isset($params['sourcing_request'])) {
            $params['sourcing_request'] = $params['sourcingRequest'];
            unset($params['sourcingRequest']);
        }

        return route($routeName, array_merge(['locale' => $locale], $params));
    }
}

