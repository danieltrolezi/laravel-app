![version](https://img.shields.io/badge/version-0.9.0-blue?style=flat)
[![build](https://github.com/danieltrolezi/laravel-app/actions/workflows/application-ci.yml/badge.svg)](https://github.com/danieltrolezi/laravel-app/actions/workflows/application-ci.yml)

## :package: Base Laravel Application

This setup serves as the foundation for my personal Laravel projects and a playground for my coding experiments. 

If you prefer a streamlined development experience, consider checking out tools like [phpctl](https://github.com/opencodeco/phpctl), [docker-php](https://github.com/serversideup/docker-php), or [devpod](https://github.com/loft-sh/devpod). 

However, if you enjoy diving deep into the workings of everything, feel free to take a look.

#### Documentation

:sparkle: [Full documentation available on the Wiki](https://github.com/danieltrolezi/laravel-app/wiki).  

Explore examples on how to use:

* [Swagger](https://github.com/danieltrolezi/laravel-app/wiki/07.-Swagger)
* [PHP's Yaml](https://github.com/danieltrolezi/laravel-app/wiki/98.-Appendix#yaml)
* [Laravel Octane](https://github.com/danieltrolezi/laravel-app/wiki/08.-Laravel-Octane)
* [Authentication & Authorization](https://github.com/danieltrolezi/laravel-app/wiki/09.-Authentication-&-Authorization)

#### Key Technologies

* Backend: PHP 8.3 + Laravel 11 Framework
* Databases: MySQL 8 + Redis
* Containerization: Docker
* Web Server: Nginx
* PHP Processor: PHP-fpm
* Process Supervisor: Supervisord

#### Development Experience

* Code Sniffer (PSR-12): Enforces consistent and clean coding practices
* Git Hooks: Automates tasks to enhance your workflow
* Unit Testing: Ensures your code works as intended
* Xdebug Integration: Elevates your debugging capabilities
* Swagger: For comprehensive OpenAPI documentation
* Rector: Simplifies PHP/Laravel version upgrades

#### CD/CI Ready

This repository includes GitHub Actions templates for:

* Running Tests
* Code Sniffer
* Building Docker Images
* Uploading Docker Images to AWS ECR
* Updating Task Definitions on AWS ECS
* Updating Services on AWS ECS