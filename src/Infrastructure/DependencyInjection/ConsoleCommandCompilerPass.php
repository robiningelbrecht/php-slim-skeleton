<?php

namespace App\Infrastructure\DependencyInjection;

use App\Console\ConsoleCommandFactory;
use Symfony\Component\Console\Attribute\AsCommand;

class ConsoleCommandCompilerPass implements CompilerPass
{

    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(ConsoleCommandFactory::class);
        foreach ($container->findTaggedWithAttributeServiceIds(AsCommand::class, 'src/Console/Command') as $class) {
            $definition->method('registerConsoleCommand', \DI\get($class));
        }

        $container->addDefinitions(
            [ConsoleCommandFactory::class => $definition],
        );
    }
}