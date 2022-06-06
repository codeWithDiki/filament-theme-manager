<?php

namespace Codewithdiki\FilamentThemeManager;

require_once ('helpers.php');

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Codewithdiki\FilamentThemeManager\Models\Theme;
use Codewithdiki\FilamentThemeManager\Observers\ThemeObserver;
use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource;

class FilamentThemeManagerProvider extends PluginServiceProvider
{

    public static string $name = 'filament-theme-manager';

    protected array $resources = [
        ThemeResource::class,
    ];

    protected array $pages = [
        \Codewithdiki\FilamentThemeManager\Filament\Pages\ThemeSetting::class
    ];

    public function configurePackage(Package $package) : void
    {
        parent::configurePackage($package);

        $package
        ->hasViews()
        ->hasMigrations(['create_themes', 'create_theme_deployment_logs'])
        ->hasConfigFile();
    }

    public function boot()
    {
        parent::boot();

        Theme::observe(ThemeObserver::class);
    }
}
