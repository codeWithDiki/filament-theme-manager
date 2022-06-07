<?php

namespace Codewithdiki\FilamentThemeManager\Filament\Pages;

use Filament\Pages\Page;

class ThemeSetting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chip';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament-theme-manager::filament.theme-setting';
    protected static ?string $navigationGroup = 'Appearance';

    protected $listeners = [
        'themeSettingNotify' => 'transmitNotify'
    ];


    public function transmitNotify($status, $message)
    {
        $this->notify($status, $message);
    }

}
