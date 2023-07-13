Requirements
    - php version 8.2.6
    - mysql version 8
    - composer version 2.5.7

Setup the laravel environment
    - Inside the project folder run the command composer install
    - create a .env file, copy past the contents of .env.example
    - Then update the db details in the .env file

Running the migrations
    - Run the php artisan migrate, this will populate all the neccessary tables in the database
    - Run php artisan config:cache
    - Run php artisan routes:cache

For running server
    - Run php artisan serve

For running the automated test
    - create the .env.testing file from .env.example
        then add the following details in the file
        DB_CONNECTION=
        DB_TEST_HOST=
        DB_TEST_PORT=
        DB_TEST_DATABASE=
        DB_TEST_USERNAME=
        DB_TEST_PASSWORD=
    - Note both the production db and test db should be different
    - Run the php artisan migrate --database=test_db_name, this will populate all the neccessary tables in the database
    - Run php artisan test for checking the php units result

For the removal of expired records
    - Run php artisan delete-expired-cart-items
