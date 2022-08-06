<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\Worker;
use PhpAmqpLib\Message\AMQPMessage;

class CommandQueueWorker implements Worker
{
    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    public function processMessage(Envelope $envelope, AMQPMessage $message)
    {
        // TODO: Implement processMessage() method.
    }

    public function processFailure(Envelope $envelope, AMQPMessage $message, \Throwable $exception, Queue $queue)
    {
        // TODO: Implement processFailure() method.
    }

    public function maxIterationsReached(): bool
    {
        // TODO: Implement maxIterationsReached() method.
    }

    public function maxLifeTimeReached(): bool
    {
        // TODO: Implement maxLifeTimeReached() method.
    }

    public function getMaxIterations(): int
    {
        // TODO: Implement getMaxIterations() method.
    }

    public function getMaxLifeTime(): \DateTimeImmutable
    {
        // TODO: Implement getMaxLifeTime() method.
    }

    public function getMaxLifeTimeInterval(): \DateInterval
    {
        // TODO: Implement getMaxLifeTimeInterval() method.
    }

}