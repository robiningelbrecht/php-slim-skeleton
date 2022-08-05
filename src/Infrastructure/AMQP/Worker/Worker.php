<?php

namespace App\Infrastructure\AMQP\Worker;

use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\ValueObject\Time\SerializableDateTime;
use PhpAmqpLib\Message\AMQPMessage;

interface Worker
{
    public function getName(): string;

    public function processMessage(Envelope $envelope, AMQPMessage $message);

    public function processFailure(Envelope $envelope, AMQPMessage $message, \Throwable $exception, Queue $queue);

    public function maxIterationsReached(): bool;

    public function maxLifeTimeReached(): bool;

    public function getMaxIterations(): int;

    public function getMaxLifeTime(): SerializableDateTime;

    public function getMaxLifeTimeInterval(): \DateInterval;
}
