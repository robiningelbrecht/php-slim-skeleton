<?php

namespace App\Infrastructure\Exception;

use Slim\Interfaces\ErrorRendererInterface;

readonly class WhoopsHtmlErrorRenderer implements ErrorRendererInterface
{
    public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
    {
        return WhoopsBuilder::fromHtmlDefaults()->build()->handleException($exception);
    }
}
