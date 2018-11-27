<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        Schema::enableForeignKeyConstraints();

        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });   
    }
}
