<?php

namespace App\Infrastructure\Eventing;

use App\Infrastructure\Eventing\EventListener\EventListener;

class EventBus
{
    /** @var EventListener[] */
    private array $eventListeners = [];
    /** @var DomainEvent[] */
    private array $queue = [];
    private bool $isPublishing = false;

    public function subscribeEventListener(EventListener $eventListener): void
    {
        $this->eventListeners[] = $eventListener;
    }

    public function publish(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->queue[] = $domainEvent;
        }

        if (!$this->isPublishing) {
            $this->isPublishing = true;

            try {
                while ($domainEvent = array_shift($this->queue)) {
                    foreach ($this->eventListeners as $eventListener) {
                        if (!$eventListener->isListeningToEvent($domainEvent)) {
                            continue;
                        }
                        $eventListener->notifyThat($domainEvent);
                    }
                }
            } finally {
                $this->isPublishing = false;
            }
        }
    }
}
