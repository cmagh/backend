# Prueba de conocimientos backend laravel developer

## Acerca de la prueba: 

Se creó crud de usuarios y productos, ademas el proyecto cuenta con autenticación OAuth2.0 con laravel passport, las rutas que tienen autencicación se detallan acontinuación. 

### Con autenticación
- Producto
    - Crear producto
    - Modificar producto
    - Eliminar producto
    
- Usuario 
    - Listar usuarios
    - Ver usuario
    - Actualizar usuario
    - Eliminar usuario
    - Logout


### Sin autenticación
- Producto
    - Listar productos 
    - Ver producto 

- Usuario
    - Crear usuario
    - Login 
    - Solicitar reseteo de password
    - Enviar formulario para reseteo de password
    
## Como instalar aplicación 

- Clonar repositorio. 
- Ejecutar comando `composer install` para instalar dependencias.
- Renombrar archivo `.env.example` por `.env`.
- Ejecutar comando `php artisan key:generate`
- Modificar valores del archivo `.env` 
    - Los valores de la base de datos por los de nuestro entorno local
    - Los valores del mail para el envio de reseteo de password
- Ejecutar comando `php artisan passport:install`
- Ejecutar comando `php artisan migrate` para crear tablas en la base de datos. 
- Ejecutar comando `dump-autoload` y luego `php artisan db::seed` para poblar tablas con datos dummy.
- Ejecutar comando `php artisan serve` para probar la aplicación.

## urls del proyecto 
- [Colleccion de pruebas en postman](https://www.getpostman.com/collections/15cab9fd93d1f43f7607)
- [openApi swagger](http://cmagh.me/swagger/)
- [urls API](http://cmagh.me/api/v0.1/)

## dumps base de datos mySql 5.7

En la raiz del proyecto se creo una carpeta llamada dumps la cual contiene dos archivos sql. 
- dump.sql (Contiene las tablas y la data dummy)
- schema.sql (Contiene solamente la definición de las tablas)
