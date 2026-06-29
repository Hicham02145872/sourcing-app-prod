<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageProcessingService
{
    public function compressAndStore(UploadedFile $file, string $directory, string $disk = 'public', int $maxWidth = 1200, int $quality = 75): ImageResult
    {
        $mimeType = $file->getMimeType();
        $isImage = str_starts_with($mimeType, 'image/') && ! str_contains($mimeType, 'svg');

        if (! $isImage) {
            $path = $file->store($directory, $disk);
            return new ImageResult(path: $path);
        }

        try {
            $image = Image::read($file);

            $image->scale(width: $maxWidth);

            $filename = pathinfo($file->hashName(), PATHINFO_FILENAME).'.jpg';
            $path = $directory.'/'.$filename;

            $encoded = $image->toJpeg($quality);

            Storage::disk($disk)->put($path, (string) $encoded);

            $publicId = null;
            if ($this->cloudinaryConfigured()) {
                $publicId = $this->uploadToCloudinary($path, $disk);
            }

            return new ImageResult(path: $path, publicId: $publicId);
        } catch (\Exception $e) {
            $path = $file->store($directory, $disk);
            return new ImageResult(path: $path);
        }
    }

    private function cloudinaryConfigured(): bool
    {
        return ! empty(config('filesystems.disks.cloudinary.url'));
    }

    private function uploadToCloudinary(string $path, string $disk): ?string
    {
        try {
            $info = pathinfo($path);
            $dirname = str_replace('\\', '/', $info['dirname'] ?? '');
            $dirname = $dirname === '.' ? '' : $dirname;
            $publicId = $dirname ? $dirname.'/'.$info['filename'] : $info['filename'];

            cloudinary()->uploadApi()->upload(
                Storage::disk($disk)->path($path),
                ['public_id' => $publicId, 'overwrite' => true]
            );

            return $publicId;
        } catch (\Exception $e) {
            Log::warning('Cloudinary upload failed, falling back to local disk', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
