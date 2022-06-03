<?php

namespace Codewithdiki\FilamentThemeManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Codewithdiki\FilamentThemeManager\DTO\GetGitUrlData;
use Codewithdiki\FilamentThemeManager\Enum\GitProviderEnum;
use Codewithdiki\FilamentThemeManager\Jobs\Run\RunCloneJob;
use Codewithdiki\FilamentThemeManager\Enum\GitConnectionEnum;

class PreparingCloneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public \Codewithdiki\FilamentThemeManager\Models\Theme $theme
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try{

            $urlDTO = new GetGitUrlData([
                'connection_type' => $this->theme->connection_type,
                'provider' => $this->theme->git_provider
            ]);
    
            $repository = (new \Codewithdiki\FilamentThemeManager\Actions\NavigateGitUrlAction)->run($urlDTO);
    
            if(empty($repository)){
                throw new \Exception("Repository URL invalid!");
            }
    
            $repository = "{$repository}{$this->theme->git_username}/{$this->theme->git_repository}.git";
    
            $cloneData = new \Codewithdiki\FilamentThemeManager\DTO\GitCloneData([
                "repository" => $repository,
                "branch" => $this->theme->git_branch,
                "vendor" => $this->theme->vendor,
                "git_username" => $this->theme->git_username,
                "git_password" => ($this->theme->connection_type == GitConnectionEnum::HTTPS()->value) ? $this->theme->meta['git_password']:null,
                "connection_type" => $this->theme->connection_type,
                "directory" => $this->theme->directory
            ]);

            RunCloneJob::dispatch($cloneData);

        } catch(\Exception $e){
            // 
        }

    }
}
