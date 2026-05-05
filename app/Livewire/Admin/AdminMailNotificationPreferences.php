<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\AdminNotificationMailGate;
use Livewire\Component;

class AdminMailNotificationPreferences extends Component
{
    /** @var array<int, list<string>> */
    public array $prefs = [];

    public function mount(AdminNotificationMailGate $gate): void
    {
        $this->reloadPrefs($gate);
    }

    protected function reloadPrefs(AdminNotificationMailGate $gate): void
    {
        $this->prefs = [];
        foreach ($this->staffQuery()->get() as $user) {
            $this->prefs[$user->id] = $gate->resolvedEnabledKeysForUser($user);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function staffQuery()
    {
        return User::query()
            ->whereIn('role', ['admin', 'super_admin'])
            ->orderByRaw("CASE WHEN role = 'super_admin' THEN 0 ELSE 1 END")
            ->orderBy('name');
    }

    public function toggle(int $userId, string $key): void
    {
        if (!isset($this->prefs[$userId])) {
            $this->prefs[$userId] = [];
        }
        $set = $this->prefs[$userId];
        if (in_array($key, $set, true)) {
            $this->prefs[$userId] = array_values(array_diff($set, [$key]));
        } else {
            $this->prefs[$userId][] = $key;
            sort($this->prefs[$userId]);
        }
    }

    public function save(): void
    {
        $gate = app(AdminNotificationMailGate::class);

        foreach ($this->staffQuery()->get() as $user) {
            $selected = array_values(array_unique($this->prefs[$user->id] ?? []));
            sort($selected);
            $defaultKeys = AdminNotificationMailGate::defaultEnabledKeysForRole($user->role);
            sort($defaultKeys);

            if ($selected === $defaultKeys) {
                $user->admin_mail_notification_keys = null;
            } else {
                $user->admin_mail_notification_keys = $selected;
            }
            $user->save();
        }

        $this->reloadPrefs($gate);

        session()->flash('status', __('Mail notification preferences saved.'));
    }

    public function resetToDefaults(): void
    {
        foreach ($this->staffQuery()->get() as $user) {
            $user->admin_mail_notification_keys = null;
            $user->save();
        }

        $this->reloadPrefs(app(AdminNotificationMailGate::class));

        session()->flash('status', __('Preferences reset to role defaults (admins: all emails; super admins: new requests & new client registration only).'));
    }

    public function render()
    {
        return view('livewire.admin.admin-mail-notification-preferences', [
            'staff' => $this->staffQuery()->get(),
            'types' => config('admin_notifications.types', []),
        ]);
    }
}
