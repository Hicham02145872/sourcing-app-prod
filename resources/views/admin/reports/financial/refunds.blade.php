<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
    ['label' => __('Refund Reports')]
]">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Refund Financial Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Daily -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Daily Total') }}</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($dailyTotal, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">MAD</span>
                    </div>
                </div>

                <!-- Weekly -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Weekly Total') }}</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($weeklyTotal, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">MAD</span>
                    </div>
                </div>

                <!-- Monthly -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-emerald-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Monthly Total') }}</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($monthlyTotal, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">MAD</span>
                    </div>
                </div>

                <!-- Total Refunded -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Total All Time') }}</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($totalRefunded, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">MAD</span>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">{{ __('Refund Trends (Last 6 Months)') }}</h3>
                <div class="h-[300px]">
                    <canvas id="refundChart"></canvas>
                </div>
            </div>

            <!-- Detailed Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">{{ __('Approved Refunds') }}</h3>
                        
                        <form action="{{ route('admin.reports.financial.refunds') }}" method="GET">
                            <select name="period" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>{{ __('All Time') }}</option>
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Order ID') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($refunds as $refund)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.refund-requests.show', $refund) }}" class="text-blue-600 hover:underline">#{{ $refund->sourcingOrder->id }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $refund->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $refund->updated_at->translatedFormat('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">
                                            {{ number_format($refund->amount_approved, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $refund->sourcingOrder->quotation->currency }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                            <span class="px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 uppercase font-bold tracking-tighter">
                                                {{ __($refund->type) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $refunds->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('refundChart').getContext('2d');
            const data = @json($chartData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.label),
                    datasets: [{
                        label: '{{ __('Refund Amount (MAD)') }}',
                        data: data.map(d => d.value),
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f97316',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
