<h1 align="center">Event-driven Slim 4 Framework skeleton</h1>

<p align="center">
	<img src="https://github.com/robiningelbrecht/slim-skeleton-ddd-amqp/raw/master/readme/slim-new.webp" alt="Slim">
</p>

<p align="center">
<a href="https://github.com/robiningelbrecht/slim-skeleton-ddd-amqp/actions/workflows/ci.yml"><img src="https://github.com/robiningelbrecht/slim-skeleton-ddd-amqp/actions/workflows/ci.yml/badge.svg" alt="CI"></a>
<a href="https://codecov.io/gh/robiningelbrecht/php-slim-skeleton" ><img src="https://codecov.io/gh/robiningelbrecht/php-slim-skeleton/branch/master/graph/badge.svg?token=hgnlFWvWvw" alt="Codecov.io"/></a>
<a href="https://github.com/robiningelbrecht/slim-skeleton-ddd-amqp/blob/master/LICENSE"><img src="https://img.shields.io/github/license/robiningelbrecht/slim-skeleton-ddd-amqp?color=428f7e&logo=open%20source%20initiative&logoColor=white" alt="License"></a>
<a href="https://phpstan.org/"><img src="https://img.shields.io/badge/PHPStan-level%208-succes.svg?logo=php&logoColor=white&color=31C652" alt="PHPStan Enabled"></a>
<a href="https://php.net/"><img src="https://img.shields.io/packagist/php-v/robiningelbrecht/php-slim-skeleton/dev-master?color=%23777bb3&logo=php&logoColor=white" alt="PHP"></a>
</p>

---

<p align="center">
    An event-driven Slim 4 Framework skeleton using AMQP and CQRS
</p>

## Installation

### Default installation profile

The default installation profile has no examples. You should be using this profile if you know what's up and want to start with a clean slate.

```bash
> composer create-project robiningelbrecht/php-slim-skeleton [app-name] --no-install --ignore-platform-reqs --stability=dev
# Build docker containers
> docker-compose up -d --build
# Install dependencies
> docker-compose run --rm php-cli composer install
```

### Full installation profile

The full installation profile has a complete working example.

```bash
> composer create-project robiningelbrecht/php-slim-skeleton:dev-master-with-examples [app-name] --no-install --ignore-platform-reqs --stability=dev
# Build docker containers
> docker-compose up -d --build
# Install dependencies
> docker-compose run --rm php-cli composer install
# Initialize example
> docker-compose run --rm php-cli composer example:init
# Start consuming the voting example queue
> docker-compose run --rm php-cli bin/console app:amqp:consume add-vote-command-queue
```

## Some examples

### Registering a new route

```php
namespace App\Controller;

class UserOverviewRequestHandler
{
    public function __construct(
        private readonly UserOverviewRepository $userOverviewRepository,
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $users = $this->userOverviewRepository->findonyBy(/*...*/);
        $response->getBody()->write(/*...*/);

        return $response;
    }
}
```

Head over to `config/routes.php` and add a route for your RequestHandler:

```php
return function (App $app) {
    // Set default route strategy.
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
    
    $app->get('/user/overview', UserOverviewRequestHandler::class.':handle');
};
```
[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/request-handlers)

### Console commands

The console application uses the Symfony console component to leverage CLI functionality.

```php
#[AsCommand(name: 'app:user:create')]
class CreateUserConsoleCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ...
        return Command::SUCCESS;
    }
}
```

[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/console-commands)

### Domain commands and command handlers

The skeleton allows you to use commands and command handlers to perform actions. 
These 2 always come in pairs, when creating a new command in the write model, a corresponding command handler has to be created as well.

#### Creating a new command

```php
namespace App\Domain\WriteModel\User\CreateUser;

class CreateUser extends DomainCommand
{
 
}
```

#### Creating the corresponding command handler

```php
namespace App\Domain\WriteModel\User\CreateUser;

#[AsCommandHandler]
class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof CreateUser);

        // Do stuff.
    }
}
```

[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/cqrs)

### Eventing

The idea of this project is that everything is, or can be, event-driven. Event sourcing is not provided by default.

#### Create a new event

```php
class UserWasCreated extends DomainEvent
{
    public function __construct(
        private UserId $userId,
    ) {
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
```

#### Record the event

```php
class User extends AggregateRoot
{
    private function __construct(
       private UserId $userId,
    ) {
    }

    public static function create(
        UserId $userId,
    ): self {
        $user = new self($userId);
        $user->recordThat(new UserWasCreated($userId));

        return $user;
    }
}
```

#### Publish the event

```php
class UserRepository extends DbalAggregateRootRepository
{
    public function add(User $user): void
    {
        $this->connection->insert(/*...*/);
        $this->publishEvents($user->getRecordedEvents());
    }
}
```

#### Listen to the event

```php
#[AsEventListener(type: EventListenerType::PROCESS_MANAGER)]
class UserNotificationManager extends ConventionBasedEventListener
{
   
    public function reactToUserWasCreated(UserWasCreated $event): void
    {
        // Send out some notifications.
    }
}
```

[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/eventing)

### Async processing of commands with RabbitMQ

The chosen AMQP implementation for this project is RabbitMQ, but it can be easily switched to for example Amazon's AMQP solution.

#### Registering new queues

```php
#[AsEventListener(type: EventListenerType::PROCESS_MANAGER)]
class UserCommandQueue extends CommandQueue
{
}
```

#### Queueing commands

```php
class YourService
{
    public function __construct(
        private readonly UserCommandQueue $userCommandQueue
    ) {
    }

    public function aMethod(): void
    {
        $this->userCommandQueue->queue(new CreateUser(/*...*/));
    }
}
```

#### Consuming your queue

```bash
> docker-compose run --rm php-cli bin/console app:amqp:consume user-command-queue
```

[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/amqp)

### Database migrations

To manage database migrations, the doctrine/migrations package is used.

```php
#[Entity]
class User extends AggregateRoot
{
    private function __construct(
        #[Id, Column(type: 'string', unique: true, nullable: false)]
        private readonly UserId $userId,
        #[Column(type: 'string', nullable: false)]
        private readonly Name $name,
    ) {
    }

    // ...
}
```

You can have Doctrine generate a migration for you by comparing the current state of your database schema 
to the mapping information that is defined by using the ORM and then execute that migration.

```bash
> docker-compose run --rm php-cli vendor/bin/doctrine-migrations diff
> docker-compose run --rm php-cli vendor/bin/doctrine-migrations migrate
```

[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/migrations)

### Templating engine

The template engine of choice for this project is Twig and can be used to render anything HTML related.

#### Create a template

```twig
<h1>Users</h1>
<ul>
    {% for user in users %}
        <li>{{ user.username|e }}</li>
    {% endfor %}
</ul>
```
#### Render the template

```php
class UserOverviewRequestHandler
{
    public function __construct(
        private readonly Environment $twig,
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $template = $this->twig->load('users.html.twig');
        $response->getBody()->write($template->render(/*...*/));

        return $response;
    }
}
```

[Full documentation](https://php-slim-skeleton.robiningelbrecht.be/development-guide/templating)

## Documentation

Learn more at these links:

- [Skeleton Documentation](https://php-slim-skeleton.robiningelbrecht.be/)
- [Slim framework](https://www.slimframework.com)
- [PHP-DI](https://php-di.org/)
- [Symfony Console Commands](https://symfony.com/doc/current/console.html)
- [Doctrine migrations](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.6/)
- [Twig](https://twig.symfony.com/)

## Contributing

Please see [CONTRIBUTING](https://php-slim-skeleton.robiningelbrecht.be/contribute) for details.
