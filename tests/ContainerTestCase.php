<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class ContainerTestCase extends TestCase
{
    use ProvideContainer;

    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->bootContainer();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
