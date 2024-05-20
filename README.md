## Out of the Box Laravel Application

### Jumpstart Laravel Development with a Feature-Rich Base Application

This repository provides a solid foundation for your next Laravel project, accelerating development with a pre-configured environment.

#### Key Technologies:

* Backend: PHP 8.3 + Laravel 11 Framework
* Database: MySQL 8.*
* Containerization: Docker
* Web Server: Nginx
* PHP Processor: PHP-fpm
* Process Supervisor: Supervisord

#### Streamlined Development Experience:

* Installation Script: Simplifies the initial setup process
* Code Sniffer (PSR-12): Enforces consistent and clean coding practices
* Git Hooks: Automates tasks within your development workflow
* Unit Testing: Ensures the functionality of your code

#### Planned Enhancements:

* Xdebug Integration: For improved debugging capabilities
* Parallel Test Execution: For faster testing cycles
* Logging and Open Telemetry: For enhanced application monitoring
* Static Code Analysis
* MongoDB

By leveraging this base application, you can benefit from a well-structured foundation that promotes efficient and maintainable development practices for your Laravel projects.

### Setup

#### Requirements

* [Docker Setup on Ubuntu](https://github.com/danieltrolezi/laravel-app/blob/master/docs/01-setup/docker.md)

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
| composer run phpcs  | Runs PHP_CodeSniffer phpcs      |
| composer run phpcbf | Runs PHP_CodeSniffer phpcbf     |
| composer run test   | Runs all tests                  |
| composer run test-coverage | Generates test coverage report  |

## Documentation

[Full documentation here](https://github.com/danieltrolezi/laravel-app/blob/master/docs/index.md)
