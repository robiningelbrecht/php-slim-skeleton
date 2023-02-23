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
        $matcher = $this->exactly(3);
        $this->connection
            ->expects($matcher)
            ->method('executeStatement')
            ->willReturnCallback(function (string $sql) use ($matcher) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($sql, 'TRUNCATE Pokemon'),
                    2 => $this->assertEquals($sql, 'TRUNCATE Vote'),
                    3 => $this->assertEquals($sql, 'TRUNCATE Result'),
                };
            });

        $command = $this->getCommandInApplication('app:pokemon:reset');

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
