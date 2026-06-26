<?php

if (! function_exists('media_url')) {
    function media_url(?string $path, array $transformations = []): string
    {
        if (empty($path)) {
            return '';
        }

        if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
            return asset('storage/' . $path);
        }

        if (!str_contains($path, 'cloudinary.com')) {
            return $path;
        }

        $defaults = ['f_auto', 'q_auto'];
        $segments = $defaults;

        if (!empty($transformations)) {
            $map = [
                'width' => fn ($v) => "w_{$v}",
                'height' => fn ($v) => "h_{$v}",
                'crop' => fn ($v) => "c_{$v}",
                'quality' => fn ($v) => "q_{$v}",
                'fetch_format' => fn ($v) => "f_{$v}",
            ];
            foreach ($map as $key => $fn) {
                if (isset($transformations[$key])) {
                    $segments[] = $fn($transformations[$key]);
                }
            }
        }

        $segments = array_unique($segments);
        $transformStr = implode(',', $segments) . '/';

        $uploadPos = strpos($path, '/upload/');
        if ($uploadPos !== false) {
            return substr_replace($path, '/upload/' . $transformStr, $uploadPos, strlen('/upload/'));
        }

        return $path;
    }
}
