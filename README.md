## Technologies Used

This project is developed using the following technologies:

- **Laravel:** A web framework provides a structure and starting point for creating your application, allowing you to focus on creating something amazing while we sweat the details.
- **Laravel Passport:** provides a full OAuth2 server implementation for your Laravel application in a matter of minutes.
- **pusher-php-server:** provide a more efficient alternative to continually polling your application's server for data changes that should be reflected in your UI.

## Features

- Built with Laravel 11.31
- OAuth2.0
- RESTful API support
- Database migrations and seeding
- Pusher Broadcasting

## Requirements

- PHP >= 8.2
- Composer
- MySQL or other supported databases
- nginx or Apache >= 2.4.62

## Getting Started

### Installation

```bash
$ git clone git@gitlab.ankh-local.com:master/general-01/ankh-starter-api.git

$ composer install

$ create .env files

$ php artisan migrate

$ php artisan passport:install
```

### Basic usage

```bash
$ php artisan serve --host={YOUR IP} --port=19192
```