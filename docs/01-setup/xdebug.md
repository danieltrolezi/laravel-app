## Setting Up Xdebug on VSCode

Xdebug is already installed and configured. To enable VSCode integration follow the steps bellow.

1. Install PHP Debug plugin: https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug

2. Click on "Run and Debug" tab on VSCode and add new configuration in `launch.json` file:
```
{
    "name": "Listen for Xdebug",
    "type": "php",
    "request": "launch",
    "port": 9003,
    "pathMappings": {
        "/var/www/laravel-app": "${workspaceRoot}"
    }
}
```

## References

* [XDebug e Docker - Configurar PHPStorm e VSCode para funcionar com XDebug usando Docker | Dias de Dev](https://www.youtube.com/watch?v=kbq3FJOYmQ0)
* [Setup Step Debugging in PHP with Xdebug 3 and Docker Compose](https://matthewsetter.com/setup-step-debugging-php-xdebug3-docker/)
* [Xdebug: entenda como funciona e como resolver problemas na sua configuração](https://www.magenteiro.com/blog/magento-2/desenvolvimento-m2/xdebug-phpstorm-vscode-como-configurar/)