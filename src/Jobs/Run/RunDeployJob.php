<?php

namespace Codewithdiki\FilamentThemeManager\Jobs\Run;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Codewithdiki\FilamentThemeManager\Enum\DeploymentStatusEnum;

class RunDeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public \Codewithdiki\FilamentThemeManager\DTO\GitProcessData $gitCloneDTO
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
            $theme_directory = theme_directory();
            
            $this->gitCloneDTO->log->status = DeploymentStatusEnum::PROCESSING()->value;

            $this->gitCloneDTO->log->save();

            $vendor_directory = "{$theme_directory}/{$this->gitCloneDTO->vendor}";
            $full_theme_directory = "{$vendor_directory}/{$this->gitCloneDTO->directory}";

            if(!file_exists($full_theme_directory)){
                throw new \Exception("Directory {$full_theme_directory} not found.");
            }


            $stash_process = new Process(['git', 'stash']);
            $stash_process->setWorkingDirectory($full_theme_directory);
            $stash_process->run();
            $stash_output = [];

            foreach($stash_process as $type => $data){
                $stash_output[] = $data;
            }

            theme_deployment_log_writer($this->gitCloneDTO->log, $stash_output);


            $pullProcess = new Process([
                'git',
                'pull',
                'origin',
                $this->gitCloneDTO->branch,
            ]);
            $pullProcess->setWorkingDirectory($full_theme_directory);
            $pullProcess->run();
            $pullOutput = [];

            foreach ($pullProcess as $type => $data) {
                $pullOutput[] = $data; 
            }
            
            theme_deployment_log_writer($this->gitCloneDTO->log, $pullOutput);

            if($pullProcess->isSuccessful()){
                $getCurrentCommit = new Process([
                    'git',
                    'rev-parse',
                    'HEAD'
                ]);
                $getCurrentCommit->setWorkingDirectory($full_theme_directory);
                $getCurrentCommit->run();

                $currentCommit = null;

                foreach ($getCurrentCommit as $type => $data) {
                    if(empty($currentCommit)){
                        $currentCommit = $data;
                    }
                }

                $this->gitCloneDTO->log->commit = $currentCommit;
                $this->gitCloneDTO->log->status = DeploymentStatusEnum::SUCCESSED()->value;
                $this->gitCloneDTO->log->process_end_at = now();

                $this->gitCloneDTO->log->save();

                theme_deployment_log_writer($this->gitCloneDTO->log, [
                    "Deploy from repository {$this->gitCloneDTO->repository} successed!"
                ]);

                return;
            }

            $this->gitCloneDTO->log->status = DeploymentStatusEnum::FAILED()->value;
            $this->gitCloneDTO->log->process_end_at = now();

            $this->gitCloneDTO->log->save();

            theme_deployment_log_writer($this->gitCloneDTO->log, [
                "Deploy from repository {$this->gitCloneDTO->repository} failed!",
                'Unexpected Error.'
            ]);

        } catch(\Exception $e){
            \Illuminate\Support\Facades\Log::alert($e->getMessage());
            theme_deployment_log_writer($this->gitCloneDTO->log, [
                $e->getMessage()
            ]);
            $this->gitCloneDTO->log->status = DeploymentStatusEnum::FAILED()->value;
            $this->gitCloneDTO->log->process_end_at = now();

            $this->gitCloneDTO->log->save();
        }
    }
}
