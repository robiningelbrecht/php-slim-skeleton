<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\ContainerFactory;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;

/** @var \DI\Container $container */
$container = ContainerFactory::create();

return DependencyFactory::fromEntityManager(
    new ConfigurationArray($container->get('settings')['doctrine']['migrations']),
    new ExistingEntityManager($container->get(EntityManager::class))
);
