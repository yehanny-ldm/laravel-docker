
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Auroblog por Alejandro Becerra

Instrucciones de instalación

- Crear .env con base a .env.example
```
    cp .env.example .env //
```
- Construya los contenedores docker
```
    docker-compose build
```
- Monte los contenedores docker
```
    docker-compose up -d
```
- Instale paquetes composer
```
    composer install && composer update
```
- Inicialice dump-autoload
```
    composer dump-autoload
``` 
- Instale paquete npm
```
    npm install
``` 
- Migrar y llenar base de datos **SQLite**
```
    php artisan migrate && php artisan db:seed
```

- Ejecute por separado los siguientes comando
```
    php artisan serve
    npm run watch
```  
- Ingresar a localhost

- Para ingresar:
```
    email: admin@admin.com
    password: 123456
```
## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [a.becor94@gmail.com](mailto:a.becor94@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
