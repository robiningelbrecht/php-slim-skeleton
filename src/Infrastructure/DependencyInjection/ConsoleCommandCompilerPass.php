<?php

namespace App\Infrastructure\DependencyInjection;

use App\Console\ConsoleCommandFactory;
use App\Infrastructure\Attribute\AsConsoleCommand;
use App\Infrastructure\Attribute\AttributeClassResolver;

class ConsoleCommandCompilerPass implements CompilerPass
{
    public function __construct(
        private AttributeClassResolver $attributeClassResolver
    )
    {
    }

    public function process(ContainerBuilder $container)
    {
        $definition = \DI\create(ConsoleCommandFactory::class);
        foreach ($this->attributeClassResolver->resolve(AsConsoleCommand::class) as $class) {
            $definition->method('registerConsoleCommand', \DI\get($class));
        }

        $container->addDefinitions(
            [ConsoleCommandFactory::class => $definition],
        );
    }
}