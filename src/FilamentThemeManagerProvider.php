<?php

namespace Codewithdiki\FilamentThemeManager;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource;

class FilamentThemeManagerProvider extends PluginServiceProvider
{

    protected array $resources = [
        ThemeResource::class,
    ];

    protected array $pages = [
        \Codewithdiki\FilamentThemeManager\Filament\Pages\ThemeSetting::class
    ];

    public function configurePackage(Package $package) : void
    {
        $package->name('filament-theme-manager')
        ->hasViews()
        ->hasMigrations(['create_themes'])
        ->hasConfigFile();
    }
}
