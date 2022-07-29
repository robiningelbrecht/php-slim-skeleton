<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\Pokemon;

class Result
{
    private function __construct(
        private Pokemon $pokemon,
        private float $score,
        private int $numberOfVotes,
    )
    {
    }

    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getNumberOfVotes(): int
    {
        return $this->numberOfVotes;
    }

    public static function fromPokemonAndVotes(Pokemon $pokemon, int $impressions, int $upVotes): self
    {
        $score = 0;
        if ($impressions > 0) {
            $score = round(($upVotes / $impressions) * 100);
        }
        return new self(
            $pokemon,
            $score,
            $upVotes,
        );
    }
}