<?php

use App\Infrastructure\Container;
use App\Controller\ChooseCoolestPokemonController;
use App\Controller\ResultController;
use App\Infrastructure\Exception\HttpErrorHandler;
use App\Infrastructure\Exception\ShutdownHandler;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

AppFactory::setContainer(Container::build());
$app = appFactory::create();

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
$shutdownHandler = new ShutdownHandler($request, $errorHandler, true);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Handling Middleware
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Set default route strategy.
$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

// Register routes.
$app
    ->get('/[{previousPokemonUpvotedUuid}/{previousPokemonNotUpvotedUuid}]', ChooseCoolestPokemonController::class . ':handle')
    ->setName('index');
$app
    ->get('/results', ResultController::class . ':handle')
    ->setName('results');

$app->run();