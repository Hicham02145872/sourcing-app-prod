<?php

namespace App\Livewire\Admin;

use App\Models\DeliveryProperty;
use App\Models\DeliveryDefect;
use App\Models\DeliveryNotice;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryContentManager extends Component
{
    use WithPagination;

    public string $activeTab = 'properties';

    public ?int $editPropertyId = null;
    public ?int $editDefectId = null;
    public ?int $editNoticeId = null;

    public $property_delivery_type = 'indirect';
    public $property_title = '';
    public $property_description = '';
    public $property_icon = '';
    public $property_sort_order = 0;

    public $defect_delivery_type = 'indirect';
    public $defect_title = '';
    public $defect_description = '';
    public $defect_sort_order = 0;

    public $notice_delivery_type = 'indirect';
    public $notice_body_text = '';

    protected function rules(): array
    {
        return match ($this->activeTab) {
            'properties' => [
                'property_delivery_type' => 'required|in:direct,indirect',
                'property_title' => 'required|string|max:255',
                'property_description' => 'nullable|string',
                'property_icon' => 'nullable|string|max:255',
                'property_sort_order' => 'integer|min:0',
            ],
            'defects' => [
                'defect_delivery_type' => 'required|in:direct,indirect',
                'defect_title' => 'required|string|max:255',
                'defect_description' => 'nullable|string',
                'defect_sort_order' => 'integer|min:0',
            ],
            'notices' => [
                'notice_delivery_type' => 'required|in:direct,indirect',
                'notice_body_text' => 'required|string',
            ],
            default => [],
        };
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editPropertyId = null;
        $this->editDefectId = null;
        $this->editNoticeId = null;
        $this->property_delivery_type = 'indirect';
        $this->property_title = '';
        $this->property_description = '';
        $this->property_icon = '';
        $this->property_sort_order = 0;
        $this->defect_delivery_type = 'indirect';
        $this->defect_title = '';
        $this->defect_description = '';
        $this->defect_sort_order = 0;
        $this->notice_delivery_type = 'indirect';
        $this->notice_body_text = '';
    }

    public function editProperty(int $id): void
    {
        $property = DeliveryProperty::findOrFail($id);
        $this->editPropertyId = $property->id;
        $this->property_delivery_type = $property->delivery_type;
        $this->property_title = $property->title;
        $this->property_description = $property->description ?? '';
        $this->property_icon = $property->icon ?? '';
        $this->property_sort_order = $property->sort_order;
    }

    public function saveProperty(): void
    {
        $this->validate();

        DeliveryProperty::updateOrCreate(
            ['id' => $this->editPropertyId],
            [
                'delivery_type' => $this->property_delivery_type,
                'title' => $this->property_title,
                'description' => $this->property_description,
                'icon' => $this->property_icon,
                'sort_order' => $this->property_sort_order,
            ]
        );

        $this->dispatch('show-success-toast', message: __('Property saved successfully.'));
        $this->resetForm();
    }

    public function deleteProperty(int $id): void
    {
        DeliveryProperty::findOrFail($id)->delete();
        $this->dispatch('show-success-toast', message: __('Property deleted.'));
    }

    public function toggleProperty(int $id): void
    {
        $property = DeliveryProperty::findOrFail($id);
        $property->update(['is_active' => !$property->is_active]);
    }

    public function editDefect(int $id): void
    {
        $defect = DeliveryDefect::findOrFail($id);
        $this->editDefectId = $defect->id;
        $this->defect_delivery_type = $defect->delivery_type;
        $this->defect_title = $defect->title;
        $this->defect_description = $defect->description ?? '';
        $this->defect_sort_order = $defect->sort_order;
    }

    public function saveDefect(): void
    {
        $this->validate();

        DeliveryDefect::updateOrCreate(
            ['id' => $this->editDefectId],
            [
                'delivery_type' => $this->defect_delivery_type,
                'title' => $this->defect_title,
                'description' => $this->defect_description,
                'sort_order' => $this->defect_sort_order,
            ]
        );

        $this->dispatch('show-success-toast', message: __('Defect saved successfully.'));
        $this->resetForm();
    }

    public function deleteDefect(int $id): void
    {
        DeliveryDefect::findOrFail($id)->delete();
        $this->dispatch('show-success-toast', message: __('Defect deleted.'));
    }

    public function toggleDefect(int $id): void
    {
        $defect = DeliveryDefect::findOrFail($id);
        $defect->update(['is_active' => !$defect->is_active]);
    }

    public function editNotice(int $id): void
    {
        $notice = DeliveryNotice::findOrFail($id);
        $this->editNoticeId = $notice->id;
        $this->notice_delivery_type = $notice->delivery_type;
        $this->notice_body_text = $notice->body_text;
    }

    public function saveNotice(): void
    {
        $this->validate();

        DeliveryNotice::updateOrCreate(
            ['id' => $this->editNoticeId],
            [
                'delivery_type' => $this->notice_delivery_type,
                'body_text' => $this->notice_body_text,
            ]
        );

        $this->dispatch('show-success-toast', message: __('Notice saved successfully.'));
        $this->resetForm();
    }

    public function deleteNotice(int $id): void
    {
        DeliveryNotice::findOrFail($id)->delete();
        $this->dispatch('show-success-toast', message: __('Notice deleted.'));
    }

    public function render()
    {
        return view('livewire.admin.delivery-content-manager', [
            'properties' => DeliveryProperty::orderBy('sort_order')->orderBy('id')->paginate(20),
            'defects' => DeliveryDefect::orderBy('sort_order')->orderBy('id')->paginate(20),
            'notices' => DeliveryNotice::orderBy('id')->paginate(20),
        ]);
    }
}
