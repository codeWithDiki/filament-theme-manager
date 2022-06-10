<?php

namespace Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource\Pages;

use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Codewithdiki\FilamentThemeManager\Enum\DeploymentTypeEnum;
use Codewithdiki\FilamentThemeManager\Enum\DeploymentStatusEnum;
use Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource;

class ViewDeploymentLog extends \Filament\Resources\Pages\ViewRecord
{
    protected static string $resource = ThemeResource::class;

    protected static ?string $title = "Deployment Log";

    protected static string $view = 'filament-theme-manager::filament.resources.theme-resource.pages.view-deployment-log';

    protected function getActions(): array
    {
        return [
            Action::make('backToTheme')
            ->url(fn() => route('filament.resources.themes.view', ['record' => $this->record])),
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);

        $log = \Codewithdiki\FilamentThemeManager\Models\ThemeDeploymentLog::find(request()->get('log_id'));


        if(empty($log)){
            abort(404);
        }

        $this->deployment_log = $log;

        static::$title = "Log : {$log->name}";
    }

}
