<?php

namespace App\Infrastructure\Attribute;

use App\Infrastructure\Environment\Settings;
use Symfony\Component\Finder\Finder;

class AttributeClassResolver
{

    public function __construct(
        private readonly Settings $settings,
        private readonly Finder $finder,
    )
    {
    }

    public function resolve(string $attributeClassName, array $restrictToDirectories = []): array
    {
        $appRoot = Settings::getAppRoot();
        $searchInDirectories = array_map(
            fn(string $dir) => $appRoot . '/' . $dir,
            $restrictToDirectories ?: ['src']
        );

        $this->finder->files()->in($searchInDirectories)->name('*.php');

        // @TODO: need to find a more efficient way to fetch tagged classes.
        // @TODO: maybe introduce caching?
        // $settings->get('slim.cache_dir').'/compiler-passes.
        $classes = [];
        foreach ($this->finder as $file) {
            $class = trim(str_replace($appRoot . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR, '', $file->getRealPath()));
            $class = 'App\\' . str_replace(
                    [DIRECTORY_SEPARATOR, '.php'],
                    ['\\', ''],
                    $class
                );

            if (!(new \ReflectionClass($class))->getAttributes($attributeClassName)) {
                // Class is not tagged with attribute.
                continue;
            }

            $classes[] = $class;
        }

        return $classes;
    }
}