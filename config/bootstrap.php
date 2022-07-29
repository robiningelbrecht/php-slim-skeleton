<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\ContainerFactory;
use Slim\App;

$container = ContainerFactory::create();
return $container->get(App::class);