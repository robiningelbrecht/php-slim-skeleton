<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Envelope;

abstract class DomainCommand implements Envelope
{
    public function __construct(
        private readonly \DateTimeImmutable $stampTime,
    )
    {
    }

    public function getStampTime(): \DateTimeImmutable
    {
        return $this->stampTime;
    }

    public function getContent(): string
    {
        return serialize($this);
    }
}