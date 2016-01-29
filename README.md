# API Resource Generator for Laravel 5.2

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

[![Total Downloads](https://img.shields.io/packagist/dt/iramgutierrez/laravel-resource-api.svg?style=flat-square)](https://packagist.org/packages/iramgutierrez/laravel-resource-api)

Full resource generator for API RESTful, 

This package implements the layers pattern design, include:

* Entity
* Controller
* Manager
* Validator
* Repository
* Migration
* Route
* Documentation (Require [apidocjs](http://apidocjs.com) installed)

## Installation

Add the following line to your `composer.json` file:

```
"iramgutierrez/laravel-resource-api": "1.0.*"
```

Run `composer update` to get the package.

Once composer has installed the package add this line of code to the `providers` array located in your `config/app.php` file:

```
IramGutierrez\API\APIServiceProvider::class,
```

Add this line of code to the `$commands` protected array located in your `app/Console/Kernel.php` file:

```
\IramGutierrez\API\ResourceAPI::class,
```

### Migrations and Configuration Publishing
Run `php artisan vendor:publish` to publish this package configuration and migrations. Afterwards you can edit the file `config/resource_api.php` to set the namespace for the resources generated.

Run migration to create required tables

```
php artisan migrate
```

## Usage

Now, you should have available the Artisan command: `create-resource-api` and can be used like this:

```
php artisan create-resource-api {Entity}
```

### Example

Run 

```
php artisan create-resource-api Test
```

And, the command ask you for the table name:

```
Table name [tests]:
```

namespace:

```
Path name [API]:
```

prefix route:

```
Prefix route []:
```

if do you want generate documentation:

```
Generate documentation? (Require apidocjs) (yes/no) [yes]:
```

if do you want generate the migration file:

```
Generate migration? (yes/no) [yes]:
```

if do you want run migration:

```
Run migration? (yes/no) [yes]:
```

if do you want add the route resource in `routes.php` file:

```
Add routes resource? (yes/no) [yes]:
```

and if do you want add some middleware:

```
Middlewares or middleware groups (comma separated) []:
```

When the execution is finished, you should have available the following functional routes:

| Route         | Method        | Uses  | Action |
| ------------- |:-------------:| -----:| ------:|
| /tests      | GET | API\TestController@index |Request all tests |
| /tests      | POST | API\TestController@store | Store a test|
| /tests/:id      | GET | API\TestController@show | Request a specific test |
| /tests/:id      | PUT | API\TestController@update | Update a specific test |
| /tests/:id      | DELETE | API\TestController@destroy | Delete a specific test |





## License

The Laravel Resource API package is released under [the MIT License](LICENSE).
