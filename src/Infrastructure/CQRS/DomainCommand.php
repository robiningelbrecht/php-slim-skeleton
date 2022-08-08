<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Envelope;

abstract class DomainCommand implements Envelope, \JsonSerializable
{
    /** @var array<mixed> */
    private array $metadata = [];

    /**
     * @param array<mixed> $metadata
     */
    public function setMetaData(array $metadata): void
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'commandName' => str_replace('\\', '.', static::class),
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
