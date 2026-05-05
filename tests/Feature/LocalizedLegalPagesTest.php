<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizedLegalPagesTest extends TestCase
{
    public function test_localized_legal_pages_are_accessible(): void
    {
        $this->get('/eng/privacy-policy')->assertOk();
        $this->get('/fr/terms-of-service')->assertOk();
        $this->get('/ar/refund-policy')->assertOk();
        $this->get('/eng/shipping-policy')->assertOk();
    }

    public function test_legacy_legal_urls_redirect_to_localized_paths(): void
    {
        $this->get('/privacy-policy')->assertRedirect('/eng/privacy-policy');
        $this->get('/terms-of-service')->assertRedirect('/eng/terms-of-service');
        $this->get('/refund-policy')->assertRedirect('/eng/refund-policy');
        $this->get('/shipping-policy')->assertRedirect('/eng/shipping-policy');
    }
}
