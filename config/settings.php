<?php

use App\Infrastructure\Environment\Environment;
use App\Infrastructure\Environment\Settings;

return [
    'slim' => [
        // Returns a detailed HTML page with error details and
        // a stack trace. Should be disabled in production.
        'displayErrorDetails' => $_ENV['DISPLAY_ERROR_DETAILS'],
        // Whether to display errors on the internal PHP log or not.
        'logErrors' => $_ENV['LOG_ERRORS'],
        // If true, display full errors with message and stack trace on the PHP log.
        // If false, display only "Slim Application Error" on the PHP log.
        // Doesn't do anything when 'logErrors' is false.
        'logErrorDetails' => $_ENV['LOG_ERROR_DETAILS'],
        'whoops' => [
            'enabled' => $_ENV['ENABLE_WHOOPS_ERROR_LOGGING'],
            'editor' => $_ENV['WHOOPS_EDITOR'],
        ],
        // Path where Slim will cache the container, compiler passes, ...
        'cache_dir' => Settings::getAppRoot().'/var/cache/slim',
    ],
    'amqp' => [
        'rabbitmq' => [
            'host' => $_ENV['RABBITMQ_HOST'],
            'port' => $_ENV['RABBITMQ_PORT'],
            'username' => $_ENV['RABBITMQ_USER'],
            'password' => $_ENV['RABBITMQ_PASS'],
            'vhost' => $_ENV['RABBITMQ_VHOST'],
        ],
    ],
    'doctrine' => [
        // Enables or disables Doctrine metadata caching
        // for either performance or convenience during development.
        'dev_mode' => Environment::PRODUCTION !== Environment::from($_ENV['ENVIRONMENT']),
        // Path where Doctrine will cache the processed metadata
        // when 'dev_mode' is false.
        'cache_dir' => Settings::getAppRoot().'/var/cache/doctrine',
        // List of paths where Doctrine will search for metadata.
        // Metadata can be either YML/XML files or PHP classes annotated
        // with comments or PHP8 attributes.
        'metadata_dirs' => [Settings::getAppRoot().'/src/Domain'],
        // The parameters Doctrine needs to connect to your database.
        // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
        // needs a 'path' parameter and doesn't use most of the ones shown in this example).
        // Refer to the Doctrine documentation to see the full list
        // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
        'connection' => [
            'driver' => $_ENV['DATABASE_DRIVER'],
            'host' => $_ENV['DATABASE_HOST'],
            'port' => $_ENV['DATABASE_PORT'],
            'dbname' => $_ENV['DATABASE_NAME'],
            'user' => $_ENV['DATABASE_USER'],
            'password' => $_ENV['DATABASE_PASSWORD'],
        ],
        'migrations' => [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
                'version_column_name' => 'version',
                'version_column_length' => 1024,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],
            'migrations_paths' => [
                'App\Migrations' => Settings::getAppRoot().'/migrations',
            ],
            'all_or_nothing' => true,
            'transactional' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',
            'connection' => null,
            'em' => null,
        ],
    ],
];
