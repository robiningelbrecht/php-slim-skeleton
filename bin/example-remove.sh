#!/bin/bash

# @todo: clear routes file.
# Public folder.
rm -Rf ../public/favicon.png
rm -Rf ../public/style.css
# Src folder.
rm -Rf ../src/Console/Pokemon*
rm -Rf ../src/Controller/*
rm -Rf ../src/Domain/ReadModel/*
rm -Rf ../src/Domain/WriteModel/*
# Templates folder.
rm -Rf ../templates/*
# Tests folder.
rm -Rf ../tests/Console/Pokemon*
rm -Rf ../tests/Controller/*
rm -Rf ../tests/Domain/ReadModel/*
rm -Rf ../tests/Domain/WriteModel/*


echo "All examples related files are deleted.
Do not forget to update 'docker/mysql/mysql.databases.sql' to create the database(s) you need"