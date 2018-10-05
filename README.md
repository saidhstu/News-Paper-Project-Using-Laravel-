# BizzNieuws
**Requirements**

 - PHP 7.1+
 - Composer for PHP
 - PHP OpenSSL Extension
 - PHP PDO Extension
 - PHP Mbstring extension
 - PHP Tokenizer extension
 - PHP XML extension
 - Node.js (was developed with version 9.2.0)

**Running BizzNieuws**

*Setup*:

1. Clone (or download) the repository
2. Execute these commands: `composer install (or php composer.phar update)` and `npm install`
3. Create a copy of the `.env.example` file and name it `.env` and edit it to fit your system setup
4. Create the database: `php artisan migrate`
5. Populate the database: `php artisan db:seed`

*Setup steps that delano forgot to include because he is stupid*:

1. generate key with `php artisan key:generate`
2. Add categories to database
3. Fix `item_keytype` paths in database
4. Link storage with `php artisan storage:link`
5. Set QUEUE_DRIVER in `.env` to `database`


*Starting*:

To start BizzNieuws use the `php artisan serve` command

