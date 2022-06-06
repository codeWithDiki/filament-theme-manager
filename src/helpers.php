<?php

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