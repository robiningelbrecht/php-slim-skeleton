<?php

use App\Controller\ChooseCoolestPokemonController;
use App\Controller\ResultController;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    // Set default route strategy.
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app
        ->get('/[{previousPokemonUpvotedId}/{previousPokemonNotUpvotedId}]', ChooseCoolestPokemonController::class . ':handle')
        ->setName('index');
    $app
        ->get('/results', ResultController::class . ':handle')
        ->setName('results');
};