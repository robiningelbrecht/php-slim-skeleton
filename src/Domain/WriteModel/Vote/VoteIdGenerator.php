<?php

namespace App\Domain\WriteModel\Vote;

class VoteIdGenerator
{
    public function random(): VoteId
    {
        return VoteId::random();
    }
}
