<?php

use App\Infrastructure\Environment;

return [
    'slim' => [
        // Returns a detailed HTML page with error details and
        // a stack trace. Should be disabled in production.
        'displayErrorDetails' => $_ENV['DISPLAY_ERROR_DETAILS'],
        // Whether to display errors on the internal PHP log or not.
        'logErrors' =>  $_ENV['LOG_ERRORS'],
        // If true, display full errors with message and stack trace on the PHP log.
        // If false, display only "Slim Application Error" on the PHP log.
        // Doesn't do anything when 'logErrors' is false.
        'logErrorDetails' => $_ENV['LOG_ERROR_DETAILS'],
    ],
    'doctrine' => [
        // Enables or disables Doctrine metadata caching
        // for either performance or convenience during development.
        'dev_mode' => Environment::from($_ENV['ENVIRONMENT']) !== Environment::PRODUCTION,
        // Path where Doctrine will cache the processed metadata
        // when 'dev_mode' is false.
        'cache_dir' => APP_ROOT . '/var/doctrine',
        // List of paths where Doctrine will search for metadata.
        // Metadata can be either YML/XML files or PHP classes annotated
        // with comments or PHP8 attributes.
        'metadata_dirs' => [APP_ROOT . '/src/Domain'],
        // The parameters Doctrine needs to connect to your database.
        // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
        // needs a 'path' parameter and doesn't use most of the ones shown in this example).
        // Refer to the Doctrine documentation to see the full list
        // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
        'connection' => [
            'driver' => $_ENV['DATABASE_DRIVER'],
            'host' =>  $_ENV['DATABASE_HOST'],
            'port' =>  $_ENV['DATABASE_PORT'],
            'dbname' => $_ENV['DATABASE_NAME'],
            'user' =>  $_ENV['DATABASE_USER'],
            'password' =>  $_ENV['DATABASE_PASSWORD'],
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
                'App\Migrations' => APP_ROOT . '/migrations',
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