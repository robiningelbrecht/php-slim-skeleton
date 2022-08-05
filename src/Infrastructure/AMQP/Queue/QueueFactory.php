<?php

namespace App\Infrastructure\AMQP\Queue;

class QueueFactory
{
    /** @var Queue[] */
    private array $queues = [];

    public function registerQueue(Queue $queue): void
    {
        $this->queues[$queue->getName()] = $queue;
    }

    public function getQueue(string $name): Queue
    {
        if (!array_key_exists($name, $this->queues)) {
            throw new \RuntimeException(sprintf('Queue "%s" not registered in factory', $name));
        }

        return $this->queues[$name];
    }

    public function getQueues(): array
    {
        return $this->queues;
    }
}
