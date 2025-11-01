                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Showing') }} <span class="font-semibold">{{ $users->count() }}</span> {{ __('of') }} <span class="font-semibold">{{ $users->total() }}</span> {{ __('clients') }}
                        </p>
                        <div class="flex space-x-2">
                            {{ $users->links() }}
                        </div>