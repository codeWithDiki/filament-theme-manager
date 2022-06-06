<?php

namespace Codewithdiki\FilamentThemeManager\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FilamentThemeManagerMiddleware extends \Hexadog\ThemesManager\Http\Middleware\ThemeLoader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $theme = null)
    {
        $theme = get_theme_path();
        $is_apply_only_on_filament = (bool)get_theme_setting('only_apply_on_filament_admin', false);
        $filament_path = config('filament.path');

        

        if($is_apply_only_on_filament){
            if(!str_contains($request->path(), "{$filament_path}/")){
                $theme = null;
            }
        }

        // Call parent Middleware handle method
        return parent::handle($request, $next, $theme);
    }
}
