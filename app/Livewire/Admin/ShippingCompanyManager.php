<?php

namespace App\Livewire\Admin;

use App\Models\ShippingCompany;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingCompanyManager extends Component
{
    use WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public ?string $google_sheet_id = null;

    public ?string $sheet_name = 'sourcing';

    public bool $is_active = true;

    public string $activeIntegration = 'lark';

    public ?string $lark_app_id = 'cli_a9d1affbbe38de1a';

    public ?string $lark_app_secret = '6eUjWpgdf1Pdxkz8y0xyFgTHIqT4xAwh';

    public ?string $lark_base_token = null;

    public ?string $lark_table_id = null;

    public ?string $tracking_provider = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'google_sheet_id' => 'nullable|string|max:255',
            'sheet_name' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'lark_app_id' => 'nullable|string|max:255',
            'lark_app_secret' => 'nullable|string|max:255',
            'lark_base_token' => 'nullable|string|max:255',
            'lark_table_id' => 'nullable|string|max:255',
            'tracking_provider' => 'nullable|string|in:itdida,faster,choicexp',
        ];
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'google_sheet_id', 'sheet_name', 'is_active', 'lark_base_token', 'lark_table_id', 'tracking_provider']);
        $this->lark_app_id = 'cli_a9d1affbbe38de1a';
        $this->lark_app_secret = '6eUjWpgdf1Pdxkz8y0xyFgTHIqT4xAwh';
        $this->is_active = true;
        $this->sheet_name = 'sourcing';
        $this->activeIntegration = 'lark';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $company = ShippingCompany::findOrFail($id);
        $this->editingId = $company->id;
        $this->name = $company->name;
        $this->google_sheet_id = $company->google_sheet_id;
        $this->sheet_name = $company->sheet_name;
        $this->is_active = $company->is_active;
        $this->lark_app_id = $company->lark_app_id;
        $this->lark_app_secret = $company->lark_app_secret;
        $this->lark_base_token = $company->lark_base_token;
        $this->lark_table_id = $company->lark_table_id;
        $this->tracking_provider = $company->tracking_provider;

        // Default to Google if it has config, otherwise Lark
        $this->activeIntegration = ($company->google_sheet_id) ? 'google' : 'lark';

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'google_sheet_id' => $this->google_sheet_id,
            'sheet_name' => $this->sheet_name,
            'is_active' => $this->is_active,
            'lark_app_id' => $this->lark_app_id,
            'lark_app_secret' => $this->lark_app_secret,
            'lark_base_token' => $this->lark_base_token,
            'lark_table_id' => $this->lark_table_id,
            'tracking_provider' => $this->tracking_provider,
        ];

        if ($this->editingId) {
            ShippingCompany::findOrFail($this->editingId)->update($data);
            $this->dispatch('show-success-toast', message: __('Shipping company updated successfully.'));
        } else {
            ShippingCompany::create($data);
            $this->dispatch('show-success-toast', message: __('Shipping company created successfully.'));
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'google_sheet_id', 'sheet_name', 'is_active', 'lark_app_id', 'lark_app_secret', 'lark_base_token', 'lark_table_id', 'tracking_provider']);
    }

    public function delete(int $id): void
    {
        ShippingCompany::findOrFail($id)->delete();
        $this->dispatch('show-success-toast', message: __('Shipping company deleted successfully.'));
    }

    public function toggleActive(int $id): void
    {
        $company = ShippingCompany::findOrFail($id);
        $company->update(['is_active' => ! $company->is_active]);
        $this->dispatch('show-success-toast', message: __('Status updated.'));
    }

    public function installHeaders(int $id): void
    {
        try {
            $company = ShippingCompany::findOrFail($id);
            $factory = new \App\Services\SheetIntegrationFactory;
            $service = $factory->getService($company);

            if (! $service) {
                $this->dispatch('show-error-toast', message: __('No integration configured for this company.'));

                return;
            }

            $result = $service->ensureHeaders($company);

            if ($result['success']) {
                $this->dispatch('show-success-toast', message: $result['message']);
            } else {
                $this->dispatch('show-error-toast', message: $result['message']);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: __('Error: ').$e->getMessage());
        }
    }

    public function testConnection(int $id): void
    {
        try {
            $company = ShippingCompany::findOrFail($id);
            $factory = new \App\Services\SheetIntegrationFactory;
            $service = $factory->getService($company);

            if (! $service) {
                $this->dispatch('show-error-toast', message: __('No integration configured for this company.'));

                return;
            }

            $config = [];
            if ($company->google_sheet_id) {
                // Google service uses staticTestConnection internally or resolves via auth
                $config = ['google_sheet_id' => $company->google_sheet_id];
            } else {
                $config = [
                    'lark_app_id' => $company->lark_app_id,
                    'lark_app_secret' => $company->lark_app_secret,
                    'lark_base_token' => $company->lark_base_token,
                ];
            }

            $result = $service->testConnection($config);

            if ($result['success']) {
                $this->dispatch('show-success-toast', message: $result['message']);
            } else {
                $this->dispatch('show-error-toast', message: $result['message']);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: __('Error: ').$e->getMessage());
        }
    }

    public function testLarkConnection(): void
    {
        $this->validate([
            'lark_app_id' => 'required|string',
            'lark_app_secret' => 'required|string',
            'lark_base_token' => 'required|string',
        ]);

        try {
            $service = new \App\Services\LarkSheetService;
            $result = $service->testConnection([
                'lark_app_id' => $this->lark_app_id,
                'lark_app_secret' => $this->lark_app_secret,
                'lark_base_token' => $this->lark_base_token,
            ]);

            if ($result['success']) {
                $this->dispatch('show-success-toast', message: $result['message']);
            } else {
                $this->dispatch('show-error-toast', message: $result['message']);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: __('Error: ').$e->getMessage());
        }
    }

    public function installLarkHeaders(int $id): void
    {
        // Consolidate with installHeaders
        $this->installHeaders($id);
    }

    public function getIntegrationType(ShippingCompany $company): string
    {
        if ($company->google_sheet_id) {
            return 'google';
        }

        return 'lark';
    }

    public function render()
    {
        return view('livewire.admin.shipping-company-manager', [
            'companies' => ShippingCompany::orderBy('name')->paginate(10),
        ]);
    }
}
