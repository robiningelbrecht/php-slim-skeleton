<?php

namespace App\Tests\Domain\WriteModel\Pokemon;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonId;
use PHPUnit\Framework\TestCase;

class PokemonTest extends TestCase
{
    public function testGetMainType(): void
    {
        $pokemon = PokemonBuilder::fromDefaults()->build();
        $this->assertEquals('typeOne', $pokemon->getMainType());
    }

    public function testGetStat(): void
    {
        $pokemon = PokemonBuilder::fromDefaults()->build();
        $this->assertEquals(30, $pokemon->getStat('hp'));
        $this->assertEquals(56, $pokemon->getStat('attack'));
    }

    public function testGetStatItShouldThrowWhenNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Stat "defense" not found');

        $pokemon = PokemonBuilder::fromDefaults()->build();
        $pokemon->getStat('defense');
    }

    public function testGetSprite(): void
    {
        $pokemon = PokemonBuilder::fromDefaults()
            ->withSprites([
                'other' => [
                    'dream_world' => [
                        'front_female' => null,
                        'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/dream-world/19.svg',
                    ],
                ],
            ])
            ->build();
        $this->assertEquals(
            'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/dream-world/19.svg',
            $pokemon->getSpriteUri()
        );

        $pokemon = PokemonBuilder::fromDefaults()
            ->withSprites([
                'other' => [
                    'official-artwork' => [
                        'front_female' => null,
                        'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/19.png',
                    ],
                ],
            ])
            ->build();

        $this->assertEquals(
            'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/19.png',
            $pokemon->getSpriteUri()
        );
    }

    public function testGetSpriteItShouldThrowWhenNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Sprite not found');

        $pokemon = PokemonBuilder::fromDefaults()
            ->withSprites([])
            ->build();
        $pokemon->getSpriteUri();
    }

    public function testCreate(): void
    {
        $pokemon = Pokemon::create(
            PokemonId::fromString('pokemon-test'),
            1,
            'Bulbasaur',
            60,
            10,
            25,
            ['abilityOne', 'abilityTwo'],
            ['moveOne', 'moveTwo'],
            ['typeOne', 'typeTwo'],
            [
                [
                    'base' => 30,
                    'name' => 'hp',
                ],
                [
                    'base' => 56,
                    'name' => 'attack',
                ],
            ],
            [
                'other' => [
                    'dream_world' => [
                        'front_female' => null,
                        'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/dream-world/19.svg',
                    ],
                ],
            ],
        );

        $this->assertEquals($pokemon, PokemonBuilder::fromDefaults()->build());
    }
}
