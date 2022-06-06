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

class RetryingDeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public \Codewithdiki\FilamentThemeManager\Models\ThemeDeploymentLog $log
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
                'connection_type' => $this->log->theme->connection_type,
                'provider' => $this->log->theme->git_provider,
                'git_username' => $this->log->theme->git_username,
                'git_password' => $this->log->theme->meta['git_password'] ?? null
            ]);
    
            $repository = (new \Codewithdiki\FilamentThemeManager\Actions\NavigateGitUrlAction)->run($urlDTO);
    
            if(empty($repository)){
                throw new \Exception("Repository URL invalid!");
            }
    
            $repository = "{$repository}{$this->log->theme->git_username}/{$this->log->theme->git_repository}.git";
    
            

            $logData = new \Codewithdiki\FilamentThemeManager\DTO\DeploymentData([
                'theme_id' => $this->log->theme->id,
                'name' => "Retry : Deploy {$this->log->theme->name}",
                'repository' => $repository,
                'branch' => $this->log->theme->git_branch,
                'git_username' => $this->log->theme->git_username,
                'connection_type' => $this->log->theme->connection_type,
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
                "branch" => $this->log->theme->git_branch,
                "vendor" => ($this->log->theme->is_child) ? $this->log->theme->parent_theme->vendor : $this->log->theme->vendor,
                "git_username" => $this->log->theme->git_username,
                "git_password" => ($this->log->theme->connection_type == GitConnectionEnum::HTTPS()->value) ? $this->log->theme->meta['git_password']:null,
                "connection_type" => $this->log->theme->connection_type,
                "directory" => $this->log->theme->directory,
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
