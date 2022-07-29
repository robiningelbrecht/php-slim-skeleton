<?php

use DI\Container;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

const APP_ROOT = __DIR__;

$settings = require APP_ROOT . '/config/settings.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(array_merge([
    Environment::class => new Environment(new FilesystemLoader(APP_ROOT . '/templates')),
    Connection::class => static function (Container $c): Connection {
        $settings = $c->get('settings');

        return DriverManager::getConnection($settings['doctrine']['connection']);
    },
    EntityManager::class => static function (Container $c): EntityManager {
        $settings = $c->get('settings');

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings['doctrine']['metadata_dirs'],
            $settings['doctrine']['dev_mode'],
        );

        return EntityManager::create($settings['doctrine']['connection'], $config);
    },
], $settings));

return $builder->build();