<?php

namespace Codewithdiki\FilamentThemeManager\Filament\Resources\ThemeResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\ButtonAction;
use Codewithdiki\FilamentThemeManager\Enum\DeploymentTypeEnum;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Codewithdiki\FilamentThemeManager\Enum\DeploymentStatusEnum;

class DeploymentLogsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'deployment_logs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = "Deployment Logs";

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
                TextColumn::make('name')
                ->searchable(),
                BadgeColumn::make('status')
                ->formatStateUsing(fn($state) => ucwords($state))
                ->colors([
                    'success' => DeploymentStatusEnum::SUCCESSED()->value,
                    'warning' => DeploymentStatusEnum::PROCESSING()->value,
                    'danger' => DeploymentStatusEnum::FAILED()->value
                ]),
                TextColumn::make('created_at')
                ->label('Process Started')
                ->dateTime('d/m/Y H:i:s'),
                TextColumn::make('process_end_at')
                ->label('Process End')
                ->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('viewLog')
                ->url(fn(Model $record) => route('filament.resources.themes.log_view', ['record' => $record->theme, 'log_id' => $record->id]))
                ->icon('heroicon-o-eye'),
                Action::make('retry')
                ->icon('heroicon-o-refresh')
                ->action(fn(Model $record) => match($record->meta['type'] ?? null){
                    DeploymentTypeEnum::CLONE()->value => \Codewithdiki\FilamentThemeManager\Jobs\RetryingCloneJob::dispatch($record),
                    DeploymentTypeEnum::DEPLOY()->value => \Codewithdiki\FilamentThemeManager\Jobs\RetryingDeployJob::dispatch($record),
                    default => null
                })
                ->requiresConfirmation()
                ->visible(fn(Model $record) => $record->status == DeploymentStatusEnum::FAILED()->value)
                ->color('danger'),
            ])
            ->bulkActions([])
            ->headerActions([]);
    }
}
