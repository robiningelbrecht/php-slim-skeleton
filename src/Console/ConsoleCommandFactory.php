<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;

class ConsoleCommandFactory
{
    /** @var Command[] */
    private array $consoleCommands;

    public function registerConsoleCommand(Command $command): void
    {
        if(array_key_exists($command->getName(), $this->getConsoleCommands())){
            throw new \RuntimeException(sprintf('Command "%s" already registered in factory', $command->getName()));
        }
        $this->consoleCommands[$command->getName()] = $command;
    }

    public function getConsoleCommands(): array
    {
        return $this->consoleCommands;
    }
}