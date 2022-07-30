<?php

use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\HttpErrorHandler;
use App\Infrastructure\Exception\ShutdownHandler;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;

return function (App $app) {
    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();

    $serverRequestCreator = ServerRequestCreatorFactory::create();
    $request = $serverRequestCreator->createServerRequestFromGlobals();

    $settings = $app->getContainer()->get(Settings::class);

    $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
    $shutdownHandler = new ShutdownHandler($request, $errorHandler, $settings->get('slim.displayErrorDetails'));
    register_shutdown_function($shutdownHandler);

    // Add Error Handling Middleware
    $errorMiddleware = $app->addErrorMiddleware(
        $settings->get('slim.displayErrorDetails'),
        $settings->get('slim.logErrors'),
        $settings->get('slim.logErrorDetails'),
    );
    $errorMiddleware->setDefaultErrorHandler($errorHandler);

};