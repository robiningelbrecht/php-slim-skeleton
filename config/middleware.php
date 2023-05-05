<?php

use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\DefaultHtmlErrorRenderer;
use App\Infrastructure\Exception\WhoopsHtmlErrorRenderer;
use App\Infrastructure\Exception\WhoopsJsonErrorRenderer;
use Slim\App;

return function (App $app) {
    /** @var Settings $settings */
    $settings = $app->getContainer()->get(Settings::class);

    $errorMiddleware = $app->addErrorMiddleware(
        $settings->get('slim.displayErrorDetails'),
        $settings->get('slim.logErrors'),
        $settings->get('slim.logErrorDetails'),
    );

    /** @var \Slim\Handlers\ErrorHandler $errorHandler */
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    if (!$settings->get('slim.whoops.enabled')) {
        $errorHandler->registerErrorRenderer('text/html', DefaultHtmlErrorRenderer::class);
        $errorHandler->setDefaultErrorRenderer('text/html', DefaultHtmlErrorRenderer::class);

        return;
    }

    $errorHandler->registerErrorRenderer('text/html', WhoopsHtmlErrorRenderer::class);
    $errorHandler->registerErrorRenderer('application/json', WhoopsJsonErrorRenderer::class);
    $errorHandler->setDefaultErrorRenderer('text/html', WhoopsHtmlErrorRenderer::class);
};
