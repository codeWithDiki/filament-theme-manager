<?php


return [
    /*
    | ---------------------------------------------------------
    |   Theme Models
    | ---------------------------------------------------------
    | Used for store theme repository data & theme settings
    */
    'theme_model' => \Codewithdiki\FilamentThemeManager\Models\Theme::class,
    'theme_setting_model' => \Codewithdiki\FilamentThemeManager\Models\ThemeSetting::class,


    /*
    | ---------------------------------------------------------
    |   Theme Location Directory
    | ---------------------------------------------------------
    | Directory used for saving your theme files from repository
    */
    'theme_directory' => env('THEME_DIRECTORY', base_path('themes')),

    /*
    | ---------------------------------------------------------
    |   Theme Assets file name
    | ---------------------------------------------------------
    | Theme style and script file name ex : js/app.js
    */
    'theme_style' => env('THEME_STYLE_NAME', 'css/app.css'),
    'theme_script' => env('THEME_SCRIPT_NAME', 'js/app.js'),

];