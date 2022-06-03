<?php


namespace Codewithdiki\FilamentThemeManager\Enum;


use Closure;
use Spatie\Enum\Enum;

/**
 * @method static self GITLAB()
 * @method static self GITHUB()
 */
class GitProviderEnum extends Enum
{
    protected static function values(): Closure
    {
        return function (string $name): string|int {
            return mb_strtolower($name);
        };
    }
}
