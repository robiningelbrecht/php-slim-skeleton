<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;

class ConsoleCommandFactory
{
    /** @var Command[] */
    private array $consoleCommands;

    public function registerConsoleCommand(Command $command): void
    {
        $this->consoleCommands[] = $command;
    }

    public function getConsoleCommands(): array
    {
        return $this->consoleCommands;
    }
}