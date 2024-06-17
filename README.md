## Out of the Box Laravel Application

![status](https://img.shields.io/badge/status-work%20in%20progress-green?style=flat)
![version](https://img.shields.io/badge/version-0.4.1-blue?style=flat)

### Jumpstart Laravel Development with a Base Application

This repository provides a solid foundation for your next Laravel project, accelerating development with a pre-configured environment.

#### Key Technologies:

* Backend: PHP 8.3 + Laravel 11 Framework
* Databases: MySQL 8.*, Redis
* Containerization: Docker
* Web Server: Nginx
* PHP Processor: PHP-fpm
* Process Supervisor: Supervisord

#### Streamlined Development Experience:

* Installation Script: Simplifies the initial setup process
* Code Sniffer (PSR-12): Enforces consistent and clean coding practices
* Git Hooks: Automates tasks within your development workflow
* Unit Testing: Ensures the functionality of your code
* Xdebug Integration: For improved debugging capabilities

#### Planned Enhancements:

* Logging and Open Telemetry
* CI/CD
* JIT
* Secret and Env Vault
* Swagger
* Static Code Analysis
* Parallel Test Execution
* MongoDB
* Installation Enchancements

By leveraging this base application, you can benefit from a well-structured foundation that promotes efficient and maintainable development practices for your Laravel projects.

### Setup

#### Requirements

* [Setting Up Docker on Ubuntu](https://github.com/danieltrolezi/laravel-app/blob/master/docs/01-setup/docker.md)

#### Setting up the Environment

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

### Commands

Commands must run inside the container.

| Command             | Description                     |
| ------------------- | ------------------------------- |
| composer phpcs      | Runs PHP_CodeSniffer phpcs      |
| composer phpcbf     | Runs PHP_CodeSniffer phpcbf     |
| composer test       | Runs all tests                  |
| composer test-coverage | Generates test coverage report  |

### Documentation

[Full documentation available here](https://github.com/danieltrolezi/laravel-app/blob/master/docs/index.md)
