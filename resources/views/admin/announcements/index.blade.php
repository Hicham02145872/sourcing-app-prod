<x-app-layout>
    <!-- Main Container -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">

        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Megaphone Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 11-5.8-1.6"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Announcements') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Client banner') }}</span>
                            </nav>
                        </div>
                    </div>

                    <!-- Top Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end mr-2">
                            <span class="text-xs font-bold text-slate-700">{{ now()->format('l, d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ __('Casablanca (GMT+1)') }}</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                        <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-orange-600 transition-colors" title="{{ __('Refresh') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            @if (session('success'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Intro -->
            <div class="rounded-lg border border-orange-200 bg-orange-50/60 px-4 py-3 text-sm text-slate-700">
                {{ __('This message is displayed as a banner to every logged-in client during the selected period. It disappears automatically once the end date has passed.') }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Create / Edit Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/60">
                            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4m4-4l-4 4 4 4"/></svg>
                                <span id="form-title">{{ $editing ? __('Edit announcement') : __('New announcement') }}</span>
                            </h2>
                        </div>

                        <form method="POST"
                              action="{{ $editing ? route('admin.announcements.update', $editing) : route('admin.announcements.store') }}"
                              class="p-5 space-y-4">
                            @csrf
                            @if ($editing)
                                @method('PUT')
                            @endif

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    {{ __('Message') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea id="message" name="message" rows="4" required
                                          placeholder="{{ __('Ex: Today we are closed — back tomorrow at 9 AM.') }}"
                                          class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white resize-y">{{ old('message', $editing?->message) }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                        {{ __('Start date') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="start_date" name="start_date" required
                                           value="{{ old('start_date', $editing?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                    @error('start_date')
                                        <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                        {{ __('End date') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="end_date" name="end_date" required
                                           value="{{ old('end_date', $editing?->end_date?->format('Y-m-d') ?? now()->addDay()->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                    @error('end_date')
                                        <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Active -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                       @checked(old('is_active', $editing ? $editing->is_active : true))
                                       class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-orange-500">
                                <span class="text-sm font-medium text-slate-700">{{ __('Active') }}</span>
                                <span class="text-[11px] text-slate-400">({{ __('uncheck to hide the banner without deleting it') }})</span>
                            </label>

                            <!-- Actions -->
                            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $editing ? __('Update') : __('Create') }}
                                </button>
                                @if ($editing)
                                    <a href="{{ route('admin.announcements.index') }}"
                                       class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-lg transition-colors">
                                        {{ __('Cancel') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Announcements List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/60 flex justify-between items-center">
                            <h2 class="text-sm font-bold text-slate-900">{{ __('All announcements') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">{{ $announcements->count() }}</span>
                        </div>

                        @if ($announcements->isEmpty())
                            <div class="p-10 text-center">
                                <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 11l18-5v12L3 14v-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.6 16.8a3 3 0 11-5.8-1.6"/></svg>
                                <p class="mt-3 text-sm font-semibold text-slate-500">{{ __('No announcements yet.') }}</p>
                                <p class="text-xs text-slate-400">{{ __('Create one to show a banner to all clients.') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Message') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Period') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Status') }}</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-600">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($announcements as $announcement)
                                            @php $status = $announcement->status(); @endphp
                                            <tr class="hover:bg-slate-50/80 transition-colors">
                                                <td class="px-4 py-3 max-w-md">
                                                    <p class="text-sm text-slate-800 font-medium line-clamp-2">{{ $announcement->message }}</p>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-600 font-mono">
                                                    {{ $announcement->start_date->format('d/m/Y') }}
                                                    <span class="text-slate-400 mx-1">→</span>
                                                    {{ $announcement->end_date->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if ($status === 'active')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ __('Visible on banner') }}
                                                        </span>
                                                    @elseif ($status === 'upcoming')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">{{ __('Upcoming') }}</span>
                                                    @elseif ($status === 'expired')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">{{ __('Expired') }}</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                                    <div class="inline-flex items-center gap-2">
                                                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[11px] font-bold rounded-md transition-colors">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                                                              onsubmit="return confirm('{{ __('Delete this announcement?') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold rounded-md transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
