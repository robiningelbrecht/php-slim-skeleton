<?php

namespace App\Infrastructure\Repository;

use App\Infrastructure\Eventing\EventBus;

abstract class DbalAggregateRootRepository
{
    public function __construct(
        protected EventBus $eventBus)
    {
    }

    protected function publishEvents(array $domainEvents): void
    {
        $this->eventBus->publish($domainEvents);
    }
}
