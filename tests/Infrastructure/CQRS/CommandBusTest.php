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

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->getContainer()->get(CommandBus::class);
    }
}
