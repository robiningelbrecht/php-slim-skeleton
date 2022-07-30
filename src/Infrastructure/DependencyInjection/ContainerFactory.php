<?php

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Attribute\AttributeClassResolver;
use App\Infrastructure\Environment\Environment;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        $containerBuilder = ContainerBuilder::create();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
        $dotenv->load();

        if (Environment::PRODUCTION === Environment::from($_ENV['ENVIRONMENT'])) {
            // Compile and cache container.
            $containerBuilder->enableCompilation(__DIR__ . '/../../../var/cache');
        }
        $containerBuilder->addDefinitions(__DIR__ . '/../../../config/container.php');
        $containerBuilder->addCompilerPass(new ConsoleCommandCompilerPass(new AttributeClassResolver()));

        return $containerBuilder->build();
    }
}