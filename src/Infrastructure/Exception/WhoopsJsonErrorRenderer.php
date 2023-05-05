<?php

namespace App\Infrastructure\Exception;

use Slim\Interfaces\ErrorRendererInterface;

class WhoopsJsonErrorRenderer implements ErrorRendererInterface
{
    public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
    {
        return WhoopsBuilder::fromJsonDefaults()->build()->handleException($exception);
    }
}
