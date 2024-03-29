<?php

namespace Codewithdiki\FilamentThemeManager;

require_once ('helpers.php');

use Livewire\Livewire;
use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Codewithdiki\FilamentThemeManager\Models\Theme;
use Codewithdiki\FilamentThemeManager\Enum\AssetCompilerEnum;
use Codewithdiki\FilamentThemeManager\Observers\ThemeObserver;
use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource;

class FilamentThemeManagerProvider extends PluginServiceProvider
{

    public static string $name = 'filament-theme-manager';

    protected array $pages = [
        \Codewithdiki\FilamentThemeManager\Filament\Pages\ThemeSetting::class
    ];

    public function configurePackage(Package $package) : void
    {
        parent::configurePackage($package);

        $package
        ->hasViews()
        ->hasMigrations(['create_themes', 'create_theme_deployment_logs', 'create_theme_settings'])
        ->hasConfigFile();
    }

    public function boot()
    {
        parent::boot();

        $theme_model = config('filament-theme-manager.theme_model', Theme::class);

        $theme_model::observe(ThemeObserver::class);
        Livewire::component('theme-setting', \Codewithdiki\FilamentThemeManager\Http\Livewire\Form\ThemeSetting::class);

        Filament::serving(function () {
            $theme = get_active_theme();
            
            if($theme?->meta['apply_on_admin'] ?? false){

                if($theme->asset_compiler == AssetCompilerEnum::MIX()->value){
                    Filament::registerTheme(theme_asset(config('filament-theme-manager.theme_style', 'css/filament.css')));
                }

                if($theme->asset_compiler == AssetCompilerEnum::VITE()->value){
                    Filament::registerViteTheme(theme_asset(config('filament-theme-manager.theme_style', 'css/filament.css')));
                }

            }

        });

    }

    protected function getResources() : array
    {
        return array_merge([], [
            config('filament-theme-manager.theme_resource', ThemeResource::class)
        ]);
    }

}
