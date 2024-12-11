<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $disableCSRF = true;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->disableCSRF) {
            $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        }
    }
}
