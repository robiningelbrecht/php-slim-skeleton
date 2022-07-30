<?php

use App\Infrastructure\Environment\Environment;
use App\Infrastructure\Environment\Settings;
use App\Console\ConsoleCommandFactory;
use Dotenv\Dotenv;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Console\Application;

$appRoot = Settings::getAppRoot();

$dotenv = Dotenv::createImmutable($appRoot);
$dotenv->load();

return [
    // Twig Environment.
    FilesystemLoader::class => DI\create(FilesystemLoader::class)->constructor($appRoot . '/templates'),
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
    Application::class => function (ConsoleCommandFactory $consoleCommandFactory) {
        $application = new Application();
        foreach ($consoleCommandFactory->getConsoleCommands() as $command) {
            $application->add($command);
        }

        return $application;
    },
    // Environment.
    Environment::class => function () {
        return Environment::from($_ENV['ENVIRONMENT']);
    },
    // Settings.
    Settings::class => DI\factory([Settings::class, 'fromArray'])
        ->parameter('settings', require $appRoot . '/config/settings.php'),
];