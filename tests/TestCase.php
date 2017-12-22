<?php

namespace Test;

use Laravel\Lumen\Testing\TestCase as IlluminateTestCase;

abstract class TestCase extends IlluminateTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
