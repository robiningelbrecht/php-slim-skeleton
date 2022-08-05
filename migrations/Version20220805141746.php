<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220805141746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Pokemon (pokemonId VARCHAR(255) NOT NULL, pokedexId INT NOT NULL, name VARCHAR(255) NOT NULL, baseExperience SMALLINT NOT NULL, height SMALLINT NOT NULL, weight SMALLINT NOT NULL, abilities JSON DEFAULT NULL, moves JSON DEFAULT NULL, types JSON DEFAULT NULL, stats JSON DEFAULT NULL, sprites JSON DEFAULT NULL, PRIMARY KEY(pokemonId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Result (pokemonId VARCHAR(255) NOT NULL, impressions INT NOT NULL, upVotes INT NOT NULL, score INT NOT NULL, PRIMARY KEY(pokemonId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Vote (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', pokemonVotedFor VARCHAR(255) NOT NULL, pokemonNotVotedFor VARCHAR(255) NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Pokemon');
        $this->addSql('DROP TABLE Result');
        $this->addSql('DROP TABLE Vote');
    }
}
