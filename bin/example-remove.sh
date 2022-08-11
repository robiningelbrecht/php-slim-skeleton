#!/bin/bash

# Public folder.
rm -Rf public/favicon.png
rm -Rf public/style.css
# Migrations folder.
rm -Rf migrations/*
# Src folder.
rm -Rf src/Console/Pokemon*
rm -Rf src/Controller/*
rm -Rf src/Domain/ReadModel/*
rm -Rf src/Domain/WriteModel/*
# Templates folder.
rm -Rf templates/*
# Tests folder.
rm -Rf tests/Console/Pokemon*
rm -Rf tests/Controller/*
rm -Rf tests/Domain/ReadModel/*
rm -Rf tests/Domain/WriteModel/*

# Update routes.php
printf '
<?php

  use Slim\App;
  use Slim\Handlers\Strategies\RequestResponseArgs;

  return function (App $app) {
      // Set default route strategy.
      $routeCollector = $app->getRouteCollector();
      $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
  };' > config/routes.php


echo "All examples related files are deleted.
Do not forget to update 'docker/mysql/mysql.databases.sql' to create the database(s) you need"