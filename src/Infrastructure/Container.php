<?php

namespace App\Infrastructure;

use DI\ContainerBuilder;
use SleekDB\Store;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Container
{
    private const SLEEKDB_DIRECTORY = __DIR__.'/../../database';
    private const TWIG_TEMPLATES_DIRECTORY = __DIR__.'/../../templates';

    public static function build(): \DI\Container
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(static::getDefinitions());

        return $builder->build();
    }

    private static function getDefinitions(): array{
        return [
            Environment::class => new Environment(new FilesystemLoader(self::TWIG_TEMPLATES_DIRECTORY)),
            Store::class => new Store('pokemons', self::SLEEKDB_DIRECTORY, [
                'primary_key' => 'id',
                'auto_cache' => false,
                'timeout' => false,
            ]),
        ];
    }
}