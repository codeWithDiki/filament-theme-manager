<?php

namespace Codewithdiki\FilamentThemeManager\Actions;

use Codewithdiki\FilamentThemeManager\DTO\GetGitUrlData;
use Codewithdiki\FilamentThemeManager\Enum\GitProviderEnum;
use Codewithdiki\FilamentThemeManager\Enum\GitConnectionEnum;


class NavigateGitUrlAction
{
    public function run(GetGitUrlData $urlDTO) : ?string
    {
        return match($urlDTO->connection_type){
            GitConnectionEnum::HTTPS()->value => match($urlDTO->provider){
                GitProviderEnum::GITHUB()->value => "https://github.com/",
                GitProviderEnum::GITLAB()->value => "https://gitlab.com/",
                default => null
            },
            GitConnectionEnum::SSH()->value => match($urlDTO->provider){
                GitProviderEnum::GITHUB()->value => "git@github.com:",
                GitProviderEnum::GITLAB()->value => "git@gitlab.com:",
                default => null
            },
            default => null
        };
    }
}