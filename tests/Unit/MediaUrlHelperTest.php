<?php

namespace Tests\Unit;

use Tests\TestCase;

class MediaUrlHelperTest extends TestCase
{
    public function test_returns_empty_string_for_null_or_empty()
    {
        $this->assertEquals('', media_url(null));
        $this->assertEquals('', media_url(''));
        $this->assertEquals('', media_url(null, ['width' => 300]));
    }

    public function test_returns_asset_url_for_local_path()
    {
        $result = media_url('product_images/test.jpg');
        $this->assertStringContainsString('storage/product_images/test.jpg', $result);
    }

    public function test_returns_external_url_as_is_when_not_cloudinary()
    {
        $url = 'https://example.com/image.jpg';
        $this->assertEquals($url, media_url($url));
    }

    public function test_adds_default_transformations_to_cloudinary_url()
    {
        $url = 'https://res.cloudinary.com/dnkp4jrup/image/upload/v1234567890/test.jpg';
        $result = media_url($url);

        $this->assertStringContainsString('f_auto,q_auto', $result);
        $this->assertStringContainsString('upload/f_auto,q_auto/', $result);
    }

    public function test_adds_custom_transformations_to_cloudinary_url()
    {
        $url = 'https://res.cloudinary.com/dnkp4jrup/image/upload/v1234567890/test.jpg';
        $result = media_url($url, ['width' => 300, 'height' => 200, 'crop' => 'fill']);

        $this->assertStringContainsString('w_300', $result);
        $this->assertStringContainsString('h_200', $result);
        $this->assertStringContainsString('c_fill', $result);
    }

    public function test_skips_transformations_for_local_path()
    {
        $result = media_url('product_images/test.jpg', ['width' => 300]);
        $this->assertStringContainsString('storage/product_images/test.jpg', $result);
        $this->assertStringNotContainsString('w_300', $result);
    }

    public function test_empty_transformations_still_applies_defaults()
    {
        $url = 'https://res.cloudinary.com/dnkp4jrup/image/upload/v1234567890/test.jpg';
        $result = media_url($url, []);

        $this->assertStringContainsString('f_auto,q_auto', $result);
    }

    public function test_overrides_default_quality_with_custom()
    {
        $url = 'https://res.cloudinary.com/dnkp4jrup/image/upload/v1234567890/test.jpg';
        $result = media_url($url, ['quality' => 'auto:best']);

        $this->assertStringContainsString('q_auto:best', $result);
    }
}
