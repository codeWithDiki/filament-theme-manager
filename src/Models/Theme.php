<?php

namespace Codewithdiki\FilamentThemeManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Theme extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory, \Spatie\MediaLibrary\InteractsWithMedia, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'meta' => 'json'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
        ->registerMediaConversions(function (Media $media) {
            $this
                ->addMediaConversion('thumb')
                ->width(400)
                ->height(400);
        });

        $this->addMediaCollection('image')
        ->registerMediaConversions(function (Media $media) {
            $this
                ->addMediaConversion('small-image')
                ->width(400)
                ->height(400);
        });
    }


    public function parent_theme() : BelongsTo
    {
        return $this->belongsTo(config('filament-theme-manager.theme_model'), 'parent_id');
    }

    public function deployment_logs() : HasMany
    {
        return $this->hasMany(ThemeDeploymentLog::class, 'theme_id')->orderBy('id', 'DESC');
    }

}
