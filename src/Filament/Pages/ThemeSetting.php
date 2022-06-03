<?php

namespace Codewithdiki\FilamentThemeManager\Filament\Pages;

use Filament\Pages\Page;

class ThemeSetting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-theme-manager::filament.theme-setting';
    protected static ?string $navigationGroup = 'Appearance';
}
