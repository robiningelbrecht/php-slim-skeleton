<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Console\Application;

const APP_ROOT = __DIR__ . '/..';

return [
    // Twig Environment.
    Environment::class => new Environment(new FilesystemLoader(APP_ROOT . '/templates')),
    // Doctrine Dbal.
    Connection::class => static function (ContainerInterface $c): Connection {
        $settings = $c->get('settings');

        return DriverManager::getConnection($settings['doctrine']['connection']);
    },
    // Doctrine EntityManager.
    EntityManager::class => static function (ContainerInterface $c): EntityManager {
        $settings = $c->get('settings');

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings['doctrine']['metadata_dirs'],
            $settings['doctrine']['dev_mode'],
        );

        return EntityManager::create($settings['doctrine']['connection'], $config);
    },
    // Console application.
    Application::class => function (ContainerInterface $container) {
        $application = new Application();

        foreach ($container->get('settings')['commands'] as $class) {
            $application->add($container->get($class));
        }

        return $application;
    },
    'settings' => function () {
        return require APP_ROOT . '/config/settings.php';
    },
];