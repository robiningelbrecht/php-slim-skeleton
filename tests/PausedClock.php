<?php

namespace App\Tests;

use DateTimeImmutable;
use Lcobucci\Clock\Clock;

class PausedClock implements Clock
{
    private function __construct(
        private DateTimeImmutable $pausedOn)
    {
    }

    public static function on(DateTimeImmutable $on): self
    {
        return new self($on);
    }

    public function now(): DateTimeImmutable
    {
        return $this->pausedOn;
    }
}
