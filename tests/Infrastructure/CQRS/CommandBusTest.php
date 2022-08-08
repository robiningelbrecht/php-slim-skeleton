<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\Serialization\Json;
use App\Tests\ContainerTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandBusTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private CommandBus $commandBus;

    public function testItRegistersAllCommands(): void
    {
        $this->assertMatchesJsonSnapshot(Json::encode(array_keys($this->commandBus->getCommandHandlers())));
    }

    public function testGetItShouldThrowOnInvalidCommandHandler(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('CommandHandler for command "App\Tests\Infrastructure\CQRS\TestCommand" not subscribed to this bus');

        $this->commandBus->dispatch(new TestCommand());
    }

    public function testSubscribeCommandHandlerItShouldThrowWhenInvalidCommandHandlerName(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Fqcn "App\Tests\Infrastructure\CQRS\TestInvalidCommandHandlerName" does not end with "CommandHandler"');

        $this->commandBus->subscribeCommandHandler(new TestInvalidCommandHandlerName());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->getContainer()->get(CommandBus::class);
    }
}
