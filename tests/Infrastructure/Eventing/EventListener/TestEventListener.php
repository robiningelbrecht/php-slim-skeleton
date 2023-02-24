<?php

namespace App\Tests\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\Eventing\EventListener\ConventionBasedEventListener;
use App\Tests\Infrastructure\Eventing\TestEvent;
use App\Tests\Infrastructure\Eventing\TestEventTwo;
use App\Tests\Infrastructure\Eventing\TestInvalidEvent;

#[AsEventListener]
class TestEventListener extends ConventionBasedEventListener
{
    public function reactToTestEvent(TestEvent $event): void
    {
        throw new \RuntimeException('reacted to event!');
    }

    public function reactToTestEventTwo(TestEventTwo $event, string $paramThatDoesNotBelongHere): void
    {
        throw new \RuntimeException('reacted to second event!');
    }

    public function reactToTestInvalidEvent(TestInvalidEvent $event): void
    {
        throw new \RuntimeException('reacted to invalid event!');
    }

    public function reactToWrongName(TestEvent $event): void
    {
        throw new \RuntimeException('wrong name');
    }
}
