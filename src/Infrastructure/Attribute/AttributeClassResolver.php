<?php

namespace App\Infrastructure\Attribute;

use App\Infrastructure\Environment\Settings;
use Symfony\Component\Finder\Finder;

class AttributeClassResolver
{

    public function __construct(
        private Finder $finder
    )
    {
    }

    public function resolve(string $attributeClassName): array
    {
        $appRoot = Settings::getAppRoot();
        $this->finder->files()->in($appRoot . '/src')->name('*.php');

        $classes = [];
        foreach ($this->finder as $file) {
            $class = trim(str_replace($appRoot . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR, '', $file->getRealPath()));
            $class = 'App\\' . str_replace(
                    [DIRECTORY_SEPARATOR, '.php'],
                    ['\\', ''],
                    $class
                );

            $reflection = new \ReflectionClass($class);
            if (!$classAttributes = $reflection->getAttributes()) {
                continue;
            }
            if (!($classAttributes[0]->newInstance() instanceof $attributeClassName)) {
                continue;
            }

            $classes[] = $class;
        }

        return $classes;
    }
}