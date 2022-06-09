Para poder utilizar y probar este proyecto es necesario tener instalado: Laravel,mysql y xampp o ISS.

Ademas se debe de crear una base de datos llamada licoreria, para acceder a la BD se tiene como usuario root y como password root

Para la creacion de las tablas en la BD se utilizan migraciones, para la creacion de debe ejecutar el siguiente comando php artisan migrate

En cuanto al llenado de las tablas de productos y proveedores se utilizan seeders que llenara la informacion con datos random, se utilizara el siguiente comando php artisan php artisan db:seed 

Es de suma importancia hacer la migracion y los seeders para que la aplicacion funcione correctamente.
