# filament-theme-manager

## Geting Started

### Things You Should Know Before Installing This Plugin
This plugin is stand above hexadog/laravel-themes-manager and filament/filament so before you install this plugin I recommend you to learn about them first.
This plugin is require you to run a proccess in a queue. Use Laravel Horizon or other tools to run queue and also i recommend you to use redis.

### READ THIS
If you install this plugin before vite is supported you should create new migrations for themes table, your migration should looks like this

```
    public function up()
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->string('asset_compiler')->default('mix');
        });
    }
```

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

![image](https://user-images.githubusercontent.com/62064510/216070237-71ade92c-5e45-4d01-ba87-b7194f39d41b.png)
![image](https://user-images.githubusercontent.com/62064510/216070303-7b2f7d76-6de0-455d-aeed-81d303310d27.png)
