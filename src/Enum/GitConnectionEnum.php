<?php


namespace Codewithdiki\FilamentThemeManager\Enum;


use Closure;
use Spatie\Enum\Enum;

/**
 * @method static self HTTPS()
 * @method static self SSH()
 */
class GitConnectionEnum extends Enum
{
    protected static function values(): Closure
    {
        return function (string $name): string|int {
            return mb_strtolower($name);
        };
    }
}
