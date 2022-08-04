<?php

namespace App\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\Eventing\DomainEvent;

abstract class ConventionBasedEventListener implements EventListener
{
    private string $eventProcessingMethodPrefix;

    public function __construct()
    {
        $this->eventProcessingMethodPrefix = $this->resolveEventProcessingMethodPrefix();
    }

    public function notifyThat(DomainEvent $domainEvent): void
    {
        $methodName = $this->eventProcessingMethodPrefix . $domainEvent->getShortClassName();
        if (!\method_exists($this, $methodName)) {
            return;
        }
        $this->$methodName($domainEvent);
    }

    private function resolveEventProcessingMethodPrefix(): string
    {
        if (!$attributes = (new \ReflectionClass($this))->getAttributes(AsEventListener::class)) {
            throw new \RuntimeException(sprintf('Event listener %s not tagged with attribute', get_class($this)));
        }

        /** @var \App\Infrastructure\Eventing\EventListener\EventListenerType $type */
        $type = $attributes[0]->newInstance()->getType();
        return $type->getEventProcessingMethodPrefix();
    }
}
