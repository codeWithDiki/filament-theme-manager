<x-filament::page>

    <div class="rounded-xl border py-4 px-2 bg-white space-y-6">
        <div class="space-y-3 px-3">
            <div class="py-3 border-b flex justify-between items-center gap-3">
                <div>
                    Process Started
                </div>
                <div>
                    {{ $deployment_log->created_at?->format('d/m/Y H:i:s') }}
                </div>
            </div>
            <div class="py-3 border-b flex justify-between items-center gap-3">
                <div>
                    Process End
                </div>
                <div>
                    {{ $deployment_log->process_end_at?->format('d/m/Y H:i:s') ?? "-" }}
                </div>
            </div>
            <div class="py-3 border-b flex justify-between items-center gap-3">
                <div>
                    Status
                </div>
                <div class="{{ 
                    match($deployment_log->status){
                        'pending' => 'text-warning-500',
                        'failed' => 'text-danger-500',
                        'successed' => 'text-success-500',
                        default => 'text-primary-500'
                    }
                 }} text-white font-bold">
                    {{ ucwords($deployment_log->status) }}
                </div>
            </div>
        </div>
        <div class="space-y-3 px-3">
            <div class="font-bold">
                Output : 
            </div>
            <div class="bg-primary-500/10 px-2 py-4 rounded-lg space-y-2">
                @foreach($deployment_log->meta['output'] as $output)
                    <div class="font-semibold text-primary-500">
                        {{ $output }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
</x-filament::page>
