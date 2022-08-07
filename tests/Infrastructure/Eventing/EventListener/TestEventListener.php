<?php

namespace App\Tests\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Eventing\DomainEvent;
use App\Infrastructure\Eventing\EventListener\EventListener;

class TestEventListener implements EventListener
{
    public function notifyThat(DomainEvent $event): void
    {
        // TODO: Implement notifyThat() method.
    }

    public function isListeningToEvent(DomainEvent $event): bool
    {
        // TODO: Implement isListeningToEvent() method.
    }
}
