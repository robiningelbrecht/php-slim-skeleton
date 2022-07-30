<?php

namespace App\Console;

use Symfony\Component\Console\Application;

class ConsoleApp
{
    public function __construct(
        private readonly ConsoleCommandFactory $consoleCommandFactory
    )
    {
    }

    public function boot(): Application
    {
        $application = new Application();
        foreach ($this->consoleCommandFactory->getConsoleCommands() as $command) {
            $application->add($command);
        }

        return $application;
    }
}