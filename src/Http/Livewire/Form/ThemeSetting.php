<?php

namespace Codewithdiki\FilamentThemeManager\Http\Livewire\Form;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;

class ThemeSetting extends Component implements \Filament\Forms\Contracts\HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    public function mount() : void
    {
        $this->form->fill([
            "active_theme" => get_theme_setting('active_theme'),
            "gitlab_username" => get_theme_setting('gitlab_username'),
            "gitlab_password" => get_theme_setting('gitlab_password'),
            "github_username" => get_theme_setting('github_username'),
            "github_password" => get_theme_setting('github_password'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
            ->schema([
                Select::make('active_theme')
                ->options(get_themes()->pluck('name', 'id'))
            ]),
            Grid::make([
                'default' => 1,
                'lg' => 4
            ])->schema([
                Grid::make([
                    'default' => 1
                ])->schema([
                    Section::make('Gitlab Account')
                    ->description('Uses for non SSH auth clone / deploy theme')
                    ->schema([
                        TextInput::make('gitlab_username')
                        ->label('Gitlab Username'),
                        TextInput::make('gitlab_password')
                        ->label('Gitlab Password')
                        ->type('password')
                    ])
                ])->columnSpan(2),
                Grid::make([
                    'default' => 1
                ])->schema([
                    Section::make('Github Account')
                    ->description('Uses for non SSH auth clone / deploy theme')
                    ->schema([
                        TextInput::make('github_username')
                        ->label('Github Username'),
                        TextInput::make('github_password')
                        ->label('Github Password')
                        ->type('password')
                    ])
                ])->columnSpan(2),
            ])
        ];
    }

    public function submit() : void
    {
        $this->form->validate();

        try{
            DB::beginTransaction();

            $data = $this->form->getState();

            foreach($data as $key => $value){
                set_theme_setting($key, $value);
            }

            DB::commit();
            $this->emit('themeSettingNotify', 'success', 'Settings saved!');
        } catch(\Exception $e){
            DB::rollBack();
            $this->emit('themeSettingNotify', 'danger', $e->getMessage());
        }
    }

    public function render()
    {
        return view('filament-theme-manager::livewire.theme-setting');
    }
}
