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

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'google_sheet_id' => 'nullable|string|max:255',
            'sheet_name' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'google_sheet_id', 'sheet_name', 'is_active']);
        $this->is_active = true;
        $this->sheet_name = 'sourcing';
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
        ];

        if ($this->editingId) {
            ShippingCompany::findOrFail($this->editingId)->update($data);
            $this->dispatch('show-success-toast', message: __('Shipping company updated successfully.'));
        } else {
            ShippingCompany::create($data);
            $this->dispatch('show-success-toast', message: __('Shipping company created successfully.'));
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'google_sheet_id', 'sheet_name', 'is_active']);
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
            if (!$company->google_sheet_id) {
                $this->dispatch('show-error-toast', message: __('Please configure Google Sheet ID first.'));
                return;
            }

            $service = new \App\Services\ShippingCompanySheetService($company);
            $result = $service->ensureHeaders();

            if ($result['success']) {
                $this->dispatch('show-success-toast', message: $result['message']);
            } else {
                $this->dispatch('show-error-toast', message: $result['message']);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: __('Error: ') . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.shipping-company-manager', [
            'companies' => ShippingCompany::orderBy('name')->paginate(10),
        ]);
    }
}
