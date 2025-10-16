<?php

namespace App\Http\Csp;

use Spatie\Csp\Policies\Basic;

class FirebasePolicy extends Basic
{
    public function configure()
    {
        parent::configure();

        $this->addDirective('script-src', ['self', 'https://www.gstatic.com', 'https://www.googleapis.com', 'https://cdn.jsdelivr.net']);
        $this->addDirective('connect-src', ['self', 'https://www.gstatic.com', 'https://*.firebaseio.com', 'https://*.googleapis.com', 'https://*.firebaseapp.com']);
        $this->addDirective('img-src', ['self', 'data:', 'https://www.gstatic.com']);
        $this->addDirective('style-src', ['self', 'unsafe-inline', 'https://fonts.googleapis.com']);
        $this->addDirective('font-src', ['self', 'data:', 'https://fonts.gstatic.com']);
        $this->addDirective('frame-src', ['self', 'https://*.firebaseapp.com']);
    }
}
