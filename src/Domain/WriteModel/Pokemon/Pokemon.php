<?php

namespace App\Domain\WriteModel\Pokemon;

use App\Infrastructure\Serialization\Json;
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
    )
    {

    }

    public static function create(
        UuidInterface $uuid,
        string $id,
        string $name,
        int $baseExperience,
        int $height,
        int $weight,
        array $abilities,
        array $moves,
        array $types,
        array $stats,
        array $sprites
    ): self
    {
        return new self(
            $uuid,
            $id,
            $name,
            $baseExperience,
            $height,
            $weight,
            $abilities,
            $moves,
            $types,
            $stats,
            $sprites,
        );
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'uuid' => $this->getUuid()->toString(),
            'name' => $this->getName(),
            'baseExperience' => $this->getBaseExperience(),
            'height' => $this->getHeight(),
            'weight' => $this->getWeight(),
            'abilities' => Json::encode($this->getAbilities()),
            'moves' => Json::encode($this->getMoves()),
            'types' => Json::encode($this->getTypes()),
            'stats' => Json::encode($this->getStats()),
            'sprites' => Json::encode($this->getSprites()),
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
            Json::decode($state['abilities']),
            Json::decode($state['moves']),
            Json::decode($state['types']),
            Json::decode($state['stats']),
            Json::decode($state['sprites']),
        );
    }
}