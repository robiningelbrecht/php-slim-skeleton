<?php

use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

$container = require_once __DIR__ . '/container.php';
return $container->get(App::class);