<?php

use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    // Set default route strategy.
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
};
