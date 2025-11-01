                            @forelse ($users as $user)
                                <tr class="hover:bg-violet-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-8 py-5 text-sm font-medium text-gray-900 dark:text-white">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-violet-400 to-violet-600 rounded-full mr-3 flex items-center justify-center text-white text-sm font-bold">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $user->email }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-sm">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($user->role === 'admin') 
                                                bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                            @elseif($user->role === 'client')
                                                bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300
                                            @else
                                                bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                            @endif
                                        ">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-sm">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-300">
                                            <span class="w-2 h-2 bg-emerald-600 rounded-full"></span>
                                            {{ __('Active') }}
                                        </span>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292m0 0a4 4 0 00-5.292 5.292m0 0a4 4 0 005.292 5.292m0 0a4 4 0 005.292-5.292"/>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No clients found') }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Start by creating your first client account') }}</p>
                                    </td>
                                </tr>
                            @endforelse