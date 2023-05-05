<?php

namespace App\Infrastructure\Exception;

use Slim\App;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

readonly class WhoopsBuilder
{
    private function __construct(
        private HandlerInterface $handler
    ) {
    }

    public static function fromHtmlDefaults(): self
    {
        $handler = new PrettyPageHandler();
        $handler->setEditor('phpstorm');
        $handler->setPageTitle('Waw, this is embarrassing');
        $handler->addDataTable('Slim Application', [
            'Version' => App::VERSION,
        ]);

        return new self($handler);
    }

    public static function fromJsonDefaults(): self
    {
        $handler = new JsonResponseHandler();
        $handler->addTraceToOutput(true);

        return new self($handler);
    }

    public function withEditor(string $editor): self
    {
        if ($this->handler instanceof PrettyPageHandler) {
            $this->handler->setEditor($editor);
        }

        return $this;
    }

    public function build(): Run
    {
        $whoops = new Run();
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler($this->handler);

        return $whoops;
    }
}
