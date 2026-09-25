<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // E-mails are sent after the response (App\Support\Mailing::later): in tests, right away.
        $this->withoutDefer();
    }
}
