<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\Serialization\Json;
use App\Tests\ContainerTestCase;
use App\Tests\Infrastructure\AMQP\RunUnitTester\RunUnitTesterCommandHandler;
use App\Tests\Infrastructure\CQRS\InvalidTestCommand\InvalidTestCommandCommandHandler;
use Spatie\Snapshots\MatchesSnapshots;

class CommandBusTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private CommandBus $commandBus;

    public function testItRegistersAllCommands(): void
    {
        $commands = array_keys($this->commandBus->getCommandHandlers());
        sort($commands);
        $this->assertMatchesJsonSnapshot(Json::encode($commands));
    }

    public function testItRegistersCommand(): void
    {
        $commandHandler = new RunUnitTesterCommandHandler();
        $this->commandBus->subscribeCommandHandler($commandHandler);
        $this->assertContains($commandHandler, $this->commandBus->getCommandHandlers());
    }

    public function testGetItShouldThrowOnInvalidCommandHandler(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('CommandHandler for command "App\Tests\Infrastructure\CQRS\TestCommand" not subscribed to this bus');

        $this->commandBus->dispatch(new TestCommand());
    }

    public function testSubscribeCommandHandlerItShouldThrowWhenNoCorrespondingCommand(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No corresponding command for commandHandler "App\Tests\Infrastructure\CQRS\TestNoCorrespondingCommandCommandHandler" found');

        $this->commandBus->subscribeCommandHandler(new TestNoCorrespondingCommandCommandHandler());
    }

    public function testSubscribeCommandHandlerItShouldThrowWhenInvalidCommandHandlerName(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Fqcn "App\Tests\Infrastructure\CQRS\TestInvalidCommandHandlerName" does not end with "CommandHandler"');

        $this->commandBus->subscribeCommandHandler(new TestInvalidCommandHandlerName());
    }

    public function testSubscribeCommandHandlerItShouldThrowWhenInvalidCommandName(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Command name cannot end with "command"');

        $this->commandBus->subscribeCommandHandler(new InvalidTestCommandCommandHandler());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->getContainer()->get(CommandBus::class);
    }
}
