
## Laravel App
Out of the box Laravel Application

### Stack

* Docker
* PHP 8.3
* Laravel 11
* MySQL 8.4

### Server
* nginx
* php-fpm
* supervisord

### Features
* Installation script
* PHP Code Sniffer w/ PSR12
* XDEBUG (Soon)
* Git Hooks
* Unit Testing
* Tests in Parallel (Soon)

## Setup

### Requirements

* [Docker Setup on Ubuntu](https://github.com/danieltrolezi/laravel-app/blob/master/docs/01-setup/docker.md)

### Setting up the Environment

1. Build images
```
$ docker compose build
```

2. Run the containers
```
$ docker compose up -d
```

3. Finishing installation
```
$ chmod +x ./install.sh
$ ./install.sh
```

## Commands

Commands must run inside the container.

| Command             | Description                     |
| ------------------- | ------------------------------- |
| composer run phpcs  | Runs PHP_CodeSniffer phpcs      |
| composer run phpcbf | Runs PHP_CodeSniffer phpcbf     |
| composer run test   | Runs all tests                  |
| composer run test-coverage | Generates test coverage report  |

## Documentation

[Full documentation here](https://github.com/danieltrolezi/laravel-app/blob/master/docs/index.md)
