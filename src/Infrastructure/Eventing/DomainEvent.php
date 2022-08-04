<?php

namespace App\Infrastructure\Eventing;

abstract class DomainEvent
{
    public function getShortClassName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}