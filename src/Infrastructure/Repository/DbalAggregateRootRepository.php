<?php

namespace App\Infrastructure\Repository;

use App\Infrastructure\Eventing\EventBus;
use Doctrine\DBAL\Connection;

abstract class DbalAggregateRootRepository
{
    public function __construct(
        protected readonly Connection $connection,
        protected readonly EventBus $eventBus)
    {
    }

    /**
     * @param \App\Infrastructure\Eventing\DomainEvent[] $domainEvents
     */
    protected function publishEvents(array $domainEvents): void
    {
        $this->eventBus->publish(...$domainEvents);
    }
}
