<?php

namespace Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ViewRecord;
use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource;

class ViewTheme extends ViewRecord
{
    protected static string $resource = ThemeResource::class;

    protected function getActions(): array
    {
        return [
            ButtonAction::make('deploy')
            ->action(fn() => \Codewithdiki\FilamentThemeManager\Jobs\PreparingDeployJob::dispatch($this->record))
            ->requiresConfirmation(),
            ButtonAction::make('reClone')
            ->color('secondary')
            ->requiresConfirmation()
            ->action(fn() => \Codewithdiki\FilamentThemeManager\Jobs\PreparingCloneJob::dispatch($this->record))
        ];
    }
}
