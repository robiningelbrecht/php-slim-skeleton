<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Eventing\EventBus;
use App\Tests\ContainerTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class EventBusTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private EventBus $eventBus;

    public function testItRegistersAllEventListeners(): void
    {
        $this->assertMatchesJsonSnapshot(array_keys($this->eventBus->getEventListeners()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventBus = $this->getContainer()->get(EventBus::class);
    }
}
