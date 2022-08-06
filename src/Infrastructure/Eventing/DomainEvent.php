<?php

namespace App\Infrastructure\Eventing;

abstract class DomainEvent implements \JsonSerializable
{
    public function getShortClassName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'eventName' => str_replace('\\', '.', static::class),
            'payload' => $this->getSerializablePayload(),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function getSerializablePayload(): array
    {
        $serializedPayload = [];
        foreach ($this as $property => $value) {
            $serializedPayload[$property] = $value;
        }

        return $serializedPayload;
    }
}
