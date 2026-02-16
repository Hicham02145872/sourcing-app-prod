<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-slate-100 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900">{{ __('Tracking Search Logs') }}</h1>
                            <nav class="flex text-xs text-slate-500">
                                <span>{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Tracking Logs') }}</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Historical Searches') }}</h3>
                </div>

                @if ($logs->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-slate-500">{{ __('No tracking logs found.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('User') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('Number') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('Provider') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('Location') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">{{ __('Payload') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->user)
                                                <div class="text-sm font-medium text-slate-900">{{ $log->user->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $log->ip_address }}</div>
                                            @else
                                                <div class="text-sm text-slate-400 italic">{{ __('Guest') }}</div>
                                                <div class="text-xs text-slate-500">{{ $log->ip_address }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-mono text-sm font-bold text-orange-600">{{ $log->tracking_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs font-medium">{{ $log->provider }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                            {{ $log->status }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $log->location ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                            {{ $log->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button @click="$dispatch('open-modal', 'payload-{{ $log->id }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                            </button>

                                            <!-- Payload Modal -->
                                            <x-modal name="payload-{{ $log->id }}" :show="false" focusable>
                                                <div class="p-6">
                                                    <h2 class="text-lg font-medium text-slate-900 mb-4">
                                                        {{ __('Tracking Data Snapshot') }} - {{ $log->tracking_number }}
                                                    </h2>
                                                    <pre class="bg-slate-900 text-slate-100 p-4 rounded text-xs overflow-auto max-h-[60vh]"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                    <div class="mt-6 flex justify-end">
                                                        <x-secondary-button x-on:click="$dispatch('close')">
                                                            {{ __('Close') }}
                                                        </x-secondary-button>
                                                    </div>
                                                </div>
                                            </x-modal>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-3 border-t border-slate-200">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
