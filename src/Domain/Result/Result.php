<?php

namespace App\Domain\Result;

use App\Domain\Pokemon\Pokemon;

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

    public static function fromPokemon(Pokemon $pokemon): self
    {
        $score = 0;
        if ($pokemon->getImpressions() > 0) {
            $score = round(($pokemon->getUpVotes() / $pokemon->getImpressions()) * 100);
        }
        return new self(
            $pokemon,
            $score,
            $pokemon->getUpVotes(),
        );
    }
}