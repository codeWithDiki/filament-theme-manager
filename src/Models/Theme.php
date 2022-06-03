<?php

namespace Codewithdiki\FilamentThemeManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Theme extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory, \Spatie\MediaLibrary\InteractsWithMedia;

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

}
