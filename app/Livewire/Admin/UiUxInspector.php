<?php

namespace App\Livewire\Admin;

use App\Services\UiUxInspectionService;
use App\Services\UiUxScreenshotService;
use Livewire\Component;

class UiUxInspector extends Component
{
    public string $url = '';

    public string $viewport = 'desktop';

    public string $analysisLanguage = 'fr';

    public bool $running = false;

    public ?string $error = null;

    public ?array $capture = null;

    public ?array $result = null;

    public ?string $screenshotUrl = null;

    protected function rules(): array
    {
        return [
            'url' => ['required', 'url', 'max:2048'],
            'viewport' => ['required', 'in:desktop,tablet,mobile'],
            'analysisLanguage' => ['required', 'in:fr,en,ar'],
        ];
    }

    public function mount(): void
    {
        $this->url = url('/');
    }

    public function inspect(): void
    {
        $this->reset('error', 'capture', 'result', 'screenshotUrl');
        $this->validate();

        if (function_exists('set_time_limit')) {
            set_time_limit(180);
        }

        $this->running = true;

        try {
            $capture = app(UiUxScreenshotService::class)->capture($this->url, $this->viewport);

            $screenshotPath = $capture['run_dir'].DIRECTORY_SEPARATOR.$capture['screenshot'];

            $result = app(UiUxInspectionService::class)->analyze($screenshotPath, [
                'url' => $this->url,
                'viewport' => $this->viewport,
                'language' => $this->analysisLanguage,
                'capture' => $capture,
            ]);

            $this->capture = $capture;
            $this->result = $result;
            $this->screenshotUrl = route('admin.dev.uiux-screenshot', [
                'run' => $capture['run_id'],
                'file' => $capture['screenshot'],
            ]);
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->running = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.ui-ux-inspector')->layout('layouts.dev');
    }
}
