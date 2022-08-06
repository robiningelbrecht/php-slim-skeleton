<?php

namespace App\Tests\Infrastructure\Repository;

use App\Infrastructure\Repository\DbalAggregateRootRepository;
use App\Tests\Infrastructure\Eventing\TestEvent;

class TestRepository extends DbalAggregateRootRepository
{
    public function save(): void
    {
        $domainEvents = [
            new TestEvent('1'),
            new TestEvent('2'),
            new TestEvent('3'),
        ];

        $this->publishEvents($domainEvents);
    }
}
