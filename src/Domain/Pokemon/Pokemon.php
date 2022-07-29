<?php

namespace App\Domain\Pokemon;

use Ramsey\Uuid\Rfc4122\UuidV5;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Pokemon
{
    public const MAX_ID = 251;

    private function __construct(
        private readonly string $id,
        private readonly UuidInterface $uuid,
        private readonly string $name,
        private readonly int $baseExperience,
        private readonly int $height,
        private readonly int $weight,
        private readonly array $abilities,
        private readonly array $moves,
        private readonly array $types,
        private readonly array $stats,
        private readonly array $sprites,
        private int $impressions = 0,
        private int $upVotes = 0
    )
    {
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBaseExperience(): int
    {
        return $this->baseExperience;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function getMoves(): array
    {
        return $this->moves;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getMainType(): string
    {
        return $this->getTypes()[0];
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function getStat(string $name): int
    {
        $stats = array_filter($this->getStats(), fn(array $stat) => $name === $stat['name']);
        if (empty($stats)) {
            throw new \RuntimeException(sprintf('Stat "%s" not found', $name));
        }

        return reset($stats)['base'];
    }

    public function getSprites(): array
    {
        return $this->sprites;
    }

    public function getSpriteUri(): string
    {
        $sprites = $this->getSprites();

        if (isset($sprites['other']['dream_world']['front_default'])) {
            return $sprites['other']['dream_world']['front_default'];
        }
        if (isset($sprites['other']['official-artwork']['front_default'])) {
            return $sprites['other']['official-artwork']['front_default'];
        }

        throw new \RuntimeException('Sprite not found');
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getUpVotes(): int
    {
        return $this->upVotes;
    }

    public function incrementImpressions(): self
    {
        $this->impressions++;

        return $this;
    }

    public function incrementUpVotes(): self
    {
        $this->upVotes++;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'uuid' => $this->getUuid()->toString(),
            'name' => $this->getName(),
            'baseExperience' => $this->getBaseExperience(),
            'height' => $this->getHeight(),
            'weight' => $this->getWeight(),
            'abilities' => $this->getAbilities(),
            'moves' => $this->getMoves(),
            'types' => $this->getTypes(),
            'stats' => $this->getStats(),
            'sprites' => $this->getSprites(),
            'impressions' => $this->getImpressions(),
            'upVotes' => $this->getUpVotes(),
        ];
    }

    public static function fromState(array $state): self
    {
        return new self(
            $state['id'],
            Uuid::fromString($state['uuid']),
            $state['name'],
            (int)$state['baseExperience'],
            (int)$state['height'],
            (int)$state['weight'],
            $state['abilities'],
            $state['moves'],
            $state['types'],
            $state['stats'],
            $state['sprites'],
            $state['impressions'],
            $state['upVotes'],
        );
    }

    public static function fromApi(array $data): self
    {
        return new self(
            $data['id'],
            Uuid::uuid5('4bdbe8ec-5cb5-11ea-bc55-0242ac130003', $data['id']),
            $data['name'],
            (int)$data['base_experience'],
            (int)$data['height'],
            (int)$data['weight'],
            array_map(fn(array $ability) => $ability['ability']['name'], $data['abilities']),
            array_map(fn(array $move) => $move['move']['name'], $data['moves']),
            array_map(fn(array $type) => $type['type']['name'], $data['types']),
            array_map(fn(array $stat) => [
                'name' => $stat['stat']['name'],
                'base' => $stat['base_stat'],
            ], $data['stats']),
            $data['sprites'],
        );
    }
}