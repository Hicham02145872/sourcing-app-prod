<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Google Sheets Sync Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="{{ route('admin.google-sheets.settings') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to settings') }}
                </a>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm text-gray-500">{{ __('Total Success') }}</div>
                            <div class="text-2xl font-semibold">{{ $stats['total_success'] }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm text-gray-500">{{ __('Total Errors') }}</div>
                            <div class="text-2xl font-semibold">{{ $stats['total_errors'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mx-auto">
                         <div class="text-sm text-gray-500 mb-1">{{ __('Last success') }}</div>
                         <div class="text-sm font-medium">
                            @if($stats['last_success_at'])
                                {{ \Carbon\Carbon::parse($stats['last_success_at'])->diffForHumans() }}
                                <div class="text-xs text-gray-400 mt-1">{{ __('Order') }} #{{ $stats['last_success_order_id'] }}</div>
                            @else
                                <span class="text-gray-400">{{ __('None') }}</span>
                            @endif
                         </div>
                    </div>
                </div>
            </div>

            <!-- Logs List (Card Based) -->
            <div class="space-y-4">
                @forelse($logs as $log)
                    <div class="bg-white rounded-lg shadow-sm border @if($log->status === 'success') border-green-200 @else border-red-200 @endif">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-1">
                                    <span class="text-2xl">{{ $log->getStatusIcon() }}</span>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-gray-900">{{ $log->getFriendlyAction() }}</span>
                                            @if($log->sourcingOrder)
                                                <a href="{{ route('admin.sourcing-orders.show', $log->sourcingOrder) }}" class="text-sm text-indigo-600 hover:text-indigo-900 hover:underline">
                                                    {{ __('Order') }} #{{ $log->sourcing_order_id }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">{{ __('Order') }} #{{ $log->sourcing_order_id }}</span>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-500" title="{{ $log->getFullTimestamp() }}">
                                            {{ $log->getFormattedTime() }}
                                        </span>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        @if($log->status === 'success')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Success') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ __('Error') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Success Details -->
                                    @if($log->status === 'success')
                                        <div class="text-sm text-gray-600">
                                            @if($log->action === 'update_status' && isset($log->data['new_status']))
                                                <p>{{ __('Status changed to:') }} <span class="font-medium text-gray-900">{{ $log->data['new_status'] }}</span></p>
                                            @else
                                                <p class="text-green-700">✓ {{ __('Synchronization completed successfully') }}</p>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Error Details -->
                                    @if($log->status === 'error')
                                        <div class="space-y-3">
                                            <!-- User-Friendly Error -->
                                            <div class="bg-red-50 border border-red-200 rounded-md p-3">
                                                <p class="text-sm font-medium text-red-800">
                                                    {{ $log->getFriendlyErrorMessage() }}
                                                </p>
                                            </div>

                                            <!-- Suggestion -->
                                            @if($log->getSuggestion())
                                                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                                    <p class="text-sm text-blue-800">
                                                        <span class="font-medium">💡 {{ __('Suggestion:') }}</span>
                                                        {!! $log->getSuggestion() !!}
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Technical Details (Collapsible) -->
                                            <details class="group">
                                                <summary class="cursor-pointer text-sm text-gray-500 hover:text-gray-700 select-none">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        {{ __('Show technical details') }}
                                                    </span>
                                                </summary>
                                                <div class="mt-2 ml-5 p-3 bg-gray-50 rounded text-xs font-mono text-gray-700 overflow-x-auto">
                                                    <div><strong>{{ __('Error Message:') }}</strong> {{ $log->error_message }}</div>
                                                    @if($log->error_code)
                                                        <div class="mt-1"><strong>{{ __('Error Code:') }}</strong> {{ $log->error_code }}</div>
                                                    @endif
                                                    @if($log->data)
                                                        <div class="mt-1"><strong>{{ __('Data:') }}</strong> {{ json_encode($log->data, JSON_PRETTY_PRINT) }}</div>
                                                    @endif
                                                </div>
                                            </details>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No activity recorded yet') }}</h3>
                        <p class="text-gray-500 text-sm">
                            {{ __('Sync logs will appear here once orders start syncing to Google Sheets.') }}
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
