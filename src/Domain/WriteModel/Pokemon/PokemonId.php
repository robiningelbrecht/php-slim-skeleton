<?php

namespace App\Domain\WriteModel\Pokemon;

use App\Infrastructure\ValueObject\Identifier;

class PokemonId extends Identifier
{
    public static function getPrefix(): string
    {
        return 'pokemon-';
    }

}