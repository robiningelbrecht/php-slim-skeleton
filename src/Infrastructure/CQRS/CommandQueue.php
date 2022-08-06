<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\Worker;

abstract class CommandQueue extends Queue
{
    public function __construct(
        AMQPChannelFactory $AMQPChannelFactory,
        private readonly CommandQueueWorker $commandQueueWorker,
    )
    {
        parent::__construct($AMQPChannelFactory);
    }

    public function getWorker(): Worker
    {
        return $this->commandQueueWorker;
    }
}