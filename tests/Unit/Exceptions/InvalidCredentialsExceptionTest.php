<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\InvalidCredentialsException;
use Tests\TestCase;

class InvalidCredentialsExceptionTest extends TestCase
{
    public function test_exception()
    {
        $exception = new InvalidCredentialsException();

        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('The provided credentials do not match our records.');
        $this->assertEquals($exception->getStatusCode(), 401);

        throw $exception;
    }
}
