<?php

namespace App\Infrastructure\AMQP;

interface Envelope
{
    public function getContent(): string;

    public function getStampTime(): \DateTimeImmutable;
}