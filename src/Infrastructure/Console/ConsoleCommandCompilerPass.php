<?php

namespace App\Infrastructure\Console;

use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use Symfony\Component\Console\Attribute\AsCommand;

class ConsoleCommandCompilerPass implements CompilerPass
{

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ConsoleCommandFactory::class);
        foreach ($container->findTaggedWithAttributeServiceIds(AsCommand::class, 'src/Console') as $class) {
            $definition->method('registerConsoleCommand', \DI\get($class));
        }

        $container->addDefinitions(
            [ConsoleCommandFactory::class => $definition],
        );
    }
}