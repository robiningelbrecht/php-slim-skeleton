<?php

namespace App\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Eventing\DomainEvent;

interface EventListener
{
    public function notifyThat(DomainEvent $domainEvent): void;

    public function getSubscribedEvents(): array;
}
