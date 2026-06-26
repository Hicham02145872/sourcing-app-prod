<?php

namespace Tests\Unit;

use App\Services\ImageResult;
use Tests\TestCase;

class ImageResultTest extends TestCase
{
    public function test_creates_with_local_path()
    {
        $result = new ImageResult(path: 'product_images/test.jpg');

        $this->assertEquals('product_images/test.jpg', $result->path);
        $this->assertNull($result->publicId);
        $this->assertFalse($result->isCloudinary());
    }

    public function test_creates_with_cloudinary_url()
    {
        $url = 'https://res.cloudinary.com/dnkp4jrup/image/upload/v1/test.jpg';
        $result = new ImageResult(path: $url, publicId: 'test/public-id');

        $this->assertEquals($url, $result->path);
        $this->assertEquals('test/public-id', $result->publicId);
        $this->assertTrue($result->isCloudinary());
    }
}
