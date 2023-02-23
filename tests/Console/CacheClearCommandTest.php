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
    private string $cacheDir;

    public function testExecute(): void
    {
        @mkdir($this->cacheDir);
        @mkdir($this->cacheDir.'/slim');
        file_put_contents($this->cacheDir.'/slim/cache.file', 'contents');
        @mkdir($this->cacheDir.'/slim/sub-dir');

        $matcher = $this->exactly(2);
        $this->settings
            ->expects($matcher)
            ->method('get')
            ->willReturnCallback(function (string $key) use ($matcher) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($key, 'doctrine.cache_dir'),
                    2 => $this->assertEquals($key, 'slim.cache_dir'),
                };
            })
            ->willReturnOnConsecutiveCalls(
                $this->cacheDir.'/doctrine',
                $this->cacheDir.'/slim'
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

        @rmdir($this->cacheDir);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheDir = Settings::getAppRoot().'/tests/Console/cache';
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
