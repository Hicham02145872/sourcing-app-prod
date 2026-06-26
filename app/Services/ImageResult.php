<?php

namespace App\Services;

class ImageResult
{
    public function __construct(
        public readonly string $path,
        public readonly ?string $publicId = null,
    ) {}

    public function isCloudinary(): bool
    {
        return $this->publicId !== null;
    }
}