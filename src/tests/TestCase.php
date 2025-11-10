<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Route::has('logout')) {
            Route::post('/logout', function () {
                return redirect('/');
            })->name('logout');
        }
    }
}
