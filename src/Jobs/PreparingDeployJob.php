<?php

namespace Codewithdiki\FilamentThemeManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Codewithdiki\FilamentThemeManager\DTO\GetGitUrlData;
use Codewithdiki\FilamentThemeManager\Enum\GitProviderEnum;
use Codewithdiki\FilamentThemeManager\Jobs\Run\RunCloneJob;
use Codewithdiki\FilamentThemeManager\Jobs\Run\RunDeployJob;
use Codewithdiki\FilamentThemeManager\Enum\GitConnectionEnum;

class PreparingDeployJob implements ShouldQueue
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
            DB::beginTransaction();
            $urlDTO = new GetGitUrlData([
                'connection_type' => $this->theme->connection_type,
                'provider' => $this->theme->git_provider,
                'git_username' => $this->theme->git_username,
                'git_password' => $this->theme->meta['git_password'] ?? null
            ]);
    
            $repository = (new \Codewithdiki\FilamentThemeManager\Actions\NavigateGitUrlAction)->run($urlDTO);
    
            if(empty($repository)){
                throw new \Exception("Repository URL invalid!");
            }
    
            $repository = "{$repository}{$this->theme->git_username}/{$this->theme->git_repository}.git";
    
            

            $logData = new \Codewithdiki\FilamentThemeManager\DTO\DeploymentData([
                'theme_id' => $this->theme->id,
                'name' => "Deploy {$this->theme->name}",
                'repository' => $repository,
                'branch' => $this->theme->git_branch,
                'git_username' => $this->theme->git_username,
                'connection_type' => $this->theme->connection_type,
                'meta' => [
                    "type" => \Codewithdiki\FilamentThemeManager\Enum\DeploymentTypeEnum::DEPLOY()->value
                ]
            ]);

            $log = (new \Codewithdiki\FilamentThemeManager\Actions\CreateDeploymentLogAction)->run($logData);

            if(empty($log)){
                throw new \Exception("Theme deployment log not found.");
            }

            $log = theme_deployment_log_writer($log, [
                'Resources are ready to be processed.',
                'Initializing deploy procedure ... '
            ]);

            $cloneData = new \Codewithdiki\FilamentThemeManager\DTO\GitProcessData([
                "repository" => $repository,
                "branch" => $this->theme->git_branch,
                "vendor" => ($this->theme->is_child) ? $this->theme->parent_theme->vendor : $this->theme->vendor,
                "git_username" => $this->theme->git_username,
                "git_password" => ($this->theme->connection_type == GitConnectionEnum::HTTPS()->value) ? $this->theme->meta['git_password']:null,
                "connection_type" => $this->theme->connection_type,
                "directory" => $this->theme->directory,
                "log" => $log
            ]);

            RunDeployJob::dispatch($cloneData);
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            \Illuminate\Support\Facades\Log::alert($e->getMessage());
        }

    }
}
