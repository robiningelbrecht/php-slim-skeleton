<?php

use App\Controller\ChooseCoolestPokemonRequestHandler;
use App\Controller\ResultRequestHandler;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    // Set default route strategy.
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app
        ->get('/[{previousPokemonUpvotedId}/{previousPokemonNotUpvotedId}]', ChooseCoolestPokemonRequestHandler::class.':handle')
        ->setName('index');
    $app
        ->get('/results', ResultRequestHandler::class.':handle')
        ->setName('results');
};
