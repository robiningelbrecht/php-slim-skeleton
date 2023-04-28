<?php

namespace App\Infrastructure\Exception;

use App\Infrastructure\Environment\Settings;
use Slim\Interfaces\ErrorRendererInterface;

readonly class WhoopsHtmlErrorRenderer implements ErrorRendererInterface
{
    public function __construct(
        private readonly Settings $settings
    ) {
    }

    public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
    {
        return WhoopsBuilder::fromHtmlDefaults()
            ->withEditor($this->settings->get('slim.whoops.editor'))
            ->build()
            ->handleException($exception);
    }
}
