<?php

namespace App\Infrastructure\ValueObject;

abstract class Identifier extends NonEmptyStringLiteral
{
    protected function __construct(
        string $identifier
    ) {
        $this->validate($identifier);
        parent::__construct($identifier);
    }

    protected function validate(string $identifier): void
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException(get_called_class().' cannot be empty');
        }
        if (!static::startsWithPrefix($identifier)) {
            throw new \InvalidArgumentException('Identifier does not start with prefix "'.$this->getPrefix().'", got: '.$identifier);
        }
    }

    private static function startsWithPrefix(string $identifier): bool
    {
        if ('' === static::getPrefix()) {
            return true;
        }

        return str_starts_with($identifier, static::getPrefix());
    }

    public static function random(): static
    {
        return new static(static::getPrefix().UuidFactory::random());
    }

    abstract public static function getPrefix(): string;
}
