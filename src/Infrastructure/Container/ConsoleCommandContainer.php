<?php

namespace App\Infrastructure\Container;

use App\Infrastructure\Attribute\AsConsoleCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

class ConsoleCommandContainer
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Finder $finder,
    )
    {
    }

    public function getApplication(): Application
    {
        $application = new Application();
        $settings = $this->container->get(Settings::class);

        $this->finder->files()->in($settings->get('console.dirs'))->name('*.php');

        foreach ($this->finder as $file) {
            $class = trim(str_replace(APP_ROOT . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR, '', $file->getRealPath()));
            $class = 'App\\' . str_replace(
                    [DIRECTORY_SEPARATOR, '.php'],
                    ['\\', ''],
                    $class
                );

            $reflection = new \ReflectionClass($class);
            if (!$classAttributes = $reflection->getAttributes()) {
                continue;
            }
            if (!($classAttributes[0]->newInstance() instanceof AsConsoleCommand)) {
                continue;
            }

            $application->add($this->container->get($class));
        }

        return $application;
    }
}