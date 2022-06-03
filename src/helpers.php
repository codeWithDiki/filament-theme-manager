<?php


if(!function_exists('theme_directory')){
    function theme_directory() : string
    {
        return config('filament-theme-manager.theme_directory', base_path('themes'));
    }
}