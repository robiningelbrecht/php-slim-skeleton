<?php

namespace App\Infrastructure\Attribute;

use App\Infrastructure\Environment\Settings;
use Symfony\Component\Finder\Finder;

class ClassAttributeResolver
{

    public function __construct(
        private readonly Finder $finder,
    )
    {
    }

    public function resolve(
        string $attributeClassName,
        array $restrictToDirectories = [],
        ?string $classAttributeCacheDir = null): array
    {
        $appRoot = Settings::getAppRoot();

        if ($classAttributeCacheDir) {
            $cache = new ClassAttributeCache($attributeClassName, $classAttributeCacheDir);
            if (!$cache->exists()) {
                return require $cache->compile(
                    $this->searchForClasses($attributeClassName, $restrictToDirectories)
                );
            }

            return require $cache->get();
        }

        return $this->searchForClasses($attributeClassName, $restrictToDirectories);
    }

    private function searchForClasses(
        string $attributeClassName,
        array $restrictToDirectories = [],
    ): array
    {
        $appRoot = Settings::getAppRoot();
        $searchInDirectories = array_map(
            fn(string $dir) => $appRoot . '/' . $dir,
            $restrictToDirectories ?: ['src']
        );

        $this->finder->files()->in($searchInDirectories)->name('*.php');

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