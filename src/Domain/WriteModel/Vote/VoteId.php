<?php

namespace App\Domain\WriteModel\Vote;

use App\Infrastructure\ValueObject\Identifier;

class VoteId extends Identifier
{
    public static function getPrefix(): string
    {
        return 'vote-';
    }
}
