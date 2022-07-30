<?php

use App\Infrastructure\Container\Settings;
use Dotenv\Dotenv;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

const APP_ROOT = __DIR__ . '/..';

$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

return [
    // Twig Environment.
    FilesystemLoader::class => DI\create(FilesystemLoader::class)->constructor(APP_ROOT . '/templates'),
    Environment::class => DI\create(Environment::class)->constructor(DI\get(FilesystemLoader::class)),
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
    // Console application.
    Application::class => function (ContainerInterface $container) {
        $application = new Application();
        $settings = $container->get(Settings::class);

        // Auto discover and register all console commands.
        foreach ($settings->get('console.dirs') as $directory) {
            $finder = new Finder();
            $finder->files()->in($directory)->name('*Command.php');

            foreach ($finder as $file) {
                if (!preg_match('/namespace[\s]+(?<namespace>[A-Za-z0-9\\\\]+?)[\s]*;/sm', $file->getContents(), $matches)) {
                    continue;
                }
                $application->add($container->get($matches['namespace'] . '\\' . str_replace('.php', '', $file->getFilename())));
            }
        }

        return $application;
    },
    // Environment.
    \App\Infrastructure\Environment::class => function () {
        return \App\Infrastructure\Environment::from($_ENV['ENVIRONMENT']);
    },
    // Settings.
    Settings::class => DI\factory([Settings::class, 'fromArray'])
        ->parameter('settings', require APP_ROOT . '/config/settings.php'),
];