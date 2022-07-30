<?php

use App\Infrastructure\Exception\HttpErrorHandler;
use App\Infrastructure\Exception\ShutdownHandler;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;

return function (App $app) {
    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();

    $serverRequestCreator = ServerRequestCreatorFactory::create();
    $request = $serverRequestCreator->createServerRequestFromGlobals();

    $slimConfig = $app->getContainer()->get('settings')['slim'];

    $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
    $shutdownHandler = new ShutdownHandler($request, $errorHandler, $slimConfig['displayErrorDetails']);
    register_shutdown_function($shutdownHandler);

    // Add Error Handling Middleware
    $errorMiddleware = $app->addErrorMiddleware(
        $slimConfig['displayErrorDetails'],
        $slimConfig['logErrors'],
        $slimConfig['logErrorDetails']
    );
    $errorMiddleware->setDefaultErrorHandler($errorHandler);

};