<?php

namespace App\Domain\Pokemon;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Pokemon
{
    public const MAX_ID = 251;

    private function __construct(
        #[Id, Column(type: "guid", unique: true, nullable: false)]
        private readonly UuidInterface $uuid,
        #[Column(type: 'integer')]
        private readonly string $id,
        #[Column(type: "string", nullable: false)]
        private readonly string $name,
        #[Column(type: "smallint", nullable: false)]
        private readonly int $baseExperience,
        #[Column(type: "smallint", nullable: false)]
        private readonly int $height,
        #[Column(type: "smallint", nullable: false)]
        private readonly int $weight,
        #[Column(type: "json", nullable: true)]
        private readonly array $abilities,
        #[Column(type: "json", nullable: true)]
        private readonly array $moves,
        #[Column(type: "json", nullable: true)]
        private readonly array $types,
        #[Column(type: "json", nullable: true)]
        private readonly array $stats,
        #[Column(type: "json", nullable: true)]
        private readonly array $sprites,
        #[Column(type: "integer", nullable: false)]
        private int $impressions = 0,
        #[Column(type: "integer", nullable: false)]
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
            'abilities' => json_encode($this->getAbilities()),
            'moves' => json_encode($this->getMoves()),
            'types' => json_encode($this->getTypes()),
            'stats' => json_encode($this->getStats()),
            'sprites' => json_encode($this->getSprites()),
            'impressions' => $this->getImpressions(),
            'upVotes' => $this->getUpVotes(),
        ];
    }

    public static function fromState(array $state): self
    {
        return new self(
            Uuid::fromString($state['uuid']),
            $state['id'],
            $state['name'],
            (int)$state['baseExperience'],
            (int)$state['height'],
            (int)$state['weight'],
            json_decode($state['abilities'], true),
            json_decode($state['moves'], true),
            json_decode($state['types'], true),
            json_decode($state['stats'], true),
            json_decode($state['sprites'], true),
            $state['impressions'],
            $state['upVotes'],
        );
    }

    public static function fromApi(array $data): self
    {
        return new self(
            Uuid::uuid5('4bdbe8ec-5cb5-11ea-bc55-0242ac130003', $data['id']),
            $data['id'],
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