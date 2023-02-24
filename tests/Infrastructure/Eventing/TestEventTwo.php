<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Eventing\DomainEvent;

class TestEventTwo extends DomainEvent
{
    public function __construct(
        protected string $id,
    ) {
    }
}
