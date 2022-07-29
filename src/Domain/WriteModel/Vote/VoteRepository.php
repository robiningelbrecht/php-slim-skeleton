<?php

namespace App\Domain\WriteModel\Vote;

use Doctrine\DBAL\Connection;

class VoteRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    public function add(Vote $vote): void
    {
        $this->connection->insert('Vote', $vote->toArray());
    }

    public function truncate(): void
    {
        $this->connection->executeStatement('TRUNCATE Vote');
    }
}