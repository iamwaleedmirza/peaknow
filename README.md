# PeaksCurative
Peaks Curative is a Digital Pharmacy integrated with Smart Doctors.

## Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

### Installing

Follow these steps to get this project up and running on your local environment

Clone the repository
```
git clone https://github.com/neuralaxiomllc/PeaksCurative.git
```

Switch to the repo folder
```
cd PeaksCurative
```

Install all the dependencies using composer
```
composer install
```

Copy the example env file and make the required configuration changes in the .env file
```
cp .env.example .env
```

Generate a new application key
```
php artisan key:generate
```

Generate a new JWT authentication secret key
```
php artisan jwt:generate
```

Run the database migrations (**Set the database connection in .env before migrating**)
```
php artisan migrate
```

Run this command to seed DB for Admin User & some required fields
```
php artisan db:seed
```

Start the local development server
```
php artisan serve
```

You can now access the server at http://localhost:8000

**TL;DR command list**
```
git clone https://github.com/neuralaxiomllc/PeaksCurative.git
cd PeaksCurative
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan jwt:generate 
```
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)
```
php artisan migrate
php artisan db:seed
php artisan serve
```
***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command
```
php artisan migrate:refresh
```

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Laravel](https://laravel.com/docs/8.x) - The web framework used
* [Composer](https://getcomposer.org/) - Dependency Management

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

