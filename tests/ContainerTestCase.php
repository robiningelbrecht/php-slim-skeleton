<?php

namespace App\Tests;

use App\Infrastructure\DependencyInjection\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class ContainerTestCase extends TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = ContainerFactory::create();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
