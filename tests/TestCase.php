<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Support\Facades\Schema;
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
    }
}
