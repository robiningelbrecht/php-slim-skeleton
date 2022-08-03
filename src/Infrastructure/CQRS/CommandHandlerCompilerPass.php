<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;

class CommandHandlerCompilerPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(CommandBus::class);
        foreach ($container->findTaggedWithAttributeIds(AsCommandHandler::class) as $class) {
            $definition->method('subscribe', \DI\get($class));
        }

        $container->addDefinitions(
            [CommandBus::class => $definition],
        );
    }

}