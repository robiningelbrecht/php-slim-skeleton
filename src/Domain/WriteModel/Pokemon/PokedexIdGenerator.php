<?php

namespace App\Domain\WriteModel\Pokemon;

class PokedexIdGenerator
{
    public function random(): int
    {
        return mt_rand(1, Pokemon::MAX_ID);
    }
}
