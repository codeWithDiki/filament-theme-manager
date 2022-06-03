<?php

namespace Codewithdiki\FilamentThemeManager\DTO;


class GetGitUrlData extends \Spatie\DataTransferObject\DataTransferObject
{
    public string $connection_type;

    public string $provider;
}