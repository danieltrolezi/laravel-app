<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExceptionHandler
{
    public function __construct(
        private Exceptions $exceptions
    ) {
        $this->reportException();
        $this->renderExceptionAsJson();
        $this->renderHttpException();
    }

    private function reportException(): void
    {
        $this->exceptions->report(function (Exception $e) {
            Log::error($e->getMessage(), [
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString()
            ]);
        })->stop();
    }

    private function renderExceptionAsJson(): void
    {
        $this->exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });
    }

    private function renderHttpException(): void
    {
        $this->exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $response = [
                    'message' => $e->getMessage()
                ];

                if (config('app.debug')) {
                    $response = array_merge($response, [
                        'exception' => get_class($e),
                        'file'      => $e->getFile(),
                        'line'      => $e->getLine(),
                        'trace'     => $e->getTraceAsString()
                    ]);
                }

                return response()->json($response, $e->getStatusCode());
            }
        });
    }
}
