<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Vote\VoteWasAdded;
use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\Eventing\EventListener\ConventionBasedEventListener;
use App\Infrastructure\Eventing\EventListener\EventListenerType;

#[AsEventListener(EventListenerType::PROJECTOR)]
class VoteResultProjector extends ConventionBasedEventListener
{
    public function projectVoteWasAdded(VoteWasAdded $event): void
    {

    }
}