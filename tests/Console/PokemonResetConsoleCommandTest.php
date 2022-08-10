<?php

namespace App\Tests\Console;

use App\Console\PokemonResetConsoleCommand;
use App\Tests\ConsoleCommandTestCase;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class PokemonResetConsoleCommandTest extends ConsoleCommandTestCase
{
    private PokemonResetConsoleCommand $pokemonResetConsoleCommand;
    private MockObject $connection;

    public function testHandle(): void
    {
        $this->connection
            ->expects($this->exactly(3))
            ->method('executeStatement')
            ->withConsecutive(
                ['TRUNCATE Pokemon'],
                ['TRUNCATE Vote'],
                ['TRUNCATE Result']
            );

        $command = $this->getCommandInApplication('pokemon:reset');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->createMock(Connection::class);

        $this->pokemonResetConsoleCommand = new PokemonResetConsoleCommand(
            $this->connection
        );
    }

    protected function getConsoleCommand(): Command
    {
        return $this->pokemonResetConsoleCommand;
    }
}
