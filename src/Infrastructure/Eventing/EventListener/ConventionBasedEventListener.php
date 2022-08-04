<?php

namespace App\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\Eventing\DomainEvent;

abstract class ConventionBasedEventListener implements EventListener
{
    private string $eventProcessingMethodPrefix;
    private array $subscribedEvents;

    public function __construct()
    {
        $this->eventProcessingMethodPrefix = $this->resolveEventProcessingMethodPrefix();
        $this->subscribedEvents = $this->resolveSubscribedEvents();
    }

    public function notifyThat(DomainEvent $domainEvent): void
    {
        $methodName = $this->eventProcessingMethodPrefix . $domainEvent->getShortClassName();
        if (!\method_exists($this, $methodName)) {
            return;
        }
        $this->$methodName($domainEvent);
    }

    public function getSubscribedEvents(): array
    {
        return $this->subscribedEvents;
    }

    private function resolveSubscribedEvents(): array
    {
        $interestedIn = [];
        $methods = (new \ReflectionClass($this))->getMethods();
        foreach ($methods as $method) {
            if (!str_starts_with($method->getName(), $this->eventProcessingMethodPrefix)) {
                continue;
            }
            $interestedIn[] = (string)$method->getParameters()[0]->getType();
        }

        return $interestedIn;
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
