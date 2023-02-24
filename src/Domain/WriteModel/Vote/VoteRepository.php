<?php

namespace App\Domain\WriteModel\Vote;

use App\Infrastructure\Repository\DbalAggregateRootRepository;

class VoteRepository extends DbalAggregateRootRepository
{
    public function add(Vote $vote): void
    {
        $this->connection->insert('Vote', $vote->toArray());
        $this->publishEvents($vote->getRecordedEvents());
    }
}
