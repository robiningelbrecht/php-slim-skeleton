<?php

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Attribute\AttributeClassResolver;
use DI\CompiledContainer;
use DI\Container;
use DI\Definition\Helper\DefinitionHelper;
use Symfony\Component\Finder\Finder;

class ContainerBuilder
{
    /** @var CompilerPass[] */
    private array $passes = [];

    private function __construct(
        private readonly \DI\ContainerBuilder $containerBuilder,
        private readonly AttributeClassResolver $attributeClassResolver,
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

    public function addCompilerPasses(CompilerPass ...$compilerPasses): self
    {
        foreach ($compilerPasses as $compilerPass) {
            $this->addCompilerPass($compilerPass);
        }

        return $this;
    }

    public function addCompilerPass(CompilerPass $pass): self
    {
        if (array_key_exists($pass::class, $this->passes)) {
            throw new \RuntimeException(sprintf('CompilerPass %s already added. Cannot add the same pass twice', $pass::class));
        }
        $this->passes[$pass::class] = $pass;

        return $this;
    }

    public function findDefinition(string $id): DefinitionHelper
    {
        return \DI\create($id);
    }

    public function findTaggedWithAttributeServiceIds(string $name, string ...$restrictToDirectories): array
    {
        return $this->attributeClassResolver->resolve($name, $restrictToDirectories);
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
        return new self(
            new \DI\ContainerBuilder(),
            new AttributeClassResolver(new Finder())
        );
    }
}