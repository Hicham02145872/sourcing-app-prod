<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageProcessingService
{
    /**
     * Process and compress an uploaded image, or just store it if it's not an image.
     *
     * @param  string  $directory  The directory within the disk
     * @param  string  $disk  The disk to store the file on
     * @return string The stored file path
     */
    public function compressAndStore(UploadedFile $file, string $directory, string $disk = 'public', int $maxWidth = 1200, int $quality = 75): string
    {
        // 1. Check if the file is an image that Intervention can handle
        $mimeType = $file->getMimeType();
        $isImage = str_starts_with($mimeType, 'image/') && ! str_contains($mimeType, 'svg');

        if (! $isImage) {
            return $file->store($directory, $disk);
        }

        try {
            // 2. Read the image
            $image = Image::read($file);

            // 3. Resize while maintaining aspect ratio, only if it's larger than maxWidth
            $image->scale(width: $maxWidth);

            // 4. Generate a unique filename (with .jpg extension)
            $filename = pathinfo($file->hashName(), PATHINFO_FILENAME).'.jpg';
            $path = $directory.'/'.$filename;

            // 5. Encode as JPG for best compression/quality ratio
            $encoded = $image->toJpeg($quality);

            // 6. Save to the specified disk
            Storage::disk($disk)->put($path, (string) $encoded);

            return $path;
        } catch (\Exception $e) {
            // Fallback to normal storage if processing fails
            return $file->store($directory, $disk);
        }
    }
}
