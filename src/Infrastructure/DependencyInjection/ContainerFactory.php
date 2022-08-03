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

        $dotenv = Dotenv::createImmutable($appRoot);
        $dotenv->load();

        // At this point the container has not been built. We need to load the settings manually.
        $settings = Settings::fromArray(require $appRoot . '/config/settings.php');
        $containerBuilder = ContainerBuilder::create($settings);

        if (Environment::PRODUCTION === Environment::from($_ENV['ENVIRONMENT'])) {
            // Compile and cache container.
            $containerBuilder->enableCompilation($settings->get('slim.cache_dir') . '/container');
        }
        $containerBuilder->addDefinitions($appRoot . '/config/container.php');
        $containerBuilder->addCompilerPasses(...require $appRoot . '/config/compiler-passes.php');
        return $containerBuilder->build();
    }
}