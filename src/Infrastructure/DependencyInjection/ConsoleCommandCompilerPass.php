<?php

namespace App\Infrastructure\DependencyInjection;

use App\Console\ConsoleCommandFactory;
use App\Infrastructure\Attribute\AsConsoleCommand;

class ConsoleCommandCompilerPass implements CompilerPass
{

    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(ConsoleCommandFactory::class);
        foreach ($container->findTaggedWithAttributeServiceIds(AsConsoleCommand::class) as $class) {
            $definition->method('registerConsoleCommand', \DI\get($class));
        }

        $container->addDefinitions(
            [ConsoleCommandFactory::class => $definition],
        );
    }
}