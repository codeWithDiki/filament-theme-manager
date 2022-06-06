<form wire:submit.prevent="submit" class="space-y-3">
    {{ $this->form }}
    <div class="flex justify-start items-center">
        <button
        wire:loading.attr.delay="disabled"
        wire:loading.class.delay="opacity-70 cursor-wait"
        class="inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button h-9 px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action">
            Save
        </button>
    </div>
</form>