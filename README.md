

## Prueba Practica Backend Apok by Omar Montero

This is a project requested by the Apok group, the inital idea of this projects is from them. this is an implementation of that test, so lets talk about what we need to run this project.


## Requirements
- [Laragon 6.0](https://laragon.org/).
- [php ^8.1](https://www.php.net/downloads).
- [Composer version ^2.5.8](https://getcomposer.org/).
- [laravel 10](https://laravel.com/).

## Step by Step.
Once you had cloned teh project in a console go to the root folder of the project and type

    composer install

then use the **.env.example** and remove the ".example " from the name, and chekc it out.

So far waht we need is to work with the Database condigurations more specifically the DB_DATABASE, why beacuse we need a virgin DB, you can support yourself with laragon/phpMyAdmin since laragon has everything, create a database with the same name as the **DB_DATABASE** env variable, and you should be ready to go, in case you want a different name for the DB you have to set the value of with that name **DB_DATABASE**.

Once all of that is done, teh next step is to migrate the database just use this command:

    php artisan migrate --path=database\migrations\2024_05_08_214306_create_nodes_table.php
    
After this we need to seed the DB with the next command:

    php artisan db:seed --class=NodeSeeder

And that should be it to run the project us the command:

    php artisan serve
you should see something like this
    
    INFO  Server running on [http://127.0.0.1:8000].
    
    Press Ctrl+C to stop the server

Now the app is ready to go.


## API Documentation.

To access the api documentation the porject mus be running with:
    
    php artisan serve

Go to the endpoint 

    http://127.0.0.1:8000/api/documentation

and you should be ready to go see it.

## Testing.
In order to test using the php unit testing you can use this command:

    "./vendor/bin/phpunit" tests/Unit/RoomTest.php
In case you want to test using a postman collection, said collection shoulf be place in this directory on teh root folder of the project:

    PostmanCollection\Prueba Tecnica Backend Apok.postman_collection.json
    
## In Case of errors and weird behavior

Kill the process and run this commands:

    php artisan route:cache

    php artisan cache:clear
After this it should be more than enough just run:

    php artisan serve

And everything shoulf be back to normal.

In case of any questions and consultions, hit me up at this email address omarjo94@gmail.com i should answer in a short time.
    
