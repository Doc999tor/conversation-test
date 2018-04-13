# conversation-test

Simple RESTful project based on [Sakila](https://dev.mysql.com/doc/sakila/en/) database
Enables to search movies by multiple free-text search parameters, records every search request: when, what and how many rows returned

Enables live predictions based on existing lists of actors, categories and languages
Search results show up as you type

---
How to run the project:
1. Clone the project
2. Install `sakila-schema.sql` and afterwards `sakila-data.sql` (MySQL)
3. Run from the terminal: `composer install` and `php -S localhost:9000`
4. Enter `localhost:9000` in the browser, make sure you use latest version of evergreen browser, Chrome is the preferred one
5. You can try api:
    1. `http://localhost:9000/api/films?actor=john&category=horror&description=szdxfsdfds`
    2. `http://localhost:9000/api/actors`
    3. `http://localhost:9000/api/languages`
    4. `http://localhost:9000/api/languages`
    5. `http://localhost:9000/api/films/log`

---
Live version: will be added