<?php

namespace App\Domain\WriteModel\Vote;

use App\Infrastructure\Eventing\EventBus;
use App\Infrastructure\Repository\DbalAggregateRootRepository;
use Doctrine\DBAL\Connection;

class VoteRepository extends DbalAggregateRootRepository
{
    public function __construct(
        EventBus $eventBus,
        private readonly Connection $connection
    ) {
        parent::__construct($eventBus);
    }

    public function add(Vote $vote): void
    {
        $this->connection->insert('Vote', $vote->toArray());
        $this->publishEvents($vote->getRecordedEvents());
    }
}
