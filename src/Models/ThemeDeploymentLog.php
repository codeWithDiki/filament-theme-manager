<?php

namespace Codewithdiki\FilamentThemeManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeDeploymentLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'process_end_at' => 'datetime',
        'meta' => 'json'
    ];

    public function theme() : BelongsTo
    {
        return $this->belongsTo(config('filament-theme-manager.theme_model'));
    }
}
