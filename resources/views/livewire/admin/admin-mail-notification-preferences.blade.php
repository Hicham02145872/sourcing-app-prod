<div class="space-y-6">
    @if (session('status'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-blue-50/80 p-4 text-sm text-blue-900 dark:border-slate-700 dark:bg-blue-950/30 dark:text-blue-200">
        <p class="font-semibold">{{ __('How it works') }}</p>
        <ul class="mt-2 list-disc space-y-1 ps-5 text-blue-800/90 dark:text-blue-300/90">
            <li>{{ __('Only the email channel is filtered here. In-app notifications in the admin panel are unchanged.') }}</li>
            <li>{{ __('Administrators: by default, all notification types below are sent by email.') }}</li>
            <li>{{ __('Super admins: by default, only “new sourcing request” and “new client registration” emails are sent.') }}</li>
            <li>{{ __('Adjust checkboxes per user, then save. “Reset defaults” clears custom settings and applies role defaults again.') }}</li>
        </ul>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-700">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50">
                    <th scope="col" class="sticky left-0 z-10 bg-slate-50 px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500 dark:bg-slate-900/95 dark:text-slate-400">
                        {{ __('Administrator') }}
                    </th>
                    @foreach($types as $type)
                        <th scope="col" class="min-w-[10rem] px-2 py-3 text-center text-[10px] font-bold uppercase leading-tight text-slate-600 dark:text-slate-300">
                            {{ __($type['label']) }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($staff as $member)
                    <tr wire:key="pref-row-{{ $member->id }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-700/20">
                        <td class="sticky left-0 z-10 bg-white px-4 py-3 dark:bg-slate-800">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $member->name }}</div>
                            <div class="text-xs text-slate-500">{{ $member->email }}</div>
                            <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $member->role === 'super_admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200' }}">
                                {{ $member->role === 'super_admin' ? __('Super admin') : __('Admin') }}
                            </span>
                        </td>
                        @foreach($types as $type)
                            @php $key = $type['key']; @endphp
                            <td class="px-2 py-3 text-center align-middle">
                                <input type="checkbox"
                                       class="h-4 w-4 rounded border-slate-300 text-[#EF7722] focus:ring-[#EF7722] cursor-pointer transition-all"
                                       x-data
                                       @checked(in_array($key, $prefs[$member->id] ?? [], true))
                                       @change="$wire.toggle({{ $member->id }}, '{{ $key }}')"
                                       wire:key="pref-{{ $member->id }}-{{ $key }}"
                                />
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button type="button"
                wire:click="save"
                class="inline-flex items-center gap-2 rounded-lg bg-[#EF7722] px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-[#e06a15] focus:outline-none focus:ring-2 focus:ring-[#EF7722]/40">
            {{ __('Save preferences') }}
        </button>
        <button type="button"
                wire:click="resetToDefaults"
                wire:confirm="{{ __('Reset all admins and super admins to their role email defaults?') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
            {{ __('Reset to role defaults') }}
        </button>
    </div>
</div>
