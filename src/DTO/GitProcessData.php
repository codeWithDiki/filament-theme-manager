<?php

namespace Codewithdiki\FilamentThemeManager\DTO;


class GitProcessData extends \Spatie\DataTransferObject\DataTransferObject
{
    public string $repository;
    
    public string $branch;

    public string $vendor;

    public string $git_username;

    public ?string $git_password;

    public string $connection_type;

    public string $directory;

    public \Codewithdiki\FilamentThemeManager\Models\ThemeDeploymentLog $log;

}