<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\InvalidScopeException;
use Tests\TestCase;

class InvalidScopeExceptionTest extends TestCase
{
    public function test_exception()
    {
        $exception = new InvalidScopeException();

        $this->expectException(InvalidScopeException::class);
        $this->expectExceptionMessage('You don\'t have the required scope to access this route.');
        $this->assertEquals($exception->getStatusCode(), 401);

        throw $exception;
    }
}
