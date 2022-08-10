<?php

namespace App\Tests\Console;

use App\Console\CacheClearCommand;
use App\Infrastructure\Environment\Settings;
use App\Tests\ConsoleCommandTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CacheClearCommandTest extends ConsoleCommandTestCase
{
    private CacheClearCommand $cacheClearCommand;
    private MockObject $settings;

    public function testExecute(): void
    {
        @mkdir(Settings::getAppRoot().'/tests/Console/cache');
        @mkdir(Settings::getAppRoot().'/tests/Console/cache/slim');
        file_put_contents(Settings::getAppRoot().'/tests/Console/cache/slim/cache.file', 'contents');

        $this->settings
            ->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(['doctrine.cache_dir'], ['slim.cache_dir'])
            ->willReturnOnConsecutiveCalls(
                Settings::getAppRoot().'/tests/Console/cache/doctrine',
                Settings::getAppRoot().'/tests/Console/cache/slim'
            );

        $command = $this->getCommandInApplication('cache:clear');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertFalse(file_exists(Settings::getAppRoot().'/tests/Console/cache/slim'));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        @rmdir(Settings::getAppRoot().'/tests/Console/cache');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->settings = $this->createMock(Settings::class);

        $this->cacheClearCommand = new CacheClearCommand(
            $this->settings
        );
    }

    protected function getConsoleCommand(): Command
    {
        return $this->cacheClearCommand;
    }
}
