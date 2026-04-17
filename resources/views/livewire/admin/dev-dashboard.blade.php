<div class="flex flex-col h-screen bg-slate-50 overflow-hidden">
    <!-- Custom Dev Header -->
    <header class="flex-none bg-slate-900 border-b border-slate-800 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-600 px-2 py-1 text-xs font-black text-white tracking-tighter uppercase">DEV</div>
                <h1 class="text-sm font-bold text-white uppercase tracking-widest">Sourcing App / Console</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="h-4 w-px bg-slate-700"></div>
                <div class="text-[10px] font-bold text-slate-500 uppercase flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500"></span> System Online
                </div>
                <button wire:click="loadData" class="text-[10px] font-bold text-indigo-400 uppercase border border-indigo-400/30 px-2 py-1 hover:bg-indigo-400 hover:text-slate-900 transition-none">
                    Reload Matrix
                </button>
                <div class="h-4 w-px bg-slate-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[10px] font-bold text-slate-400 uppercase hover:text-white transition-none">Disconnect</button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar Navigation -->
        <nav class="w-64 bg-white border-r border-slate-200 p-4 flex flex-col gap-1 overflow-y-auto">
            @foreach(['sync' => 'Sync Monitor', 'testing' => 'Test Tools', 'users' => 'Users', 'tracking' => 'Tracking Monitor', 'cache' => 'Cache Monitor', 'notifications' => 'Notifications', 'flags' => 'Feature Flags', 'health' => 'System Health', 'shortcuts' => 'Shortcuts', 'env' => 'Env Preview', 'mail' => 'Mail Viewer', 'backups' => 'Backups', 'scheduler' => 'Scheduler', 'sessions' => 'Sessions', 'seeders' => 'Seeders', 'queue' => 'Queue Monitor', 'db' => 'Database Explorer', 'audit' => 'Status Audit', 'debug' => 'Debugger'] as $tab => $label)
                <button wire:click="$set('activeTab', '{{ $tab }}')" 
                    class="w-full text-left px-4 py-3 text-xs font-bold uppercase tracking-tight transition-none {{ $activeTab === $tab ? 'bg-slate-900 text-white' : 'text-slate-600 border border-transparent border-b-slate-100 hover:border-slate-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </nav>

        <!-- Main Content Matrix -->
        <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            @if($activeTab === 'sync')
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Google Sheets Sync</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Manual synchronization retry & error logging</p>
                        </div>
                        <button wire:click="forceSyncAll" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest transition-none">
                            Execute Force Sync
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-100 border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Client</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Registry Error</th>
                                    <th class="px-4 py-3 text-right text-[10px] font-black text-slate-600 uppercase">Cmd</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($syncErrors as $error)
                                    <tr class="text-xs">
                                        <td class="px-4 py-3 font-mono text-indigo-600">#{{ $error->display_id }}</td>
                                        <td class="px-4 py-3 text-slate-900 font-bold">{{ $error->user->name }}</td>
                                        <td class="px-4 py-3">
                                            <div class="bg-slate-50 p-2 border border-slate-200 font-mono text-[10px] text-rose-600 break-all">
                                                {{ $error->sheet_sync_error }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button wire:click="forceSyncAll" class="text-indigo-600 font-black uppercase text-[10px] border-b border-indigo-200">Retry</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center text-slate-400 text-xs uppercase font-bold tracking-widest">No active synchronization faults</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'testing')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- User Switcher -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-black text-slate-900 uppercase tracking-tighter border-b border-slate-300 pb-3">User Impersonation Matrix</h2>
                        <div class="bg-white border border-slate-200 overflow-hidden divide-y divide-slate-100">
                            @foreach($users as $user)
                                <div class="p-4 flex justify-between items-center bg-white hover:bg-slate-50 transition-none">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-900">{{ $user->name }}</span>
                                            <span class="text-[9px] px-1 bg-slate-200 text-slate-600 font-black uppercase">{{ $user->role }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $user->email }}</span>
                                    </div>
                                    <button wire:click="impersonate({{ $user->id }})" class="bg-slate-900 text-white px-3 py-1.5 text-[9px] font-black uppercase tracking-widest transition-none">
                                        Access Account
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <!-- Data Generator -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-black text-slate-900 uppercase tracking-tighter border-b border-slate-300 pb-3">Synthetic Data Generation</h2>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach(['orders' => 'Sourcing Orders', 'requests' => 'Sourcing Requests'] as $key => $title)
                                <div class="bg-white border border-slate-200 p-6 space-y-4">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $title }}</h3>
                                    <div class="flex gap-2">
                                        @foreach([1, 5, 20] as $count)
                                            <button wire:click="generateTestData('{{ $key }}', {{ $count }})" class="flex-1 py-3 border border-slate-200 text-slate-900 text-xs font-bold hover:bg-slate-900 hover:text-white transition-none">
                                                Inject {{ $count }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            @endif

            @if($activeTab === 'users')
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Users Management</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">View all users and reset account passwords</p>
                        </div>
                        <div class="w-full max-w-sm">
                            <input
                                wire:model.live.debounce.300ms="userSearch"
                                type="text"
                                placeholder="Search by name, email, role..."
                                class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none"
                            >
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 p-4 grid grid-cols-1 lg:grid-cols-3 gap-3 items-center">
                        <div class="lg:col-span-2 text-[10px] font-black uppercase text-amber-900 tracking-wider">
                            Extra protection enabled: enter your current password to authorize each reset. Protected roles (super_admin, developer) and self-reset are blocked.
                        </div>
                        <input
                            wire:model.defer="currentAdminPassword"
                            type="password"
                            placeholder="Your current password"
                            class="bg-white border border-amber-300 text-xs px-3 py-2 outline-none focus:border-amber-500 transition-none"
                        >
                    </div>

                    <div class="bg-white border border-slate-200 overflow-hidden">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-100 border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">User</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Role</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Email Verification</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Update Password</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($this->filteredUsers as $user)
                                    <tr class="text-xs align-top hover:bg-slate-50 transition-none">
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-500 font-mono">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-[9px] px-2 py-1 bg-slate-200 text-slate-700 font-black uppercase">{{ $user->role }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($user->email_verified_at)
                                                <span class="text-[9px] px-2 py-1 bg-emerald-100 text-emerald-800 font-black uppercase">Verified</span>
                                            @else
                                                <span class="text-[9px] px-2 py-1 bg-rose-100 text-rose-800 font-black uppercase">Not Verified</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                                <input
                                                    wire:model.defer="passwordInputs.{{ $user->id }}"
                                                    type="password"
                                                    placeholder="New password"
                                                    class="bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none"
                                                >
                                                <input
                                                    wire:model.defer="passwordConfirmations.{{ $user->id }}"
                                                    type="password"
                                                    placeholder="Confirm password"
                                                    class="bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none"
                                                >
                                                <button
                                                    wire:click="updateUserPassword({{ $user->id }})"
                                                    class="bg-slate-900 text-white px-3 py-2 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-none"
                                                >
                                                    Save Password
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center text-slate-400 text-xs uppercase font-bold tracking-widest">
                                            No users found for this search
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'tracking')
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Tracking Monitor</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Real-time tracking status & API testing</p>
                        </div>
                        <div class="flex items-center gap-4">
                            @if($hasPendingUpdates)
                                <div wire:poll.5s="loadTrackingOrders" class="flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-200 text-[9px] font-black text-amber-900 uppercase tracking-tighter animate-pulse">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                    Background Updates in progress...
                                </div>
                            @endif
                            <button wire:click="loadData" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest transition-none">
                                Refresh Data
                            </button>
                        </div>
                    </div>

                    {{-- SLA Observability Dashboard --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">SLA Observability (Last 24h)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($trackingStats as $provider => $data)
                                <div class="bg-white border border-slate-200 p-4 space-y-3">
                                    <div class="flex justify-between items-start">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $provider }}</span>
                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase {{ $data['rate'] >= 95 ? 'bg-emerald-100 text-emerald-800' : ($data['rate'] >= 80 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                            {{ $data['rate'] }}% SLA
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <div class="text-xl font-black text-slate-900">{{ $data['success'] }}</div>
                                            <div class="text-[9px] text-slate-400 uppercase font-black">Successes</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-rose-600">{{ $data['failed'] }}</div>
                                            <div class="text-[9px] text-slate-400 uppercase font-black">Failures</div>
                                        </div>
                                    </div>
                                    <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $data['rate'] >= 95 ? 'bg-emerald-500' : ($data['rate'] >= 80 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $data['rate'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach

                            @if(empty($trackingStats))
                                <div class="col-span-full bg-white border border-slate-200 p-8 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest italic">
                                    No tracking activity recorded in the last 24 hours.
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- Manual Tracking Tester --}}
                    <section class="bg-white border border-slate-200 p-6 space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Manual API Tester</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-2">Tracking Number</label>
                                <input wire:model="testTrackingNumber" type="text" placeholder="Enter tracking number..." 
                                    class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none uppercase font-bold tracking-tight">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-2">Carrier (Optional)</label>
                                <input wire:model="testTrackingCarrier" type="text" placeholder="e.g., faster, itdida..." 
                                    class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none uppercase font-bold tracking-tight">
                            </div>
                            <div class="flex items-end">
                                <button wire:click="testTracking" class="w-full bg-slate-900 text-white px-6 py-2 text-xs font-bold uppercase tracking-widest transition-none hover:bg-indigo-600">
                                    Test Tracking
                                </button>
                            </div>
                        </div>

                        @if($trackingTestError)
                            <div class="bg-rose-50 border border-rose-200 p-4 text-rose-600 font-mono text-[10px] font-bold">
                                ERROR: {{ $trackingTestError }}
                            </div>
                        @endif

                        @if($trackingTestResult)
                            <div class="bg-emerald-50 border border-emerald-200 p-4 space-y-2">
                                <div class="flex justify-between items-start">
                                    <span class="text-[10px] font-black text-emerald-900 uppercase">Success</span>
                                    <span class="text-[9px] px-2 bg-emerald-600 text-white font-mono">{{ $trackingTestResult['provider'] ?? 'Unknown' }}</span>
                                </div>
                                <div class="bg-white border border-emerald-200 p-3 font-mono text-[10px] text-slate-600 max-h-64 overflow-y-auto">
                                    <pre>{{ json_encode($trackingTestResult, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </section>

                    {{-- Recent Tracking Orders --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Recent Tracking Orders</h3>
                        <div class="bg-white border border-slate-200">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-slate-100 border-b border-slate-200">
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Order ID</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Client</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Tracking Number</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Carrier</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Status</th>
                                        <th class="px-4 py-3 text-right text-[10px] font-black text-slate-600 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($trackingOrders as $order)
                                        <tr class="text-xs hover:bg-slate-50 transition-none">
                                            <td class="px-4 py-3 font-mono text-indigo-600 font-bold">#{{ $order->display_id }}</td>
                                            <td class="px-4 py-3 text-slate-900">{{ $order->user->name }}</td>
                                            <td class="px-4 py-3 font-mono text-slate-600">{{ $order->tracking_number }}</td>
                                            <td class="px-4 py-3 text-slate-500">{{ $order->shippingCompany?->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">
                                                @if(\Illuminate\Support\Facades\Cache::has("tracking_pending:{$order->tracking_number}"))
                                                    <span class="px-2 py-1 text-[9px] font-black uppercase bg-amber-100 text-amber-800 animate-pulse">
                                                        Processing...
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-[9px] font-black uppercase bg-blue-100 text-blue-800">
                                                        {{ str_replace('_', ' ', $order->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <button wire:click="refreshTracking('{{ $order->tracking_number }}')" class="text-indigo-600 font-black uppercase text-[10px] border-b border-indigo-200 hover:border-indigo-600">
                                                    Refresh
                                                </button>
                                                <a href="{{ route('admin.sourcing-orders.show', $order->id) }}" target="_blank" class="text-slate-600 font-black uppercase text-[10px] border-b border-slate-200 hover:border-slate-600">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-12 text-center text-slate-400 text-xs uppercase font-bold tracking-widest">No tracking orders found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- Tracking Logs --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Recent Tracking Logs</h3>
                        <div class="bg-white border border-slate-200">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-slate-100 border-b border-slate-200">
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Tracking #</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Provider</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Location</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($trackingLogs as $log)
                                        <tr class="text-xs hover:bg-slate-50 transition-none">
                                            <td class="px-4 py-3 font-mono text-slate-900">{{ $log->tracking_number }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $log->provider }}</td>
                                            <td class="px-4 py-3 text-slate-500">{{ $log->status }}</td>
                                            <td class="px-4 py-3 text-slate-400">{{ $log->location ?: 'N/A' }}</td>
                                            <td class="px-4 py-3 font-mono text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-12 text-center text-slate-400 text-xs uppercase font-bold tracking-widest">No tracking logs found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            @endif


            @if($activeTab === 'cache')
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Cache Monitor</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Cache statistics & purge controls</p>
                        </div>
                        <button wire:click="loadCacheStats" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest transition-none">
                            Refresh Stats
                        </button>
                    </div>

                    {{-- Cache Stats --}}
                    <section class="bg-white border border-slate-200 p-6 space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Cache Configuration</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Cache Driver</span>
                                <span class="text-[10px] font-mono font-bold text-slate-900">{{ $cacheStats['driver'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Cache Prefix</span>
                                <span class="text-[10px] font-mono font-bold text-slate-900">{{ $cacheStats['prefix'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- Cache Purge Controls --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Cache Purge Controls</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach([
                                'all' => ['label' => 'Clear All Cache', 'desc' => 'Purge entire application cache', 'color' => 'rose'],
                                'config' => ['label' => 'Clear Config', 'desc' => 'Purge configuration cache', 'color' => 'amber'],
                                'route' => ['label' => 'Clear Routes', 'desc' => 'Purge route cache', 'color' => 'blue'],
                                'view' => ['label' => 'Clear Views', 'desc' => 'Purge compiled views', 'color' => 'emerald']
                            ] as $type => $info)
                                <div class="bg-white border border-slate-200 p-6 flex flex-col justify-between h-48">
                                    <div>
                                        <h4 class="font-black text-sm text-slate-900">{{ $info['label'] }}</h4>
                                        <p class="text-[10px] text-slate-400 uppercase font-bold mt-1">{{ $info['desc'] }}</p>
                                    </div>
                                    <button wire:click="clearCache('{{ $type }}')" 
                                        class="w-full py-3 bg-{{ $info['color'] }}-100 border border-{{ $info['color'] }}-200 text-[10px] font-black uppercase tracking-widest text-{{ $info['color'] }}-900 hover:bg-{{ $info['color'] }}-600 hover:text-white transition-none">
                                        Execute Purge
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Performance Tips --}}
                    <section class="bg-blue-50 border border-blue-200 p-6 space-y-3">
                        <h3 class="text-xs font-black text-blue-900 uppercase tracking-widest">⚡ Performance Tips</h3>
                        <ul class="space-y-2 text-[10px] text-blue-800">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span><strong>Config Cache:</strong> Clear after modifying .env or config files</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span><strong>Route Cache:</strong> Clear after adding/modifying routes</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span><strong>View Cache:</strong> Clear after Blade template changes</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span><strong>All Cache:</strong> Use sparingly - clears everything including data cache</span>
                            </li>
                        </ul>
                    </section>
                </div>
            @endif


            @if($activeTab === 'notifications')
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Notification Tester</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Test FCM notifications & view history</p>
                        </div>
                        <button wire:click="loadRecentNotifications" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest transition-none">
                            Refresh Data
                        </button>
                    </div>

                    {{-- FCM Test Form --}}
                    <section class="bg-white border border-slate-200 p-6 space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">FCM Notification Tester</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-2">Select User</label>
                                <select wire:model="testUserId" class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none uppercase font-bold tracking-tight">
                                    <option value="">-- Select a user --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) {{ $user->fcm_token ? '✓ FCM' : '✗ No FCM' }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[9px] text-slate-400 mt-1">{{ $this->fcmCount }} users have FCM tokens registered</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-2">Notification Title</label>
                                <input wire:model="testNotificationTitle" type="text" placeholder="Test Notification" 
                                    class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none font-bold tracking-tight">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-2">Notification Body</label>
                                <textarea wire:model="testNotificationBody" rows="3" placeholder="This is a test notification from Dev Dashboard" 
                                    class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none resize-none"></textarea>
                            </div>
                            <div>
                                <button wire:click="testFcmNotification" class="w-full bg-slate-900 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest transition-none hover:bg-indigo-600">
                                    Send Test Notification
                                </button>
                            </div>
                        </div>

                        @if($notificationTestError)
                            <div class="bg-rose-50 border border-rose-200 p-4 text-rose-600 font-mono text-[10px] font-bold">
                                ERROR: {{ $notificationTestError }}
                            </div>
                        @endif

                        @if($notificationTestResult)
                            <div class="bg-emerald-50 border border-emerald-200 p-4 space-y-2">
                                <div class="flex justify-between items-start">
                                    <span class="text-[10px] font-black text-emerald-900 uppercase">Notification Sent Successfully</span>
                                    <span class="text-[9px] px-2 bg-emerald-600 text-white font-mono">FCM</span>
                                </div>
                                <div class="bg-white border border-emerald-200 p-3 space-y-2 text-[10px]">
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 font-bold uppercase">User:</span>
                                        <span class="text-slate-900 font-mono">{{ $notificationTestResult['user'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 font-bold uppercase">Email:</span>
                                        <span class="text-slate-900 font-mono">{{ $notificationTestResult['email'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 font-bold uppercase">FCM Token:</span>
                                        <span class="text-slate-900 font-mono">{{ $notificationTestResult['fcm_token'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="border-t border-emerald-200 pt-2 mt-2">
                                        <div class="text-slate-400 font-bold uppercase mb-1">Title:</div>
                                        <div class="text-slate-900">{{ $notificationTestResult['title'] ?? 'N/A' }}</div>
                                    </div>
                                    <div class="border-t border-emerald-200 pt-2">
                                        <div class="text-slate-400 font-bold uppercase mb-1">Body:</div>
                                        <div class="text-slate-900">{{ $notificationTestResult['body'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </section>

                    {{-- Notification History --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Recent Notifications (Database)</h3>
                        <div class="bg-white border border-slate-200">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-slate-100 border-b border-slate-200">
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">ID</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Notifiable</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Read At</th>
                                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentNotifications as $notification)
                                        <tr class="text-xs hover:bg-slate-50 transition-none">
                                            <td class="px-4 py-3 font-mono text-slate-900">{{ substr($notification['id'], 0, 8) }}...</td>
                                            <td class="px-4 py-3 text-slate-600">
                                                <div class="max-w-xs truncate">{{ class_basename($notification['type']) }}</div>
                                            </td>
                                            <td class="px-4 py-3 font-mono text-slate-500">{{ $notification['notifiable_type'] }} #{{ $notification['notifiable_id'] }}</td>
                                            <td class="px-4 py-3">
                                                @if($notification['read_at'])
                                                    <span class="px-2 py-1 text-[9px] font-black uppercase bg-emerald-100 text-emerald-800">Read</span>
                                                @else
                                                    <span class="px-2 py-1 text-[9px] font-black uppercase bg-amber-100 text-amber-800">Unread</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-mono text-[10px] text-slate-400">
                                                {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-12 text-center text-slate-400 text-xs uppercase font-bold tracking-widest">No notifications found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- Info Box --}}
                    <section class="bg-blue-50 border border-blue-200 p-6 space-y-3">
                        <h3 class="text-xs font-black text-blue-900 uppercase tracking-widest">📱 FCM Testing Tips</h3>
                        <ul class="space-y-2 text-[10px] text-blue-800">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span>Only users with registered FCM tokens can receive push notifications</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span>Check the application logs for FCM delivery status and errors</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span>Notifications are stored in the database regardless of FCM delivery</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-black">•</span>
                                <span>Test notifications are marked with metadata: test=true, sent_from=dev_dashboard</span>
                            </li>
                        </ul>
                    </section>
                </div>
            @endif

            @if($activeTab === 'flags')
                <div class="space-y-8 animate-in fade-in duration-500">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                Feature Matrix
                            </h2>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">Runtime Feature Decoupling & Access Control</p>
                        </div>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div class="relative flex-1 md:w-64">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input wire:model.live="featureSearch" type="text" placeholder="Filter features..." 
                                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 text-xs font-bold uppercase tracking-tight focus:border-indigo-500 outline-none transition-all shadow-sm">
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="initializeFeatureFlags" class="bg-slate-800 text-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest hover:bg-slate-700 transition-colors shadow flex items-center gap-2 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Init Defaults
                                </button>
                                <button x-data x-on:click="$dispatch('open-modal', 'add-feature')" class="bg-indigo-600 text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-colors shadow-lg flex items-center gap-2 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    New Feature
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Summary --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                            $visibleCount = collect($featureFlags)->where('status', 'visible')->count();
                            $hiddenCount = collect($featureFlags)->where('status', 'hidden')->count();
                            $comingSoonCount = collect($featureFlags)->where('status', 'coming_soon')->count();
                        @endphp
                        <div class="bg-white p-6 border-b-4 border-emerald-500 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Atmosphere</p>
                                <p class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Visible</p>
                            </div>
                            <div class="text-3xl font-black text-emerald-500">{{ $visibleCount }}</div>
                        </div>
                        <div class="bg-white p-6 border-b-4 border-rose-500 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Darkspace</p>
                                <p class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Hidden</p>
                            </div>
                            <div class="text-3xl font-black text-rose-500">{{ $hiddenCount }}</div>
                        </div>
                        <div class="bg-white p-6 border-b-4 border-amber-500 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Orbiting</p>
                                <p class="text-2xl font-black text-slate-900 uppercase tracking-tighter" title="Visible for Admins, Coming Soon for Clients">Coming Soon</p>
                            </div>
                            <div class="text-3xl font-black text-amber-500">{{ $comingSoonCount }}</div>
                        </div>
                    </div>

                    {{-- Features List --}}
                    <div class="bg-white border border-slate-200 shadow-xl overflow-hidden">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-900 border-b border-slate-800">
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Feature Identifier</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status / Protocols</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Authorized Entities</th>
                                    <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Protocols</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($featureFlags as $flag)
                                    <tr class="hover:bg-slate-50 transition-colors group" wire:key="flag-{{ $flag['id'] }}">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 bg-slate-100 rounded flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $flag['name'] }}</div>
                                                    <div class="text-[10px] font-mono text-indigo-500 font-bold tracking-tighter">{{ $flag['key'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="inline-flex overflow-hidden rounded-sm border border-slate-200">
                                                @foreach([
                                                    'visible' => ['label' => 'Visible', 'color' => 'emerald'], 
                                                    'hidden' => ['label' => 'Hidden', 'color' => 'rose'], 
                                                    'coming_soon' => ['label' => 'Soon', 'color' => 'amber']
                                                ] as $status => $cfg)
                                                    <button wire:click="toggleFeatureStatus({{ $flag['id'] }}, '{{ $status }}')"
                                                        class="px-4 py-2 text-[9px] font-black uppercase transition-all {{ $flag['status'] === $status ? "bg-{$cfg['color']}-600 text-white" : 'bg-white text-slate-400 hover:bg-slate-50' }}">
                                                        {{ $cfg['label'] }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(['admin', 'super_admin', 'client'] as $role)
                                                    @php 
                                                        $roles = $flag['roles'] ?? [];
                                                        $hasRole = in_array($role, $roles);
                                                    @endphp
                                                    <button wire:click="updateFeatureRoles({{ $flag['id'] }}, {{ json_encode($hasRole ? array_values(array_diff($roles, [$role])) : array_merge($roles, [$role])) }})"
                                                        class="px-2 py-1 rounded text-[9px] font-black uppercase border transition-all flex items-center gap-1.5 {{ $hasRole ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-400 border-slate-200 hover:border-slate-300' }}">
                                                        <div class="w-1.5 h-1.5 rounded-full {{ $hasRole ? 'bg-indigo-600 animate-pulse' : 'bg-slate-300' }}"></div>
                                                        {{ str_replace('_', ' ', $role) }}
                                                    </button>
                                                @endforeach
                                                @if(empty($flag['roles']))
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase italic tracking-tighter">Public Access Enabled</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex justify-end gap-3">
                                                <button onclick="confirm('Initiate Decommission Protocol?') || event.stopImmediatePropagation()" 
                                                    wire:click="deleteFeatureFlag({{ $flag['id'] }})"
                                                    class="p-2 text-rose-400 hover:text-rose-600 transition-colors" title="Delete Feature">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center gap-4">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                </div>
                                                <div class="text-xs font-black text-slate-400 uppercase tracking-widest">No matching features found in the mainframe</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Documentation Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <section class="bg-slate-900 p-8 border border-slate-800 space-y-4">
                            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                Frontend Directives
                            </h3>
                            <div class="space-y-4 font-mono text-[11px]">
                                <div class="space-y-1">
                                    <p class="text-slate-500">// Conditional display</p>
                                    <div class="bg-slate-950 p-3 border border-slate-800 text-indigo-300">@@feature('<span class="text-amber-500">key</span>') ... @@endfeature</div>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-slate-500">// Même logique que @@feature (visible + Coming Soon)</p>
                                    <div class="bg-slate-950 p-3 border border-slate-800 text-indigo-300">@@featureVisible('<span class="text-amber-500">key</span>') ... @@endfeatureVisible</div>
                                </div>
                            </div>
                        </section>

                        <section class="bg-white p-8 border border-slate-200 space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Operational Protocols
                            </h3>
                            <ul class="space-y-3 text-[10px] text-slate-600 font-bold uppercase tracking-tight">
                                <li class="flex items-start gap-3">
                                    <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-1"></span>
                                    <span><strong>Visible:</strong> Fully accessible based on role permissions.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-1"></span>
                                    <span><strong>Hidden:</strong> Completely removed from runtime for all users.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-1"></span>
                                    <span><strong>Soon:</strong> Visible for admins; Show placeholder for clients.</span>
                                </li>
                            </ul>
                        </section>
                    </div>

                    {{-- Add New Feature Inline (Simplified) --}}
                    <div x-data="{ open: false }" class="bg-white border border-slate-200 overflow-hidden shadow-lg">
                        <button @click="open = !open" class="w-full px-6 py-4 flex justify-between items-center bg-slate-50 hover:bg-slate-100 transition-colors">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Register New Feature Module</h3>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-collapse class="p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Key (Immutable)</label>
                                    <input wire:model="newFeatureKey" type="text" placeholder="e.g. tracking_v2" 
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-xs font-black uppercase tracking-widest focus:border-indigo-500 outline-none transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Human Identifier</label>
                                    <input wire:model="newFeatureName" type="text" placeholder="e.g. Advanced Tracking" 
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-xs font-black uppercase tracking-widest focus:border-indigo-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button wire:click="addFeatureFlag" class="bg-slate-900 text-white px-10 py-4 text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-xl">
                                    Authorize Module Registration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeTab === 'health')
                <div class="space-y-8">
                    <div class="flex justify-between items-center border-b border-slate-300 pb-3">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">System Health & Telemetry</h2>
                        <button wire:click="loadData" class="text-[10px] font-black uppercase text-indigo-600 border border-indigo-200 px-4 py-2 hover:bg-slate-50 transition-none">Sync Monitors</button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Log Monitor -->
                        <section class="space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest flex justify-between items-center">
                                <span>Laravel Log Stream</span>
                                <span class="text-[9px] text-slate-400 font-mono">Last 200 Lines</span>
                            </h3>
                            <div class="bg-slate-900 border border-slate-800 p-4 h-[500px] overflow-y-auto custom-scrollbar font-mono text-[10px] space-y-1">
                                @forelse($parsedLogs as $log)
                                    <div class="{{ $log['level'] === 'error' ? 'text-rose-500 font-bold' : ($log['level'] === 'warning' ? 'text-amber-500' : 'text-slate-400') }}">
                                        {{ $log['text'] }}
                                    </div>
                                @empty
                                    <div class="text-slate-600 italic">Logs empty / No data stream</div>
                                @endforelse
                            </div>
                        </section>

                        <!-- Queue Explorer -->
                        <section class="space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest flex justify-between items-center">
                                <span>Failed Job Matrix</span>
                                <span class="text-[9px] px-2 bg-rose-600 text-white">{{ count($failedJobsList) }} Failed</span>
                            </h3>
                            <div class="bg-white border border-slate-200 h-[500px] overflow-y-auto custom-scrollbar">
                                <table class="w-full border-collapse">
                                    <thead class="sticky top-0 bg-white">
                                        <tr class="bg-slate-100 border-b border-slate-200">
                                            <th class="px-3 py-2 text-left text-[9px] font-black text-slate-600 uppercase">ID</th>
                                            <th class="px-3 py-2 text-left text-[9px] font-black text-slate-600 uppercase">Queue</th>
                                            <th class="px-3 py-2 text-left text-[9px] font-black text-slate-600 uppercase">Failed At</th>
                                            <th class="px-3 py-2 text-right text-[9px] font-black text-slate-600 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($failedJobsList as $job)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-3 py-2 font-mono text-[10px] text-slate-900">#{{ $job['id'] }}</td>
                                                <td class="px-3 py-2 font-mono text-[10px] text-slate-500">{{ $job['queue'] }}</td>
                                                <td class="px-3 py-2 font-mono text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($job['failed_at'])->diffForHumans() }}</td>
                                                <td class="px-3 py-2 text-right">
                                                    <button wire:click="retryJob({{ $job['id'] }})" class="text-[9px] font-black uppercase text-indigo-600 underline">Retry</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-8 text-center text-slate-300 text-[10px] uppercase font-bold italic">Queue stable / Zero failures</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            @endif

            @if($activeTab === 'shortcuts')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach([
                        'optimize' => 'Production Optimization',
                        'config:cache' => 'Config Preservation',
                        'route:cache' => 'Route Compilation',
                        'view:cache' => 'View Pre-rendering',
                        'queue:restart' => 'Worker Recycling',
                        'optimize:clear' => 'Buffer Purge',
                        'migrate --force' => 'Schema Progression',
                        'storage:link' => 'Volume Linking',
                    ] as $cmd => $desc)
                        <div class="bg-white border border-slate-200 p-6 flex flex-col justify-between h-48">
                            <div>
                                <h3 class="font-black text-sm text-slate-900 font-mono">artisan {{ $cmd }}</h3>
                                <p class="text-[10px] text-slate-400 uppercase font-bold mt-1">{{ $desc }}</p>
                            </div>
                            <button wire:click="runArtisan('{{ $cmd }}')" class="w-full py-3 bg-slate-100 border border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:bg-slate-900 hover:text-white transition-none">
                                Commit Action
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
            
            @if($activeTab === 'sessions')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Active Sessions Monitor</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Inspect and terminate active user sessions</p>
                        </div>
                        <div class="text-[10px] font-mono text-slate-500">
                            Driver: {{ config('session.driver') }} • Lifetime: {{ config('session.lifetime') }} min
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 overflow-hidden">
                        <table class="min-w-full border-collapse">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">User</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">IP</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">User Agent</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Last Activity</th>
                                    <th class="px-4 py-3 text-right text-[10px] font-black text-slate-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($activeSessions as $session)
                                    <tr class="text-xs hover:bg-slate-50">
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-900">
                                                {{ $session['user_name'] ?? 'Guest' }}
                                            </div>
                                            <div class="text-[10px] text-slate-500 font-mono">
                                                {{ $session['user_email'] ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-slate-700">
                                            {{ $session['ip_address'] ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-[10px] text-slate-500 max-w-xs truncate" title="{{ $session['user_agent'] ?? '' }}">
                                                {{ $session['user_agent'] ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-[10px] text-slate-600 font-mono">
                                            {{ \Carbon\Carbon::createFromTimestamp($session['last_activity'] ?? now()->timestamp)->diffForHumans() }}
                                            <div class="text-[9px] text-slate-400">
                                                {{ isset($session['last_activity']) ? \Carbon\Carbon::createFromTimestamp($session['last_activity'])->format('d/m/Y H:i:s') : '' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button wire:click="deleteSession('{{ $session['id'] }}')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-rose-600 border border-rose-200 hover:bg-rose-50 transition-none">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Kill
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center text-slate-400 text-[10px] uppercase font-bold tracking-widest">
                                            No active sessions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'env')
                <div class="bg-white border border-slate-200 flex flex-col h-[calc(100vh-16rem)]">
                    <div class="bg-slate-900 text-[11px] text-emerald-500 font-mono p-4 flex justify-between items-center shrink-0">
                        <span>Environment File Configuration (.env) - <span class="text-amber-500">Sensitive values are masked for security</span></span>
                        <div class="flex gap-4">
                            <span class="text-emerald-500/50 uppercase tracking-widest text-[9px]">Read Only</span>
                        </div>
                    </div>
                    <div class="flex-1 overflow-auto bg-slate-950 p-6 font-mono text-xs text-slate-300 custom-scrollbar">
                        <pre class="leading-relaxed">{{ $envContent }}</pre>
                    </div>
                    <div class="bg-slate-50 border-t border-slate-200 p-4 shrink-0 text-[10px] text-slate-400 uppercase font-bold">
                        Warning: This panel exposes application configuration. Use with extreme caution in production.
                    </div>
                </div>
            @endif

            @if($activeTab === 'mail')
                <div class="flex h-[calc(100vh-16rem)] gap-8">
                    <!-- Mail List -->
                    <div class="w-96 flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Captured Emails</h3>
                            <div class="flex gap-2">
                                <button wire:click="loadCapturedMails" class="text-[9px] font-black text-indigo-600 uppercase border-b border-indigo-200">Refresh</button>
                                <button wire:click="clearAllMails" class="text-[9px] font-black text-rose-600 uppercase border-b border-rose-200">Clear All</button>
                            </div>
                        </div>
                        <div class="flex-1 bg-white border border-slate-200 overflow-y-auto custom-scrollbar divide-y divide-slate-100">
                            @forelse($capturedMails as $mail)
                                <div wire:click="selectMail('{{ $mail['id'] }}')" 
                                    class="p-4 cursor-pointer hover:bg-slate-50 transition-none {{ ($selectedMail['id'] ?? '') === $mail['id'] ? 'bg-slate-100 border-l-4 border-indigo-600' : '' }}">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-[9px] font-mono text-slate-400">{{ $mail['date'] }}</span>
                                        <button wire:click.stop="deleteMail('{{ $mail['id'] }}')" class="text-slate-300 hover:text-rose-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                    <div class="text-[11px] font-black text-slate-900 truncate">S: {{ $mail['subject'] }}</div>
                                    <div class="text-[10px] text-slate-500 truncate">To: {{ $mail['to'] }}</div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400 text-[10px] uppercase font-bold italic">No emails captured yet.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Mail Preview -->
                    <div class="flex-1 bg-white border border-slate-200 flex flex-col overflow-hidden">
                        @if($selectedMail)
                            <div class="p-6 border-b border-slate-100 space-y-2 shrink-0">
                                <div class="grid grid-cols-[80px_1fr] gap-2 text-[10px]">
                                    <span class="font-black text-slate-400 uppercase">Subject:</span>
                                    <span class="font-bold text-slate-900">{{ $selectedMail['subject'] }}</span>
                                    <span class="font-black text-slate-400 uppercase">From:</span>
                                    <span class="text-slate-600">{{ $selectedMail['from'] }}</span>
                                    <span class="font-black text-slate-400 uppercase">To:</span>
                                    <span class="text-slate-600">{{ $selectedMail['to'] }}</span>
                                    <span class="font-black text-slate-400 uppercase">Date:</span>
                                    <span class="text-slate-600">{{ $selectedMail['date'] }}</span>
                                </div>
                            </div>
                            <div class="flex-1 bg-slate-50 overflow-auto p-4 custom-scrollbar">
                                <div class="bg-white border border-slate-200 p-8 shadow-sm mx-auto max-w-4xl min-h-full">
                                    {!! $selectedMail['body'] !!}
                                </div>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center bg-slate-50 text-slate-300 text-xs font-black uppercase tracking-widest italic">
                                Select an email to preview
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($activeTab === 'backups')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Backup System</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Manual Database & File Archives</p>
                        </div>
                        <div class="flex gap-4">
                            <button wire:click="createDatabaseBackup" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-none">
                                Backup Database
                            </button>
                            <button wire:click="createFilesBackup" class="bg-slate-900 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition-none">
                                Backup Public Files
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Backup Name</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Size</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date Created</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($backups as $backup)
                                    <tr class="hover:bg-slate-50 transition-none">
                                        <td class="px-6 py-4 font-mono text-xs text-slate-900">{{ $backup['name'] }}</td>
                                        <td class="px-6 py-4 text-xs text-slate-500">{{ $backup['size'] }}</td>
                                        <td class="px-6 py-4 text-xs text-slate-500">{{ $backup['date'] }}</td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button wire:click="downloadBackup('{{ $backup['name'] }}')" class="inline-flex items-center gap-2 text-[10px] font-black text-indigo-600 uppercase border border-indigo-200 px-3 py-1.5 hover:bg-indigo-50">
                                                Download
                                            </button>
                                            <button onclick="confirm('Delete this backup?') || event.stopImmediatePropagation()" wire:click="deleteBackup('{{ $backup['name'] }}')" class="inline-flex items-center gap-2 text-[10px] font-black text-rose-600 uppercase border border-rose-200 px-3 py-1.5 hover:bg-rose-50">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest italic">
                                            No backups found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'scheduler')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Scheduler Management</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">View & Manually Trigger Scheduled Tasks</p>
                        </div>
                        <button wire:click="loadScheduledTasks" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-none">
                            Refresh List
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Command / Event</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Schedule</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Next Run</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Manual Trigger</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($scheduledTasks as $task)
                                    <tr class="hover:bg-slate-50 transition-none">
                                        <td class="px-6 py-4">
                                            <div class="font-mono text-xs text-slate-900 break-all">{{ $task['command'] }}</div>
                                            @if($task['description'])
                                                <div class="text-[9px] text-slate-400 uppercase font-bold mt-1">{{ $task['description'] }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-indigo-600">{{ $task['expression'] }}</td>
                                        <td class="px-6 py-4 text-xs text-slate-500">{{ $task['next_run'] }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="runScheduledTask('{{ addslashes($task['command']) }}')" class="inline-flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase border border-emerald-200 px-4 py-2 hover:bg-emerald-50 whitespace-nowrap">
                                                Run Now
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest italic">
                                            No scheduled tasks registered.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'sessions')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Active Sessions</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Monitor & Terminate User sessions</p>
                        </div>
                        <button wire:click="loadSessions" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-none">
                            Refresh Sessions
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200 overflow-hidden text-[10px]">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 font-black text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">User</th>
                                    <th class="px-6 py-4">IP Address</th>
                                    <th class="px-6 py-4">User Agent</th>
                                    <th class="px-6 py-4">Last Activity</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-mono text-slate-600">
                                @forelse($activeSessions as $session)
                                    <tr class="hover:bg-slate-50 transition-none">
                                        <td class="px-6 py-4">
                                            @if($session['user_email'])
                                                <div class="font-bold text-slate-900 uppercase tracking-tighter">{{ $session['user_name'] }}</div>
                                                <div class="text-[9px] text-slate-400 truncate w-32">{{ $session['user_email'] }}</div>
                                            @else
                                                <span class="text-slate-300 italic uppercase">Guest</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $session['ip_address'] }}</td>
                                        <td class="px-6 py-4 truncate w-48 max-w-xs" title="{{ $session['user_agent'] }}">{{ $session['user_agent'] }}</td>
                                        <td class="px-6 py-4">{{ date('Y-m-d H:i:s', $session['last_activity']) }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="deleteSession('{{ $session['id'] }}')" class="text-rose-600 font-black uppercase border border-rose-200 px-3 py-1 hover:bg-rose-50">
                                                Terminate
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-black uppercase italic">No active sessions.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'seeders')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Seeder Launcher</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Populate Database with Test Data</p>
                        </div>
                        <button wire:click="loadSeeders" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-none">
                            Reload List
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($availableSeeders as $seeder)
                            <div class="bg-white border border-slate-200 p-6 flex flex-col justify-between h-40">
                                <div class="font-mono text-xs font-black text-slate-900 uppercase tracking-tight">{{ $seeder }}</div>
                                <button 
                                    wire:click="runSeeder('{{ $seeder }}')" 
                                    wire:loading.attr="disabled"
                                    class="w-full py-3 {{ $seederLoading === $seeder ? 'bg-slate-300' : 'bg-slate-900 hover:bg-slate-800' }} text-white text-[10px] font-black uppercase tracking-widest transition-none flex items-center justify-center gap-2">
                                    @if($seederLoading === $seeder)
                                        <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Executing...
                                    @else
                                        Launch Seeder
                                    @endif
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($activeTab === 'queue')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Queue Monitor</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Pending Jobs in `jobs` Table</p>
                        </div>
                        <button wire:click="loadQueueJobs" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-none">
                            Refresh Jobs
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200 overflow-hidden text-[10px]">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 font-black text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Queue</th>
                                    <th class="px-6 py-4">Job Name</th>
                                    <th class="px-6 py-4">Attempts</th>
                                    <th class="px-6 py-4">Available At</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-mono text-slate-600">
                                @forelse($queueJobs as $job)
                                    <tr class="hover:bg-slate-50 transition-none">
                                        <td class="px-6 py-4 font-bold text-slate-900 italic">#{{ $job['id'] }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-500 uppercase font-black text-[8px] tracking-widest">
                                                {{ $job['queue'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 truncate w-64 max-w-sm text-slate-900 font-bold" title="{{ $job['name'] }}">{{ $job['name'] }}</td>
                                        <td class="px-6 py-4">{{ $job['attempts'] }}</td>
                                        <td class="px-6 py-4 text-slate-400">{{ $job['available_at'] }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="confirm('Remove this job from queue?') || event.stopImmediatePropagation()" wire:click="deleteQueueJob({{ $job['id'] }})" class="text-rose-600 font-black uppercase border border-rose-200 px-3 py-1 hover:bg-rose-50">
                                                Purge
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-black uppercase italic">Current Queue is empty.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($activeTab === 'health')
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Service Health</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Connectivity & Dependency Diagnostics</p>
                        </div>
                        <button wire:click="checkHealth" class="bg-indigo-600 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-none">
                            Run Diagnostics
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($healthStatus as $service => $data)
                            <div class="bg-white border border-slate-200 p-6 flex flex-col gap-4">
                                <div class="flex justify-between items-center bg-slate-50 border-b border-slate-100 -m-6 p-6 mb-2">
                                    <span class="font-black text-xs text-slate-900 uppercase tracking-widest">{{ $service }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 {{ $data['ok'] ? 'bg-emerald-500' : 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]' }} animate-pulse"></div>
                                        <span class="text-[10px] font-black uppercase {{ $data['ok'] ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $data['ok'] ? 'Operational' : 'Critical' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Report</div>
                                    <div class="font-mono text-[11px] {{ $data['ok'] ? 'text-slate-600' : 'text-rose-500 font-bold' }} break-all">
                                        {{ $data['message'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($activeTab === 'db')
                <div class="flex h-[calc(100vh-16rem)] gap-8">
                    <!-- Schema Navigation -->
                    <div class="w-72 flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Database Schema</h3>
                            <span class="text-[9px] font-bold text-slate-400">{{ count($this->filteredTables) }} Tables</span>
                        </div>
                        <div class="relative">
                            <input wire:model.live="tableSearch" type="text" placeholder="Search tables..." 
                                class="w-full bg-white border border-slate-200 text-xs px-3 py-2 outline-none focus:border-indigo-500 transition-none uppercase font-bold tracking-tight">
                        </div>
                        <div class="flex-1 bg-white border border-slate-200 overflow-y-auto custom-scrollbar p-2 divide-y divide-slate-100">
                            @foreach($this->filteredTables as $table)
                                <div class="py-3 px-2" wire:key="table-{{ $table }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="text-[11px] font-black text-slate-900 font-mono break-all">{{ $table }}</div>
                                        <span class="text-[9px] font-mono text-slate-400">[{{ $tableCounts[$table] ?? 0 }}]</span>
                                    </div>
                                    <div class="flex gap-1">
                                        <button wire:click="runSqlQuery('SELECT * FROM {{ $table }} LIMIT 50')" class="flex-1 bg-slate-50 border border-slate-200 text-[9px] font-black uppercase py-1 hover:bg-slate-900 hover:text-white transition-none">Data</button>
                                        <button wire:click="runSqlQuery('DESCRIBE {{ $table }}')" class="flex-1 bg-slate-50 border border-slate-200 text-[9px] font-black uppercase py-1 hover:bg-slate-900 hover:text-white transition-none">Schema</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SQL Editor -->
                    <div class="flex-1 flex flex-col gap-6">
                        <div class="flex-none space-y-2">
                            <div class="flex justify-between items-center">
                                <div class="flex gap-2">
                                    <button wire:click="runSqlQuery('SELECT * FROM tables WHERE id = 1')" class="text-[9px] font-black uppercase text-slate-400 border border-slate-200 px-2 py-1 hover:bg-slate-100 italic transition-none">Template: SELECT</button>
                                    <button wire:click="runSqlQuery('UPDATE tables SET column = value WHERE id = 1')" class="text-[9px] font-black uppercase text-slate-400 border border-slate-200 px-2 py-1 hover:bg-slate-100 italic transition-none">Template: UPDATE</button>
                                </div>
                                @if($queryHistory)
                                    <select wire:change="runSqlQuery($event.target.value)" class="text-[9px] font-black uppercase border border-slate-200 bg-transparent px-2 py-1 outline-none transition-none">
                                        <option value="">Recent History</option>
                                        @foreach($queryHistory as $his)
                                            <option value="{{ $his }}">{{ Str::limit($his, 30) }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="bg-slate-900 border border-slate-800 p-1">
                                <textarea wire:model="query" rows="5" class="w-full bg-slate-900 text-indigo-400 font-mono text-xs p-4 outline-none border-0 block resize-none" placeholder="SQL_STATEMENT_HERE:"></textarea>
                                <div class="bg-slate-800 px-4 py-2 flex justify-end">
                                    <button wire:click="runSqlQuery" class="bg-indigo-600 text-white px-8 py-2 text-xs font-black uppercase tracking-widest transition-none">Execute Query</button>
                                </div>
                            </div>
                        </div>

                        @if($queryError)
                            <div class="flex-none bg-rose-50 border border-rose-200 p-4 text-rose-600 font-mono text-[10px] font-bold">
                                ERROR: {{ $queryError }}
                            </div>
                        @endif

                        <!-- Result Display -->
                        <div class="flex-1 bg-white border border-slate-200 overflow-hidden flex flex-col">
                            <div class="bg-slate-50 border-b border-slate-200 px-4 py-2 flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Query Buffer Result</span>
                                    @if($queryResult)
                                        <span class="text-[9px] font-black bg-slate-900 text-white px-2 py-0.5">{{ count($queryResult) }} ROWS</span>
                                        <button wire:click="exportToCsv" class="text-[9px] font-black text-indigo-600 uppercase border-b border-indigo-200">Export CSV</button>
                                    @endif
                                </div>
                                <span class="text-[9px] text-slate-400 uppercase font-black italic">Tip: Click any cell to edit directly</span>
                            </div>
                            <div class="flex-1 overflow-auto custom-scrollbar">
                                @if($queryResult)
                                    <table class="w-full border-collapse">
                                        <thead class="sticky top-0 bg-white">
                                            <tr class="bg-slate-100 border-b border-slate-200">
                                                @foreach(array_keys($queryResult[0]) as $header)
                                                    <th class="px-4 py-2 text-left text-[9px] font-black text-slate-600 uppercase">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($queryResult as $index => $row)
                                                @php 
                                                    $tableName = null;
                                                    if (preg_match('/FROM\s+([a-zA-Z0-9_]+)/i', $query, $matches)) {
                                                        $tableName = $matches[1];
                                                    }
                                                    $rowId = $row['id'] ?? null;
                                                @endphp
                                                <tr class="hover:bg-slate-50 transition-none">
                                                    @foreach($row as $column => $cell)
                                                        @php
                                                            $isEditing = $editingCell && 
                                                                         $editingCell['table'] === $tableName && 
                                                                         $editingCell['id'] === $rowId && 
                                                                         $editingCell['column'] === $column;
                                                        @endphp
                                                        <td class="px-4 py-2 text-[10px] text-slate-500 font-mono whitespace-nowrap border-r border-slate-50 last:border-r-0">
                                                            @if($isEditing)
                                                                <div class="flex gap-1">
                                                                    <input wire:model.defer="editingCell.value" type="text" 
                                                                        class="bg-white border border-indigo-300 text-slate-900 px-1 outline-none text-[10px] font-mono w-full"
                                                                        auto-focus>
                                                                    <button wire:click="updateCell" class="text-emerald-600 font-black">SAVE</button>
                                                                    <button wire:click="cancelEditing" class="text-rose-600 font-black">X</button>
                                                                </div>
                                                            @else
                                                                <div wire:click="startEditing('{{ $tableName ?? 'unknown' }}', '{{ $rowId }}', '{{ $column }}', '{{ $cell }}')" 
                                                                     class="cursor-text min-w-[20px] min-h-[14px]">
                                                                    {{ is_array($cell) ? '[JSON]' : (is_null($cell) ? 'NULL' : $cell) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="h-full flex items-center justify-center text-slate-300 text-[10px] font-black uppercase tracking-tighter italic">Data set empty / Pending execution</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeTab === 'audit')
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-300 pb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Status Audit Trail</h2>
                            <p class="text-xs text-slate-500 uppercase mt-1">Recent order status changes & transitions</p>
                        </div>
                        <button wire:click="loadData" class="bg-slate-900 text-white px-6 py-3 text-xs font-bold uppercase tracking-widest transition-none">
                            Refresh Audit
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-100 border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Order ID</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Client</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Current Status</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Admin Assigned</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-slate-600 uppercase">Last Updated</th>
                                    <th class="px-4 py-3 text-right text-[10px] font-black text-slate-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($statusAudit as $order)
                                    @php
                                        $statusColors = [
                                            'pending_payment' => 'bg-amber-100 text-amber-800',
                                            'paid' => 'bg-emerald-100 text-emerald-800',
                                            'shipment_preparing' => 'bg-blue-100 text-blue-800',
                                            'in_transit_china' => 'bg-indigo-100 text-indigo-800',
                                            'arrival_uae' => 'bg-purple-100 text-purple-800',
                                            'customs_clearance_uae' => 'bg-violet-100 text-violet-800',
                                            'in_transit_uae' => 'bg-sky-100 text-sky-800',
                                            'arrival_destination_country' => 'bg-cyan-100 text-cyan-800',
                                            'customs_clearance_destination_country' => 'bg-teal-100 text-teal-800',
                                            'out_for_delivery' => 'bg-lime-100 text-lime-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'delivery_failed' => 'bg-rose-100 text-rose-800',
                                            'shipment_delayed' => 'bg-orange-100 text-orange-800',
                                            'shipment_returned' => 'bg-red-100 text-red-800',
                                            'shipment_canceled' => 'bg-slate-100 text-slate-800',
                                            'order_completed' => 'bg-emerald-200 text-emerald-900',
                                            'refunded' => 'bg-pink-100 text-pink-800',
                                            'on_hold' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $statusColor = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-800';
                                    @endphp
                                    <tr class="text-xs hover:bg-slate-50 transition-none">
                                        <td class="px-4 py-3 font-mono text-indigo-600 font-bold">#{{ $order->display_id }}</td>
                                        <td class="px-4 py-3 text-slate-900">{{ $order->user->name }}</td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <div class="max-w-xs truncate">{{ $order->quotation->sourcingRequest->product_name }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-[9px] font-black uppercase {{ $statusColor }}">
                                                {{ str_replace('_', ' ', $order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-500">{{ $order->assignedAdmin?->name ?? 'Unassigned' }}</td>
                                        <td class="px-4 py-3 font-mono text-[10px] text-slate-400">
                                            {{ $order->updated_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.sourcing-orders.show', $order->id) }}" target="_blank" class="text-indigo-600 font-black uppercase text-[10px] border-b border-indigo-200 hover:border-indigo-600">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center text-slate-400 text-xs uppercase font-bold tracking-widest">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Status Distribution --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <section class="space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Status Distribution</h3>
                            <div class="bg-white border border-slate-200 p-6 space-y-3">
                                @php
                                    $statusCounts = $statusAudit->groupBy('status')->map->count()->sortDesc();
                                @endphp
                                @foreach($statusCounts as $status => $count)
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-slate-600 uppercase">{{ str_replace('_', ' ', $status) }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 h-2 bg-slate-100">
                                                <div class="h-full bg-indigo-600" style="width: {{ ($count / $statusAudit->count()) * 100 }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-mono font-bold text-slate-900 w-8 text-right">{{ $count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Quick Stats</h3>
                            <div class="bg-white border border-slate-200 p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Orders (Recent)</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-900">{{ $statusAudit->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Pending Payment</span>
                                    <span class="text-[10px] font-mono font-bold text-amber-600">{{ $statusAudit->where('status', 'pending_payment')->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">In Transit</span>
                                    <span class="text-[10px] font-mono font-bold text-blue-600">
                                        {{ $statusAudit->whereIn('status', ['in_transit_china', 'in_transit_uae', 'out_for_delivery'])->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Delivered</span>
                                    <span class="text-[10px] font-mono font-bold text-emerald-600">{{ $statusAudit->where('status', 'delivered')->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Issues (Failed/Delayed/Returned)</span>
                                    <span class="text-[10px] font-mono font-bold text-rose-600">
                                        {{ $statusAudit->whereIn('status', ['delivery_failed', 'shipment_delayed', 'shipment_returned'])->count() }}
                                    </span>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            @endif

            @if($activeTab === 'debug')
                <div class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        @foreach($serverStats as $label => $value)
                            <div class="bg-white border border-slate-200 p-4">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                                <div class="text-sm font-bold text-slate-900 mt-1 uppercase">{{ $value }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Query Inspector -->
                        <section class="space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Real-time Query Monitor</h3>
                            <div class="bg-slate-900 border border-slate-800 divide-y divide-slate-800 overflow-y-auto max-h-[500px] custom-scrollbar">
                                @forelse($debugQueries as $q)
                                    <div class="p-4 space-y-2">
                                        <div class="flex justify-between items-start">
                                            <span class="text-[9px] px-1 bg-indigo-600 text-white font-mono">{{ number_format($q['time'], 2) }}ms</span>
                                        </div>
                                        <div class="text-[10px] font-mono text-indigo-400 break-all leading-tight">{{ $q['sql'] }}</div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-slate-600 text-[10px] uppercase font-bold italic">No queries captured in current lifecycle</div>
                                @endforelse
                            </div>
                        </section>

                        <!-- Lifecycle Telemetry -->
                        <section class="space-y-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-200 pb-2">Session Telemetry</h3>
                            <div class="bg-white border border-slate-200 p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Memory Allocation</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-900">{{ memory_get_peak_usage() / 1024 / 1024 }} MB (Peak)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Current User</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-900">{{ auth()->user()->email }} ({{ auth()->user()->role }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Route Context</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-900">{{ request()->path() }}</span>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }
    </style>
</div>
