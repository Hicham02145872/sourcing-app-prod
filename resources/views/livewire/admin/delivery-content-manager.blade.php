<div>
    <div class="mb-6 flex items-center gap-2 border-b border-slate-200">
        <button type="button" wire:click="setTab('properties')" class="px-4 py-3 text-xs font-bold uppercase tracking-widest transition-colors {{ $activeTab === 'properties' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-slate-500 hover:text-slate-700' }}">
            {{ __('Delivery Properties') }}
        </button>
        <button type="button" wire:click="setTab('defects')" class="px-4 py-3 text-xs font-bold uppercase tracking-widest transition-colors {{ $activeTab === 'defects' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-slate-500 hover:text-slate-700' }}">
            {{ __('Logistics Defects') }}
        </button>
        <button type="button" wire:click="setTab('notices')" class="px-4 py-3 text-xs font-bold uppercase tracking-widest transition-colors {{ $activeTab === 'notices' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-slate-500 hover:text-slate-700' }}">
            {{ __('Discharge Notices') }}
        </button>
    </div>

    {{-- Properties Tab --}}
    @if($activeTab === 'properties')
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900">{{ $editPropertyId ? __('Edit Property') : __('Add New Property') }}</h3>
            </div>
            <div class="p-6">
                <form wire:submit="saveProperty" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Delivery Type') }}</label>
                        <select wire:model="property_delivery_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                            <option value="direct">{{ __('Direct') }}</option>
                            <option value="indirect">{{ __('Indirect') }} ({{ __('Dubai') }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Title') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="property_title" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                        @error('property_title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="property_description" rows="2" class="w-full px-3 py-2 text-sm border border-slate-300 rounded"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Icon') }}</label>
                        <input type="text" wire:model="property_icon" class="w-full px-3 py-2 text-sm border border-slate-300 rounded" placeholder="emoji or icon class">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Sort Order') }}</label>
                        <input type="number" wire:model="property_sort_order" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                    </div>
                    <div class="md:col-span-2 flex items-center gap-3">
                        <button type="submit" class="px-6 py-2 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-800 transition-colors">
                            {{ $editPropertyId ? __('Update') : __('Save') }}
                        </button>
                        @if($editPropertyId)
                            <button type="button" wire:click="resetForm" class="px-4 py-2 border border-slate-300 text-slate-600 text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-50 transition-colors">
                                {{ __('Cancel') }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Title') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">{{ __('Active') }}</th>
                            <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($properties as $property)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ $property->title }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600 capitalize">{{ $property->delivery_type }}</td>
                                <td class="px-6 py-3 text-center">
                                    <button type="button" wire:click="toggleProperty({{ $property->id }})" class="inline-flex items-center gap-1 text-xs font-bold {{ $property->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $property->is_active ? __('Yes') : __('No') }}
                                    </button>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" wire:click="editProperty({{ $property->id }})" class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                            {{ __('Edit') }}
                                        </button>
                                        <button type="button" wire:click="deleteProperty({{ $property->id }})" wire:confirm="{{ __('Are you sure?') }}" class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-red-600 hover:bg-red-50 rounded transition-colors">
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">{{ __('No properties defined.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($properties->hasPages())
                <div class="px-6 py-3 border-t border-slate-100">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Defects Tab --}}
    @if($activeTab === 'defects')
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900">{{ $editDefectId ? __('Edit Defect') : __('Add New Defect') }}</h3>
            </div>
            <div class="p-6">
                <form wire:submit="saveDefect" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Delivery Type') }}</label>
                        <select wire:model="defect_delivery_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                            <option value="direct">{{ __('Direct') }}</option>
                            <option value="indirect">{{ __('Indirect') }} ({{ __('Dubai') }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Title') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="defect_title" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                        @error('defect_title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="defect_description" rows="2" class="w-full px-3 py-2 text-sm border border-slate-300 rounded"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Sort Order') }}</label>
                        <input type="number" wire:model="defect_sort_order" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                    </div>
                    <div class="md:col-span-2 flex items-center gap-3">
                        <button type="submit" class="px-6 py-2 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-800 transition-colors">
                            {{ $editDefectId ? __('Update') : __('Save') }}
                        </button>
                        @if($editDefectId)
                            <button type="button" wire:click="resetForm" class="px-4 py-2 border border-slate-300 text-slate-600 text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-50 transition-colors">
                                {{ __('Cancel') }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Title') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">{{ __('Active') }}</th>
                            <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($defects as $defect)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ $defect->title }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600 capitalize">{{ $defect->delivery_type }}</td>
                                <td class="px-6 py-3 text-center">
                                    <button type="button" wire:click="toggleDefect({{ $defect->id }})" class="inline-flex items-center gap-1 text-xs font-bold {{ $defect->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $defect->is_active ? __('Yes') : __('No') }}
                                    </button>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" wire:click="editDefect({{ $defect->id }})" class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                            {{ __('Edit') }}
                                        </button>
                                        <button type="button" wire:click="deleteDefect({{ $defect->id }})" wire:confirm="{{ __('Are you sure?') }}" class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-red-600 hover:bg-red-50 rounded transition-colors">
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">{{ __('No defects defined.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($defects->hasPages())
                <div class="px-6 py-3 border-t border-slate-100">
                    {{ $defects->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Notices Tab --}}
    @if($activeTab === 'notices')
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900">{{ $editNoticeId ? __('Edit Notice') : __('Add New Notice') }}</h3>
            </div>
            <div class="p-6">
                <form wire:submit="saveNotice" class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Delivery Type') }}</label>
                        <select wire:model="notice_delivery_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded">
                            <option value="direct">{{ __('Direct') }}</option>
                            <option value="indirect">{{ __('Indirect') }} ({{ __('Dubai') }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Notice Text') }} <span class="text-red-500">*</span></label>
                        <textarea wire:model="notice_body_text" rows="4" class="w-full px-3 py-2 text-sm border border-slate-300 rounded"></textarea>
                        @error('notice_body_text') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-6 py-2 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-800 transition-colors">
                            {{ $editNoticeId ? __('Update') : __('Save') }}
                        </button>
                        @if($editNoticeId)
                            <button type="button" wire:click="resetForm" class="px-4 py-2 border border-slate-300 text-slate-600 text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-50 transition-colors">
                                {{ __('Cancel') }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Notice Text') }}</th>
                            <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">{{ __('Active') }}</th>
                            <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($notices as $notice)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-3 text-sm text-slate-600 capitalize">{{ $notice->delivery_type }}</td>
                                <td class="px-6 py-3 text-sm text-slate-800 max-w-md truncate">{{ $notice->body_text }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold {{ $notice->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $notice->is_active ? __('Yes') : __('No') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" wire:click="editNotice({{ $notice->id }})" class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                            {{ __('Edit') }}
                                        </button>
                                        <button type="button" wire:click="deleteNotice({{ $notice->id }})" wire:confirm="{{ __('Are you sure?') }}" class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-red-600 hover:bg-red-50 rounded transition-colors">
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">{{ __('No notices defined.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notices->hasPages())
                <div class="px-6 py-3 border-t border-slate-100">
                    {{ $notices->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
