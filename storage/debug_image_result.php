<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = new App\Services\ImageProcessingService();
$file = Illuminate\Http\UploadedFile::fake()->image('test.jpg', 100, 100);
$result = $service->compressAndStore($file, 'test-uploads', 'local');

echo 'Class: ' . get_class($result) . PHP_EOL;
echo 'Path: ' . var_export($result->path, true) . PHP_EOL;
echo 'publicId: ' . var_export($result->publicId, true) . PHP_EOL;
echo 'isCloudinary: ' . var_export($result->isCloudinary(), true) . PHP_EOL;
