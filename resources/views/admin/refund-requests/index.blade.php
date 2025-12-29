<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 15v-1a4 4 0 0 0-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Refund Management') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Refund Requests') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-slate-600 transition-colors" title="{{ __('Refresh') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <div class="h-6 w-px bg-slate-200"></div>
                        <span class="text-xs text-slate-500">{{ __(':count records', ['count' => $requests->count()]) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Section 1: KPIs (Summary Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Pending -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-amber-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Pending Review') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($stats['pending']) }}</h3>
                        </div>
                        <div class="p-1.5 bg-amber-50 rounded text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-amber-600 font-medium">{{ __('Action required') }}</div>
                </div>

                <!-- Processed -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-emerald-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Requests Approved') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($stats['approved']) }}</h3>
                        </div>
                        <div class="p-1.5 bg-emerald-50 rounded text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-emerald-600 font-medium">{{ __('Processed successfully') }}</div>
                </div>

                <!-- Revenue Impact -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Refunded') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($stats['total_refunded'], 2) }} <span class="text-sm font-medium text-slate-400">USD</span></h3>
                        </div>
                        <div class="p-1.5 bg-orange-50 rounded text-orange-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Cumulative financial impact') }}</div>
                </div>
            </div>

            <!-- Section 2: Filters -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
                <form action="{{ route('admin.refund-requests.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Status') }}</label>
                        <select name="status" class="w-full text-xs border-slate-200 rounded-md focus:ring-slate-900 focus:border-slate-900">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Administrator') }}</label>
                        <select name="admin_id" class="w-full text-xs border-slate-200 rounded-md focus:ring-slate-900 focus:border-slate-900">
                            <option value="">{{ __('All Admins') }}</option>
                            @foreach($admins ?? [] as $admin)
                                <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Min Amount') }}</label>
                        <input type="number" name="min_amount" value="{{ request('min_amount') }}" step="0.01" placeholder="0.00" class="w-full text-xs border-slate-200 rounded-md focus:ring-slate-900 focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Date') }}</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="w-full text-xs border-slate-200 rounded-md focus:ring-slate-900 focus:border-slate-900">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-slate-900 text-white text-xs font-bold py-2 rounded-md hover:bg-slate-800 transition-colors">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.refund-requests.index') }}" class="px-3 bg-slate-100 text-slate-600 text-xs font-bold py-2 rounded-md hover:bg-slate-200 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Section 3: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Request List') }}</h3>
                </div>

                @if ($requests->isEmpty())
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3 border border-slate-100">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-900">{{ __('No refund requests found') }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ __('All caught up!') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Order/Client') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Reason') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    <th scope="col" class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Assignee') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach ($requests as $request)
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-9 w-9">
                                                    <div class="h-9 w-9 rounded bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm border border-slate-200">
                                                        {{ substr($request->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-slate-900">#{{ $request->sourcingOrder->display_id }}</div>
                                                    <div class="text-[11px] text-slate-500 font-medium">{{ $request->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-slate-700">{{ __($request->reason_category) }}</div>
                                            <div class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $request->reason_description }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-sm font-bold text-slate-900">{{ number_format($request->amount_requested, 2) }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ __($request->type) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'under_review' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'processed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                                ];
                                                $currentClass = $statusClasses[$request->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight border {{ $currentClass }}">
                                                {{ __(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($request->assignedAdmin)
                                                <div class="flex items-center">
                                                    <div class="w-5 h-5 rounded-full bg-slate-900 flex items-center justify-center text-[10px] text-white font-bold mr-2">
                                                        {{ substr($request->assignedAdmin->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-xs text-slate-600 font-medium">{{ $request->assignedAdmin->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-300 uppercase italic">{{ __('Unassigned') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.refund-requests.show', $request) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-md transition-colors shadow-sm">
                                                <span>{{ __('Manage') }}</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($requests->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $requests->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
