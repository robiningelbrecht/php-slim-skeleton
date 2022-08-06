<?php

namespace App\Domain\WriteModel\Vote;

use App\Infrastructure\Attribute\AsAmqpQueue;
use App\Infrastructure\CQRS\CommandQueue;

#[AsAmqpQueue(name: 'add-vote-command-queue', numberOfWorkers: 1)]
class AddVoteCommandQueue extends CommandQueue
{
}
