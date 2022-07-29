<?php


use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Application;

const APP_ROOT = __DIR__ . '/..';

$builder = new ContainerBuilder();
$builder->addDefinitions([
    Environment::class => new Environment(new FilesystemLoader(APP_ROOT . '/templates')),
    Connection::class => static function (ContainerInterface $c): Connection {
        $settings = $c->get('settings');

        return DriverManager::getConnection($settings['doctrine']['connection']);
    },
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
    // Slim App.
    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Register routes
        (require __DIR__ . '/routes.php')($app);
        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },
    'settings' => function () {
        return require APP_ROOT . '/config/settings.php';
    },
]);

return $builder->build();