<h1 align="center">Event-driven Slim Framework skeleton</h1>

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
    An event-driven Slim Framework skeleton using AMQP and CQRS
</p>

## Installation

```bash
composer create-project robiningelbrecht/php-slim-skeleton [app-name] --no-install --ignore-platform-reqs --stability=dev
# Build docker containers
docker-compose up -d --build
# Install dependencies
docker-compose run --rm php-cli composer install
```

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
