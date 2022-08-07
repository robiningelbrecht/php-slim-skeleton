<?php

namespace App\Tests;

use App\Infrastructure\DependencyInjection\ContainerFactory;
use Psr\Container\ContainerInterface;

trait ProvideContainer
{
    private static ?ContainerInterface $testContainer = null;

    public function bootContainer(): ContainerInterface
    {
        if (!self::$testContainer) {
            self::$testContainer = ContainerFactory::createForTestSuite();
        }

        return self::$testContainer;
    }
}
