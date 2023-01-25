# filament-theme-manager

## Geting Started

### Things You Should Know Before Installing This Plugin
This plugin is stand above hexadog/laravel-themes-manager and filament/filament so before you install this plugin I recommend you to learn about them first.
This plugin is require you to run a proccess in a queue. Use Laravel Horizon or other tools to run queue and also i recommend you to use redis.

### Install Guide

```
composer require codewithdiki/filament-theme-manager
```
In your config/app.php place this code in you providers section

```
'providers' => [

        ...

        /*
         * Package Service Providers...
         */
        Codewithdiki\FilamentThemeManager\FilamentThemeManagerProvider::class,

        ...

    ],
```

### Publish Views And Migration
```
php artisan vendor:publish --tag=filament-theme-manager-migrations

php artisan vendor:publish --tag=filament-theme-manager-views
```

### Migrate 
```
php artisan migrate
```