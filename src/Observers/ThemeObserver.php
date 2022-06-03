<?php

namespace Codewithdiki\FilamentThemeManager\Observers;

use Codewithdiki\FilamentThemeManager\Models\Theme;

class ThemeObserver
{
    /**
     * Handle the Theme "created" event.
     *
     * @param  \App\Models\Theme  $theme
     * @return void
     */
    public function created(Theme $theme)
    {
        \Codewithdiki\FilamentThemeManager\Jobs\PreparingCloneJob::dispatch($theme);
    }

    /**
     * Handle the Theme "updated" event.
     *
     * @param  \App\Models\Theme  $theme
     * @return void
     */
    public function updated(Theme $theme)
    {
        //
    }

    /**
     * Handle the Theme "deleted" event.
     *
     * @param  \App\Models\Theme  $theme
     * @return void
     */
    public function deleted(Theme $theme)
    {
        //
    }

    /**
     * Handle the Theme "restored" event.
     *
     * @param  \App\Models\Theme  $theme
     * @return void
     */
    public function restored(Theme $theme)
    {
        //
    }

    /**
     * Handle the Theme "force deleted" event.
     *
     * @param  \App\Models\Theme  $theme
     * @return void
     */
    public function forceDeleted(Theme $theme)
    {
        //
    }
}
