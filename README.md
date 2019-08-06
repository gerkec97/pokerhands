# Installation and running

Clone the repository and run this command from the directory in which you want to install
    
    composer install

Check that the upload directory is writable. When in doubt run

    chmod 777 uploads
    
The database is already set up but in case you need to regenerate it, use the following command

    php ./bin/SQLLIteCreateTable.php

Start the application using the php built-in webserver with the following command

    composer install

Note that the application has been developed using PHP 7

    PHP 7.1.23 (cli) (built: Feb 22 2019 22:19:32) ( NTS )

# General Notes

Application is built using the Slim Framework v3 and my code that is not framework related boilerplate can be found in 

    ./src/PokerHands/* 

Two routes are set up to server the index page and handle the file upload

    ./src/routes.php
    
The file upload is handled by the controller
   
    ./src/PokerHands/Controller/ImportController.php

## To be done....
    
* Add some unit tests, especially to validate the algorithm that calculates the hand value.
* Replace the SQLite with a DB Server
* Containerize the application. (There is already a docker-compose.yml but this hasn't been checked)