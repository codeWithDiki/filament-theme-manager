<?php


namespace Codewithdiki\FilamentThemeManager\Enum;


use Closure;
use Spatie\Enum\Enum;

/**
 * @method static self MIX()
 * @method static self VITE()
 */
class AssetCompilerEnum extends Enum
{
    protected static function values(): Closure
    {
        return function (string $name): string|int {
            return mb_strtolower($name);
        };
    }
}
