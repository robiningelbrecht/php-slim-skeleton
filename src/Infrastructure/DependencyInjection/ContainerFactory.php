<?php

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Environment\Environment;
use App\Infrastructure\Environment\Settings;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        $appRoot = Settings::getAppRoot();
        $containerBuilder = ContainerBuilder::create();

        $dotenv = Dotenv::createImmutable($appRoot);
        $dotenv->load();

        if (Environment::PRODUCTION === Environment::from($_ENV['ENVIRONMENT'])) {
            // Compile and cache container.
            $containerBuilder->enableCompilation($appRoot . '/var/cache');
        }
        $containerBuilder->addDefinitions($appRoot . '/config/container.php');
        $containerBuilder->addCompilerPasses(...require $appRoot . '/config/compiler-passes.php');
        return $containerBuilder->build();
    }
}