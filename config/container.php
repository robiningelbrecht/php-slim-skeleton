<?php

use App\Infrastructure\Container\Environment;
use App\Infrastructure\Container\Settings;
use App\Infrastructure\Container\ConsoleCommandApplication;
use Dotenv\Dotenv;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Console\Application;

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

return [
    // Twig Environment.
    FilesystemLoader::class => DI\create(FilesystemLoader::class)->constructor(APP_ROOT . '/templates'),
    TwigEnvironment::class => DI\create(TwigEnvironment::class)->constructor(DI\get(FilesystemLoader::class)),
    // Doctrine Dbal.
    Connection::class => function (Settings $settings): Connection {
        return DriverManager::getConnection($settings->get('doctrine.connection'));
    },
    // Doctrine EntityManager.
    EntityManager::class => function (Settings $settings): EntityManager {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings->get('doctrine.metadata_dirs'),
            $settings->get('doctrine.dev_mode'),
        );

        return EntityManager::create($settings->get('doctrine.connection'), $config);
    },
    // Auto discover and register all console commands.
    Application::class => function (ConsoleCommandApplication $consoleCommandContainer) {
        return $consoleCommandContainer->getApplication();
    },
    // Environment.
    Environment::class => function () {
        return Environment::from($_ENV['ENVIRONMENT']);
    },
    // Settings.
    Settings::class => DI\factory([Settings::class, 'fromArray'])
        ->parameter('settings', require APP_ROOT . '/config/settings.php'),
];