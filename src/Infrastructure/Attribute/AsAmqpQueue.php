<?php

namespace App\Infrastructure\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsAmqpQueue
{
    public function __construct(
        public string $name,
        public int $numberOfWorkers,
    )
    {

    }
}
