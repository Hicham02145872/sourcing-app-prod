<div>
    <div class="overflow-x-auto rounded-lg border border-slate-100 shadow-sm">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Gestionnaire</th>
                    <th scope="col" class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Requêtes</th>
                    <th scope="col" class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Commandes</th>
                    <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Taux Conv.</th>
                    <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Profit Généré</th>
                    <th scope="col" class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-50">
                @forelse($adminPerformance as $admin)
                    <tr class="hover:bg-slate-50/80 transition-colors" wire:key="admin-{{ $admin->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg overflow-hidden border border-orange-200 shadow-sm flex-shrink-0">
                                    @if ($admin->profile_photo_path)
                                        <img class="h-full w-full object-cover" src="{{ Storage::url($admin->profile_photo_path) }}" alt="{{ $admin->name }}" />
                                    @else
                                        <div class="h-full w-full bg-orange-100 text-orange-700 flex items-center justify-center text-xs font-bold transition-transform group-hover:scale-110">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <span class="text-sm font-semibold text-slate-800">{{ $admin->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-medium text-slate-600">
                                {{ $admin->assigned_sourcing_requests_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-medium text-slate-600">
                                {{ $admin->assigned_sourcing_orders_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @php
                                $conversion = $admin->assigned_sourcing_requests_count > 0 
                                    ? round(($admin->assigned_sourcing_orders_count / $admin->assigned_sourcing_requests_count) * 100, 1) 
                                    : 0;
                            @endphp
                            <span class="inline-flex items-center text-xs font-bold {{ $conversion > 30 ? 'text-emerald-600' : 'text-slate-500' }}">
                                {{ $conversion }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold {{ $admin->total_net_profit > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ number_format($admin->total_net_profit ?? 0, 2) }} <span class="text-[10px] uppercase opacity-60">USD</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.sourcing-requests.index', ['admin_id' => $admin->id]) }}" 
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 hover:text-orange-600 hover:border-orange-200 transition-all shadow-sm"
                                   title="Voir les Requêtes">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    REQ
                                </a>
                                <a href="{{ route('admin.sourcing-orders.index', ['admin_id' => $admin->id]) }}" 
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm"
                                   title="Voir les Commandes">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    ORD
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400 italic">
                            Aucun administrateur actif pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($adminPerformance->hasPages())
        <div class="mt-4 px-2">
            {{ $adminPerformance->links() }}
        </div>
    @endif
</div>
