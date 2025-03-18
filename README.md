

## Prueba Practica Backend Apok by Omar Montero

This is a project requested by the Apok group, the initial idea of this project is from them
Here is the link to the repo [Prueba Practica Backend](https://gitlab.com/grupoapok/dev-position-tests/backend/graph-api)
. This is just an implementation of that test, so let's check what we need to run this project.


## Requirements
- [Laragon 6.0](https://laragon.org/).
- [php ^8.1](https://www.php.net/downloads).
- [Composer version ^2.5.8](https://getcomposer.org/).
- [laravel 10](https://laravel.com/).

## Step by Step.
Once you have cloned the project in a console go to the root folder of the project and run the command:

    composer install

Then use the **.env.example** and remove the ".example " from the name, and check it out.

So far what we need is to work with the Database configurations more specifically the DB_DATABASE, why because we need a virgin DB, you can support yourself with laragon/phpMyAdmin since laragon has everything, create a database with the same name as the **DB_DATABASE** env variable, and you should be ready to go, in case you want a different name for the DB you have to set the value of with that name **DB_DATABASE**.

Once all of that is done, the next step is to migrate the database just use this command:

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

To access the API documentation the project must be running with:
    
    php artisan serve

Go to the endpoint 

    http://127.0.0.1:8000/api/documentation

and you should be ready to go, see it.

## Testing.
To test using the php unit testing you can use this command:

    "./vendor/bin/phpunit" tests/Unit/RoomTest.php
In case you want to test using a Postman collection, said collection should be placed in this directory on the root folder of the project:

    PostmanCollection\Prueba Tecnica Backend Apok.postman_collection.json
    
## In Case of errors and weird behavior

Kill the process and run these commands:

    php artisan route:cache

    php artisan cache:clear
After this, it should be more than enough just run:

    php artisan serve

And everything should be back to normal.

In case of any questions and consultations, hit me up at this email address omarjo94@gmail.com, i should answer in a short time.
    
