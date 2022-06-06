<?php

namespace Codewithdiki\FilamentThemeManager\DTO;


class DeploymentData extends \Spatie\DataTransferObject\DataTransferObject
{
    public int $theme_id;

    public ?int $parent_id;

    public string $name;

    public string $repository;

    public string $branch;

    public string $git_username;

    public string $connection_type;

    public ?string $status = 'pending';

    public ?string $commit;

    public ?array $meta;
}