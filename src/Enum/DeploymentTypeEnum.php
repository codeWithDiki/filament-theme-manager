<?php


namespace Codewithdiki\FilamentThemeManager\Enum;


use Closure;
use Spatie\Enum\Enum;

/**
 * @method static self CLONE()
 * @method static self DEPLOY()
 */
class DeploymentTypeEnum extends Enum
{
    protected static function values(): Closure
    {
        return function (string $name): string|int {
            return mb_strtolower($name);
        };
    }
}
