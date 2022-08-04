<?php

namespace App\Infrastructure\Console;

use Symfony\Component\Console\Command\Command;

class ConsoleCommandContainer
{
    /** @var Command[] */
    private array $consoleCommands = [];

    public function registerCommand(Command $command): void
    {
        if(array_key_exists($command->getName(), $this->getCommands())){
            throw new \RuntimeException(sprintf('Command "%s" already registered in factory', $command->getName()));
        }
        $this->consoleCommands[$command->getName()] = $command;
    }

    public function getCommands(): array
    {
        return $this->consoleCommands;
    }
}