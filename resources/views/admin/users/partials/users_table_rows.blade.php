@forelse ($users as $user)
    <tr class="hover:bg-slate-50 transition-colors group cursor-pointer" 
        @click="expandedId = (expandedId === {{ $user->id }} ? null : {{ $user->id }})"
        wire:key="user-row-{{ $user->id }}">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    @if ($user->profile_photo_path)
                        <img class="h-10 w-10 rounded-full object-cover border border-slate-200 shadow-sm transition-transform group-hover:scale-105" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" />
                    @else
                        <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-sm border border-orange-200 shadow-sm transition-transform group-hover:scale-105">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="text-sm font-bold text-slate-900 group-hover:text-orange-600 transition-colors">{{ $user->name }}</div>
                    <div class="text-xs text-slate-500 flex items-center gap-1">
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $user->email }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($user->role === 'admin' || $user->role === 'super_admin')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase tracking-tight">
                    {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-tight">
                    Client
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($user->email_verified_at)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-tight">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> {{ __('Active') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-tight">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ __('Pending') }}
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium font-mono">
            {{ $user->created_at->format('d/m/Y') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end gap-2">
                <button type="button" 
                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-bold rounded-md transition-all duration-200 shadow-sm"
                        :class="expandedId === {{ $user->id }} ? 'bg-orange-600 text-white' : 'bg-slate-50 text-slate-600 hover:bg-orange-50 hover:text-orange-600 border border-slate-200'">
                    <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="expandedId === {{ $user->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    {{ __('Activity') }}
                </button>

                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');" class="inline" @click.stop>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all" title="{{ __('Delete User') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>

    <tr x-cloak x-show="expandedId === {{ $user->id }}" class="bg-slate-50 border-x border-slate-200 shadow-inner">
        <td colspan="5" class="px-0 py-0 border-0">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Sourcing Requests -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-4 py-3 bg-slate-50/50 border-b border-slate-200 flex justify-between items-center">
                            <h4 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ __('Recent Requests') }}
                            </h4>
                            <span class="bg-white px-2 py-0.5 rounded text-[10px] font-bold text-slate-400 border border-slate-100">
                                {{ $user->sourcingRequests->count() }}
                            </span>
                        </div>
                        <div class="flex-1 max-h-[300px] overflow-y-auto">
                            @forelse($user->sourcingRequests as $request)
                                <div class="px-4 py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors flex items-center justify-between group/item">
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs font-bold text-slate-800 truncate">{{ $request->product_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $request->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full text-slate-500 bg-slate-100 border border-slate-200 uppercase whitespace-nowrap">
                                            {{ $request->status }}
                                        </span>
                                        <a href="{{ route('admin.sourcing-requests.show', $request) }}" class="text-slate-400 hover:text-orange-600 p-1.5 rounded-lg hover:bg-orange-50 lg:opacity-0 group-hover/item:opacity-100 transition-all bg-white border border-slate-100 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-10 text-center">
                                    <p class="text-[11px] font-medium text-slate-400 italic">{{ __('No requests placed yet') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Sourcing Orders -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-4 py-3 bg-slate-50/50 border-b border-slate-200 flex justify-between items-center">
                            <h4 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                {{ __('Recent Orders') }}
                            </h4>
                            <span class="bg-white px-2 py-0.5 rounded text-[10px] font-bold text-slate-400 border border-slate-100">
                                {{ $user->sourcingOrders->count() }}
                            </span>
                        </div>
                        <div class="flex-1 max-h-[300px] overflow-y-auto">
                            @forelse($user->sourcingOrders as $order)
                                <div class="px-4 py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors flex items-center justify-between group/item">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-800">#{{ $order->id }}</span>
                                            <span class="text-[11px] font-extrabold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ number_format($order->total_amount, 2) }} <small class="text-[8px] uppercase">MAD</small></span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full text-blue-600 bg-blue-50 border border-blue-100 uppercase whitespace-nowrap">
                                            {{ str_replace('_', ' ', $order->status) }}
                                        </span>
                                        <a href="{{ route('admin.sourcing-orders.show', $order) }}" class="text-slate-400 hover:text-blue-600 p-1.5 rounded-lg hover:bg-blue-50 lg:opacity-0 group-hover/item:opacity-100 transition-all bg-white border border-slate-100 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-10 text-center">
                                    <p class="text-[11px] font-medium text-slate-400 italic">{{ __('No orders recorded yet') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-16 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="h-16 w-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">{{ __('No clients found') }}</h3>
                <p class="text-xs text-slate-500 mt-2">{{ __('Try adjusting your search or filters to find specific users.') }}</p>
            </div>
        </td>
    </tr>
@endforelse
