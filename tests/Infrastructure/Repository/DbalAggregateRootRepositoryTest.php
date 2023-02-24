<?php

namespace App\Tests\Infrastructure\Repository;

use App\Infrastructure\Eventing\DomainEvent;
use App\Infrastructure\Eventing\EventBus;
use App\Infrastructure\Serialization\Json;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class DbalAggregateRootRepositoryTest extends TestCase
{
    use MatchesSnapshots;

    public function testPublishEvents(): void
    {
        $eventBus = $this->createMock(EventBus::class);

        $eventBus
            ->expects($this->once())
            ->method('publish')
            ->willReturnCallback(function (DomainEvent ...$domainEvents) {
                $this->assertMatchesJsonSnapshot(Json::encode($domainEvents));
            });

        $testRepository = new TestRepository($this->createMock(Connection::class), $eventBus);
        $testRepository->save();
    }
}
