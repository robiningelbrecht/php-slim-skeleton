<?php

namespace App\Infrastructure\DependencyInjection;

use DI\CompiledContainer;
use DI\Container;

class ContainerBuilder
{
    /** @var CompilerPass[] */
    private array $passes;

    private function __construct(
        private \DI\ContainerBuilder $containerBuilder
    )
    {
    }

    public function addDefinitions(...$definitions): self
    {
        $this->containerBuilder->addDefinitions(...$definitions);

        return $this;
    }

    public function enableCompilation(
        string $directory,
        string $containerClass = 'CompiledContainer',
        string $containerParentClass = CompiledContainer::class
    ): self
    {
        $this->containerBuilder->enableCompilation(
            $directory,
            $containerClass,
            $containerParentClass,
        );

        return $this;
    }

    public function addCompilerPass(CompilerPass $pass): self
    {
        $this->passes[] = $pass;

        return $this;
    }

    public function build(): Container
    {
        foreach ($this->passes as $pass) {
            $pass->process($this);
        }
        return $this->containerBuilder->build();
    }

    public static function create(): self
    {
        return new self(new \DI\ContainerBuilder());
    }
}