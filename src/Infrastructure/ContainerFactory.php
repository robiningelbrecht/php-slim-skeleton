<?php

namespace App\Infrastructure;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        if (Environment::PRODUCTION === Environment::from($_ENV['ENVIRONMENT'])) {
            // Compile and cache container.
            $containerBuilder->enableCompilation(__DIR__ . '/../../var/cache');
        }
        $containerBuilder->addDefinitions(__DIR__ . '/../../config/container.php');

        return $containerBuilder->build();
    }
}