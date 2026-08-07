<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Icone Branding -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Sourcing Requests') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition-colors">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Request List') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-200 text-slate-500 text-xs font-semibold rounded transition-colors shadow-sm cursor-not-allowed group relative">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('New Request') }}
                            <span class="absolute -top-8 right-0 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                {{ __('Coming Soon') }}
                            </span>
                        </div>
                        <button type="button" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ __('Export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            
            <!-- Section 1: KPIs (Summary Cards) - Enterprise Style -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Requests') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingRequests->total() }}</h3>
                        </div>
                        <div class="p-1.5 bg-slate-100 rounded text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Across all categories') }}</div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Pending') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingRequests->where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-blue-50 rounded text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-blue-600 font-medium">{{ __('Requires action') }}</div>
                </div>

                <!-- Processing -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('In Progress') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingRequests->where('status', 'active')->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-orange-50 rounded text-orange-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Active processing') }}</div>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-emerald-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Completed') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingRequests->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-emerald-50 rounded text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Total success') }}</div>
                </div>
            </div>

            <!-- Section 2: Filters Bar (Sticky) -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 sticky top-20 z-10">
                <form action="{{ route('admin.sourcing-requests.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        
                        <div class="flex-1 w-full md:w-auto flex flex-col md:flex-row gap-3">
                            <!-- Search -->
                            <div class="relative w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search (product, client)...') }}"
                                    class="w-full pl-9 pr-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors placeholder:text-slate-400">
                            </div>

                            <!-- Admin Filter (Super Admin only) -->
                            @if(auth()->user()->isSuperAdmin())
                            <div class="w-full md:w-48">
                                <select name="admin_id" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
                                    <option value="all">{{ __('All Admins') }}</option>
                                    <option value="unassigned" {{ request('admin_id') == 'unassigned' ? 'selected' : '' }}>{{ __('Unassigned') }}</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                             <button type="submit" class="w-full md:w-auto px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm">
                                {{ __('Filter') }}
                            </button>
                        </div>

                        <!-- Reset -->
                        <div>
                             <a href="{{ route('admin.sourcing-requests.index') }}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-orange-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Section 2b: Status Toggle Bar -->
            @php
                $statusStyles = [
                    'pending'     => ['active' => 'bg-amber-500 text-white border-amber-500 shadow-sm', 'inactive' => 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50'],
                    'in_review'   => ['active' => 'bg-blue-500 text-white border-blue-500 shadow-sm', 'inactive' => 'bg-white text-blue-700 border-blue-200 hover:bg-blue-50'],
                    'quoted'      => ['active' => 'bg-purple-500 text-white border-purple-500 shadow-sm', 'inactive' => 'bg-white text-purple-700 border-purple-200 hover:bg-purple-50'],
                    'negotiating' => ['active' => 'bg-sky-500 text-white border-sky-500 shadow-sm', 'inactive' => 'bg-white text-sky-700 border-sky-200 hover:bg-sky-50'],
                    'accepted'    => ['active' => 'bg-indigo-500 text-white border-indigo-500 shadow-sm', 'inactive' => 'bg-white text-indigo-700 border-indigo-200 hover:bg-indigo-50'],
                    'completed'   => ['active' => 'bg-emerald-500 text-white border-emerald-500 shadow-sm', 'inactive' => 'bg-white text-emerald-700 border-emerald-200 hover:bg-emerald-50'],
                    'rejected'    => ['active' => 'bg-red-500 text-white border-red-500 shadow-sm', 'inactive' => 'bg-white text-red-700 border-red-200 hover:bg-red-50'],
                    'cancelled'   => ['active' => 'bg-slate-500 text-white border-slate-500 shadow-sm', 'inactive' => 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'],
                ];
                $statusLabels = [
                    'pending' => 'Pending',
                    'in_review' => 'In Review',
                    'quoted' => 'Quoted',
                    'negotiating' => 'Negotiating',
                    'accepted' => 'Accepted',
                    'completed' => 'Completed',
                    'rejected' => 'Rejected',
                    'cancelled' => 'Cancelled',
                ];
            @endphp
            <div class="flex flex-wrap gap-2">
                @foreach (\App\Models\SourcingRequest::STATUSES as $status)
                    @php
                        $count = $statusCounts[$status] ?? 0;
                        $isActive = $activeStatus === $status;
                        if ($count === 0 && !$isActive) continue;
                        $style = $isActive ? $statusStyles[$status]['active'] : $statusStyles[$status]['inactive'];
                        $params = array_merge(request()->except('status', 'page'), ['status' => $status]);
                        $url = $isActive ? route('admin.sourcing-requests.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) : route('admin.sourcing-requests.index', $params);
                    @endphp
                    <a href="{{ $url }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full border transition-all {{ $style }}">
                        {{ $statusLabels[$status] }}
                        <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold rounded-full {{ $isActive ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-600' }}">
                            {{ $count }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Section 3: Data Table / Cards -->
            <div x-data="{
                    view: (() => { try { return localStorage.getItem('sourcingRequestsView') || 'list'; } catch (e) { return 'list'; } })(),
                    init() { this.$watch('view', (v) => { try { localStorage.setItem('sourcingRequestsView', v); } catch (e) {} }); }
                }" class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Recent Requests') }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="text-xs text-slate-500 hidden sm:block">
                            <span class="font-medium text-slate-900">{{ $sourcingRequests->firstItem() ?? 0 }}-{{ $sourcingRequests->lastItem() ?? 0 }}</span> {{ __('of') }} <span class="font-medium text-slate-900">{{ $sourcingRequests->total() }}</span>
                        </div>
                        <!-- View Toggle: List / Cards -->
                        <div class="inline-flex items-center gap-0.5 bg-slate-100 border border-slate-200 rounded-lg p-0.5">
                            <button type="button" @click="view = 'list'" title="{{ __('List view') }}"
                                :class="view === 'list' ? 'bg-white text-orange-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 border-transparent'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md border transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <span class="hidden sm:inline">{{ __('List') }}</span>
                            </button>
                            <button type="button" @click="view = 'cards'" title="{{ __('Card view') }}"
                                :class="view === 'cards' ? 'bg-white text-orange-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 border-transparent'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md border transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z"/></svg>
                                <span class="hidden sm:inline">{{ __('Cards') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div x-show="view === 'list'" x-cloak class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-64">{{ __('Product') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Destination') }}</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Assignment') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($sourcingRequests as $request)
                                @php
                                    $statusConfig = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'in_review' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'quoted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'negotiating' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'accepted' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                    $statusClass = $statusConfig[$request->status] ?? $statusConfig['pending'];
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <!-- Product -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 flex-shrink-0 rounded bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                                @if ($request->product_image)
                                                    <img src="{{ media_url($request->product_image) }}" alt="" class="h-full w-full object-cover">
                                                @else
                                                    <span class="text-xs font-bold text-slate-400">{{ substr($request->product_name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-mono font-medium text-orange-600">{{ $request->reference_id }}</div>
                                                <div class="text-sm font-medium text-slate-900 truncate max-w-[180px]" title="{{ $request->product_name }}">{{ $request->product_name }}</div>
                                                <div class="text-xs text-slate-500">{{ $request->category?->name ?? __('Unclassified') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Client -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                {{ substr($request->user->name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm text-slate-700">{{ $request->user->name }}</span>
                                                <span class="text-[10px] text-slate-400">{{ $request->user->email }}</span>
                                                @if($request->user->phone)
                                                    <span class="text-[10px] text-orange-600 font-medium">📞 {{ $request->user->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Destinations -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-1">
                                            @foreach($request->destinations->take(2) as $destination)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $destination->country->code }}
                                                </span>
                                            @endforeach
                                            @if($request->destinations->count() > 2)
                                                <span class="text-[10px] text-slate-400">+{{ $request->destinations->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusClass }} uppercase tracking-wide">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="text-sm text-slate-600">{{ $request->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $request->updated_at->diffForHumans() }}</div>
                                    </td>

                                    <!-- Assignment -->
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500">
                                        @if($request->assigned_to_admin_id)
                                            <div class="flex items-center gap-2 group">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border border-purple-100 bg-purple-50 text-purple-700 text-xs font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                                    {{ $request->assignedAdmin->name }}
                                                </span>
                                                @if(auth()->user()->isSuperAdmin())
                                                    <form action="{{ route('admin.sourcing-requests.unassign', $request) }}" method="POST" class="inline opacity-0 group-hover:opacity-100 transition-opacity">
                                                        @csrf
                                                        <button type="submit" class="text-slate-400 hover:text-red-500" title="{{ __('Unassign') }}">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            @if(auth()->user()->isSuperAdmin())
                                                <form action="{{ route('admin.sourcing-requests.assign', $request) }}" method="POST">
                                                    @csrf
                                                    <select name="admin_id" onchange="this.form.submit()" class="text-xs py-1 pl-2 pr-6 border-slate-200 rounded bg-slate-50 text-slate-500 focus:ring-1 focus:ring-orange-500 focus:border-orange-500 cursor-pointer hover:bg-white hover:border-slate-300 transition-colors">
                                                        <option value="">{{ __('Assign...') }}</option>
                                                        @foreach($admins as $admin)
                                                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400 italic">{{ __('Unassigned') }}</span>
                                            @endif
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-3 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.sourcing-requests.show', [$request, 'page' => request('page')]) }}" 
                                               class="inline-flex items-center justify-center h-8 w-8 rounded-full text-slate-400 hover:text-orange-600 hover:bg-orange-50 transition-all border border-transparent hover:border-orange-200"
                                               title="{{ __('View') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                            <form action="{{ route('admin.sourcing-requests.destroy', $request) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this request?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center h-8 w-8 rounded-full text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-200" title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-12 w-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <h3 class="text-sm font-medium text-slate-900">{{ __('No requests found') }}</h3>
                                            <p class="text-xs text-slate-500 mt-1">{{ __('No requests match your current criteria.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Cards view -->
                <div x-show="view === 'cards'" x-cloak class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-4">
                    @forelse ($sourcingRequests as $request)
                        @php
                            $statusConfig = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'in_review' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'quoted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'negotiating' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'accepted' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                            ];
                            $statusClass = $statusConfig[$request->status] ?? $statusConfig['pending'];
                        @endphp
                        <div class="flex flex-col border border-slate-200 rounded-lg bg-white shadow-sm hover:shadow-md hover:border-orange-200 transition-all overflow-hidden">
                            <!-- Card Header -->
                            <div class="p-4 flex items-start gap-3 border-b border-slate-100">
                                <div class="h-14 w-14 flex-shrink-0 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                    @if ($request->product_image)
                                        <img src="{{ media_url($request->product_image) }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-sm font-bold text-slate-400">{{ substr($request->product_name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-sm font-mono font-medium text-orange-600">{{ $request->reference_id }}</div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusClass }} uppercase tracking-wide shrink-0">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium text-slate-900 truncate mt-1" title="{{ $request->product_name }}">{{ $request->product_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $request->category?->name ?? __('Unclassified') }}</div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="p-4 space-y-3 flex-1">
                                <div class="flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 shrink-0">
                                        {{ substr($request->user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm text-slate-700 truncate">{{ $request->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 truncate">{{ $request->user->email }}</div>
                                        @if($request->user->phone)
                                            <div class="text-[10px] text-orange-600 font-medium">📞 {{ $request->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Destination') }}</span>
                                        <div class="flex items-center gap-1 mt-1">
                                            @foreach($request->destinations->take(2) as $destination)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $destination->country->code }}
                                                </span>
                                            @endforeach
                                            @if($request->destinations->count() > 2)
                                                <span class="text-[10px] text-slate-400">+{{ $request->destinations->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Date') }}</span>
                                        <div class="text-sm text-slate-600 mt-1">{{ $request->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $request->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Assignment') }}</span>
                                    <div class="mt-1">
                                        @if($request->assigned_to_admin_id)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border border-purple-100 bg-purple-50 text-purple-700 text-xs font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                                {{ $request->assignedAdmin->name }}
                                            </span>
                                        @else
                                            @if(auth()->user()->isSuperAdmin())
                                                <form action="{{ route('admin.sourcing-requests.assign', $request) }}" method="POST">
                                                    @csrf
                                                    <select name="admin_id" onchange="this.form.submit()" class="text-xs py-1 pl-2 pr-6 border-slate-200 rounded bg-slate-50 text-slate-500 focus:ring-1 focus:ring-orange-500 focus:border-orange-500 cursor-pointer hover:bg-white hover:border-slate-300 transition-colors">
                                                        <option value="">{{ __('Assign...') }}</option>
                                                        @foreach($admins as $admin)
                                                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400 italic">{{ __('Unassigned') }}</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Card Footer -->
                            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-2">
                                <a href="{{ route('admin.sourcing-requests.show', [$request, 'page' => request('page')]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    {{ __('View') }}
                                </a>
                                <form action="{{ route('admin.sourcing-requests.destroy', $request) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this request?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-red-600 bg-white border border-red-200 hover:bg-red-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12">
                            <div class="h-12 w-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900">{{ __('No requests found') }}</h3>
                            <p class="text-xs text-slate-500 mt-1">{{ __('No requests match your current criteria.') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($sourcingRequests->hasPages())
                    <div class="bg-white px-6 py-3 border-t border-slate-200">
                        {{ $sourcingRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>