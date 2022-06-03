<?php

namespace Codewithdiki\FilamentThemeManager\Filament\Resources;

use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource\Pages;
use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ThemeResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Appearance';

    public static function getModel(): string
    {
        return config('filament-theme-manager.theme_model') ?? (string) Str::of(class_basename(static::class))
            ->beforeLast('Resource')
            ->prepend('App\\Models\\');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThemes::route('/'),
            'create' => Pages\CreateTheme::route('/create'),
            'edit' => Pages\EditTheme::route('/{record}/edit'),
        ];
    }
}
