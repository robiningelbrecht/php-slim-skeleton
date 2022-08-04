<?php

namespace App\Infrastructure\Eventing;

abstract class AggregateRoot
{
    private array $recordedEvents = [];

    protected function recordThat(DomainEvent $domainEvent): void
    {
        $this->recordedEvents[] = $domainEvent;
    }

    public function getRecordedEvents(): array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }
}