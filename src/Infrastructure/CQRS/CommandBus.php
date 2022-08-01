<?php

namespace App\Infrastructure\CQRS;

class CommandBus
{
    private const COMMAND_HANDLER_SUFFIX = 'CommandHandler';

    private array $commandHandlers = [];


    public function dispatch(DomainCommand $command): void
    {
        $this->getHandlerForCommand($command)->handle($command);
    }

    private function getHandlerForCommand(DomainCommand $command): CommandHandler
    {
        return $this->commandHandlers[$command::class] ??
            throw new \RuntimeException(sprintf('CommandHandler for command "%s" not subscribed for this bus', $command::class));
    }

    public function subscribe(CommandHandler $commandHandler): void
    {
        $this->guardThatFqcnEndsInCommandHandler($commandHandler::class);
        $this->guardThatThereIsACorrespondingCommand($commandHandler);

        $commandFqcn = str_replace(self::COMMAND_HANDLER_SUFFIX, '', $commandHandler::class);
        $this->commandHandlers[$commandFqcn] = $commandHandler;
    }

    private function guardThatFqcnEndsInCommandHandler(string $fqcn): void
    {
        if (str_ends_with($fqcn, self::COMMAND_HANDLER_SUFFIX)) {
            return;
        }

        throw new CanNotRegisterCommandHandler(sprintf('Fqcn "%s" does not end with "CommandHandler"', $fqcn));
    }

    private function guardThatThereIsACorrespondingCommand(CommandHandler $commandHandler): void
    {
        $commandFqcn = str_replace(self::COMMAND_HANDLER_SUFFIX, '', $commandHandler::class);
        if (!class_exists($commandFqcn)) {
            throw new CanNotRegisterCommandHandler(sprintf('No corresponding command for commandHandler "%s" found', $commandHandler::class));
        }
    }
}