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
        if (config('cloudinary.cloud_url') || env('CLOUDINARY_URL')) {
            try {
                $result = cloudinary()->uploadApi()->upload($file->getRealPath(), [
                    'folder' => $directory,
                ]);

                return new ImageResult(
                    path: $result['secure_url'],
                    publicId: $result['public_id'],
                );
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed, falling back to local: ' . $e->getMessage());
            }
        }

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

            return new ImageResult(path: $path);
        } catch (\Exception $e) {
            $path = $file->store($directory, $disk);
            return new ImageResult(path: $path);
        }
    }
}
