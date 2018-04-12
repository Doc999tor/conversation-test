# PHP-Slim-starter-kit

Simple but functional skeleton of PHP Slim project

What included?
* Twig views templating and some Slim specific functions for Twig
* Database connection and simple commented query in `/hello/{name}`. Selected database is MS SQL. You can easily switch it to another database by changing the connection string in [index.php line 28](https://github.com/Doc999tor/PHP-Slim-starter-kit/blob/e17cef9dacc3f864af20c70d1d59eb784b2e5788/index.php#L28)
* Routes resolved with callbacks and controllers
  * Example of route group
* Middleware template
* Caching structure for views and routes, replace the `false` with a path to cache files
* Autoloading from `lib` directory with prefixes `\Lib` namespace

---
How to start:
1. Open Git terminal and run
2. `git clone https://github.com/Doc999tor/PHP-Slim-starter-kit.git slim-project`
3. `cd slim-project`
4. `composer install`
5. `php -S localhost:3000`. PHP executable has to be in your PATH
6. Open your browser and type `localhost:3000`

---
Requirements:
* PHP 7.1
* [Composer](https://getcomposer.org/doc/00-intro.md)
