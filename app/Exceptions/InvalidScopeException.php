<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidScopeException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            statusCode: Response::HTTP_UNAUTHORIZED,
            message: 'You don\'t have the required scope to access this route.'
        );
    }
}
