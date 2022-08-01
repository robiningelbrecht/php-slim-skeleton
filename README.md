<h1 align="center">Slim Framework skeleton</h1>

<p align="center">
	<img src="https://github.com/robiningelbrecht/slim-skeleton-ddd-amqp/raw/master/readme/slim.webp" alt="Slim">
</p>

<p align="center">
<a href="https://github.com/robiningelbrecht/slim-skeleton-ddd-amqp/blob/master/LICENSE"><img src="https://img.shields.io/github/license/robiningelbrecht/slim-skeleton-ddd-amqp?color=428f7e&logo=open%20source%20initiative&logoColor=white" alt="License"></a>
<a href="https://php.net/"><img src="https://img.shields.io/packagist/php-v/robiningelbrecht/slim-skeleton-ddd-amqp/dev-master?color=777bb3&logo=php&logoColor=white" alt="PHP"></a>
</p>

---

This repository is a simple example on how to use <a href="https://github.com/slimphp/Slim">Slim Framework</a> and <a href="https://github.com/PHP-DI/PHP-DI">PHP-DI</a>

To Run on your local machine:

* Run `git clone git@github.com:robiningelbrecht/slim-skeleton-ddd-amqp.git`
* Run `composer install` to install dependencies
* Copy `.env.dist` to `.env`
* Run `docker-composer up -d --build` to up and build Docker containers
* Run `docker-compose run --rm php-cli vendor/bin/doctrine-migrations migrate` to bring db schema up to date.
* Run `docker-compose run --rm php-cli bin/console pokemon:cache` to store Pokemon in database.
* Navigate to `http://localhost:8080`

<h2 align="center">Voting example</h2>
<p align="center">
  <img src="https://github.com/robiningelbrecht/the-coolest-pokemon/raw/master/readme/vote.webp" alt="Vote">
</p>

<h2 align="center">Results example</h2>
<p align="center">
  <img src="https://github.com/robiningelbrecht/the-coolest-pokemon/raw/master/readme/results.webp" alt="Results">
</p>
