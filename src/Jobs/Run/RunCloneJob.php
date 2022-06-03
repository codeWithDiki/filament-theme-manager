<?php

namespace Codewithdiki\FilamentThemeManager\Jobs\Run;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RunCloneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public \Codewithdiki\FilamentThemeManager\DTO\GitCloneData $gitCloneDTO
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
    
            if(file_exists($theme_directory)){
                if(!is_dir($theme_directory)){
                    throw new \Exception("Directory {$theme_directory} has already registered, but it's not a directory.");
                }
            }

            if(!file_exists($theme_directory)){
                mkdir($theme_directory);
            }



            $vendor_directory = "{$theme_directory}/{$this->gitCloneDTO->vendor}";
            $full_theme_directory = "{$vendor_directory}/{$this->gitCloneDTO->directory}";


            if(file_exists($vendor_directory)){
                if(!is_dir($vendor_directory)){
                    throw new \Exception("Directory {$vendor_directory} has already registered, but it's not a directory.");
                }
            }

            if(!file_exists($vendor_directory)){
                mkdir($vendor_directory);
            }

            if(file_exists($full_theme_directory)){
                throw new \Exception("Directory {$full_theme_directory} has been used by other theme.");
            }


            $cloneProcess = new Process([
                'git',
                'clone',
                '--single-branch',
                '--branch',
                $this->gitCloneDTO->branch,
                $this->gitCloneDTO->repository,
                $this->gitCloneDTO->directory
            ]);

            $cloneProcess->setWorkingDirectory($vendor_directory);

            $cloneProcess->run();

        } catch(\Exception $e){
            \Illuminate\Support\Facades\Log::alert($e->getMessage());
        }
    }
}
