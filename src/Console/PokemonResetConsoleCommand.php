<?php

namespace App\Console;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:pokemon:reset', description: 'Empty complete DB, votes will be deleted as well')]
class PokemonResetConsoleCommand extends Command
{
    public function __construct(
        private readonly Connection $connection,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tables = ['Pokemon', 'Vote', 'Result'];
        foreach ($tables as $table) {
            $this->connection->executeStatement('TRUNCATE '.$table);
        }

        return Command::SUCCESS;
    }
}
