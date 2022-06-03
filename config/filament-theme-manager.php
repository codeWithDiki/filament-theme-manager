<?php


return [
    /*
    | ---------------------------------------------------------
    |   Theme Model
    | ---------------------------------------------------------
    | Used for store theme repository data
    */
    'theme_model' => \Codewithdiki\FilamentThemeManager\Models\Theme::class,


    /*
    | ---------------------------------------------------------
    |   Theme Location Directory
    | ---------------------------------------------------------
    | Directory used for saving your theme files from repository
    */
    'theme_directory' => env('THEME_DIRECTORY', base_path('themes'))

];