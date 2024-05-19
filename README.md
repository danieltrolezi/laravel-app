
## Laravel App

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

### Coomands

Commands must run inside the container.

| Command             | Description                     |
| ------------------- | ------------------------------- |
| composer run phpcs  | Runs PHP_CodeSniffer phpcs<br>  |
| composer run phpcbf | Runs PHP_CodeSniffer phpcbf<br> |

### Documentation

[Full documentation here](https://github.com/danieltrolezi/laravel-app/blob/master/docs/index.md)
