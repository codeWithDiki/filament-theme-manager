<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Codewithdiki\FilamentThemeManager\Models\ThemeSetting;
use Codewithdiki\FilamentThemeManager\Models\ThemeDeploymentLog;


if(!function_exists('theme_directory')){
    function theme_directory() : string
    {
        return config('filament-theme-manager.theme_directory', base_path('themes'));
    }
}


if(!function_exists('theme_deployment_log_writer'))
{
    function theme_deployment_log_writer(ThemeDeploymentLog $log, array $output) : ThemeDeploymentLog
    {
        if(isset($log->meta['output'])){
            $log_output = array_merge($log->meta['output'] ?? [], $output);

            $log->meta = array_merge($log->meta, [
                "output" => $log_output
            ]);

            $log->save();

            return $log;
        }


        $log->meta = array_merge($log->meta ?? [], [
            'output' => $output
        ]);

        $log->save();

        return $log;
        
    }
}


if(!function_exists('get_theme_setting')){
    function get_theme_setting(string $key, $default = null) : null|string|int
    {
        $model = config('filament-theme-manager.theme_setting_model', ThemeSetting::class);

        $setting = $model::where('key', $key)->first();

        if(empty($setting)){
            return $default;
        }

        return $setting->value ?? $default;
    }
}


if(!function_exists('set_theme_setting')){
    function set_theme_setting(string $key, $value) : bool
    {
        $model = config('filament-theme-manager.theme_setting_model', ThemeSetting::class);

        try{
            DB::beginTransaction();

            $setting = $model::updateOrCreate([
                'key' => $key
            ], [
                'key' => $key,
                'value' => $value
            ]);

            if(empty($setting)){
                throw new \Exception('Failed to create theme setting');
            }


            DB::commit();

            return true;
        } catch(\Exception $e){
            DB::rollBack();
            \Illuminate\Support\Facades\Log::alert($e->getMessage());
            return false;
        }
    }
}

if(!function_exists('get_themes')){
    function get_themes() : Collection
    {
        $model = config('filament-theme-manage.theme_model', \Codewithdiki\FilamentThemeManager\Models\Theme::class);

        return $model::all();
    }
}

if(!function_exists('get_active_theme')){
    function get_active_theme() : ?Model
    {
        $model = config('filament-theme-manage.theme_model', \Codewithdiki\FilamentThemeManager\Models\Theme::class);

        return $model::find(get_theme_setting('active_theme', 0));
    }
}


if(!function_exists('get_theme_path')){
    function get_theme_path() : ?string
    {
        $theme = get_active_theme();

        if(empty($theme)){
            return null;
        }

        if($theme->parent_theme){
            return "{$theme->parent_theme->vendor}/{$theme->directory}";
        }

        return "{$theme->vendor}/{$theme->directory}";
    }
}