<?php

namespace App\Infrastructure\Attribute;

use App\Infrastructure\Settings;
use Symfony\Component\Finder\Finder;

class AttributeClassResolver
{

    public function resolve(string $attributeClassName): array
    {
        $appRoot = Settings::getAppRoot();

        $finder = new Finder();
        $finder->files()->in($appRoot . '/src')->name('*.php');

        $classes = [];
        foreach ($finder as $file) {
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