<?php

if (! function_exists('is_image_file')) {
    function is_image_file(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'avif'], true);
    }
}

if (! function_exists('cloudinary_mirror_url')) {
    function cloudinary_mirror_url(string $path): ?string
    {
        if (empty(config('filesystems.disks.cloudinary.url'))) {
            return null;
        }

        try {
            $info = pathinfo($path);
            $dirname = str_replace('\\', '/', $info['dirname'] ?? '');
            $dirname = $dirname === '.' ? '' : $dirname;
            $publicId = $dirname ? $dirname.'/'.$info['filename'] : $info['filename'];

            return (string) cloudinary()->image($publicId)->toUrl();
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path, array $transformations = []): string
    {
        if (empty($path)) {
            return '';
        }

        if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
            static $localCheck = [];

            if (!isset($localCheck[$path])) {
                $localCheck[$path] = \Illuminate\Support\Facades\Storage::disk('public')->exists($path);
            }

            if (!$localCheck[$path]) {
                $mirrorUrl = cloudinary_mirror_url($path);

                if ($mirrorUrl) {
                    return $mirrorUrl;
                }
            }

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
