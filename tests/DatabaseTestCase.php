<?php

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

abstract class DatabaseTestCase extends ContainerTestCase
{
    private static bool $testDatabaseCreated = false;
    protected static ?Connection $connection = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$connection) {
            self::$connection = $this->getContainer()->get(Connection::class);
        }

        if (!self::$testDatabaseCreated) {
            $this->createTestDatabase();
            self::$testDatabaseCreated = true;
        }

        $this->getConnection()->beginTransaction();
    }

    public function tearDown(): void
    {
        try {
            $this->getConnection()->rollBack();
        } catch (ConnectionException) {
        } catch (\PDOException) {
        }
    }

    public function getConnection(): Connection
    {
        return self::$connection;
    }

    private function createTestDatabase(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }
}
