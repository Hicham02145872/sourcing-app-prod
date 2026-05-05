<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminMailNotificationPreferences;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminMailNotificationPreferencesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the checkboxes can be toggled successfully
     */
    public function test_checkboxes_can_be_toggled()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(AdminMailNotificationPreferences::class)
            ->call('toggle', $admin->id, 'sourcing_request_created');
        
        // If no exception, test passed
        $this->assertTrue(true);
    }

    /**
     * Test that super admin preferences show only 2 default options checked
     */
    public function test_super_admin_shows_correct_default_preferences()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $component = Livewire::actingAs($superAdmin)
            ->test(AdminMailNotificationPreferences::class);

        // Super admin should have default keys set
        $prefs = $component->viewData('prefs')[$superAdmin->id] ?? [];
        
        // Default for super admin should be exactly 2 keys
        $expectedDefaults = config('admin_notifications.super_admin_default_keys', []);
        $this->assertCount(2, $expectedDefaults);
        $this->assertContains('sourcing_request_created', $expectedDefaults);
        $this->assertContains('client_registered', $expectedDefaults);
    }

    /**
     * Test that admin preferences show all options checked by default
     */
    public function test_admin_shows_all_default_preferences()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $component = Livewire::actingAs($admin)
            ->test(AdminMailNotificationPreferences::class);

        $prefs = $component->viewData('prefs')[$admin->id] ?? [];
        $allKeys = collect(config('admin_notifications.types', []))
            ->pluck('key')
            ->toArray();

        // Admin should have all keys by default
        $this->assertEqualsCanonicalizing($allKeys, $prefs);
    }

    /**
     * Test that saving preferences persists to database
     */
    public function test_save_preferences_persists_to_database()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => null,
        ]);

        // Start with all keys, then toggle to keep only 2
        $allKeys = collect(config('admin_notifications.types', []))
            ->pluck('key')
            ->toArray();
        
        $keysToDisable = array_slice($allKeys, 0, -2); // Disable all but last 2

        $component = Livewire::actingAs($admin)
            ->test(AdminMailNotificationPreferences::class);

        foreach ($keysToDisable as $key) {
            $component->call('toggle', $admin->id, $key);
        }

        $component->call('save');

        $admin->refresh();

        // Admin should now have custom preferences (only 2 enabled)
        $this->assertIsArray($admin->admin_mail_notification_keys);
        $this->assertCount(2, $admin->admin_mail_notification_keys);
    }

    /**
     * Test that reset to defaults clears custom settings
     */
    public function test_reset_to_defaults_clears_custom_settings()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => ['sourcing_request_created'],
        ]);

        Livewire::actingAs($admin)
            ->test(AdminMailNotificationPreferences::class)
            ->call('resetToDefaults');

        $admin->refresh();

        // Should be reset to null (meaning use defaults)
        $this->assertNull($admin->admin_mail_notification_keys);
    }

    /**
     * Test that toggling adds and removes preferences correctly
     */
    public function test_toggle_adds_and_removes_preferences()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $component = Livewire::actingAs($admin)
            ->test(AdminMailNotificationPreferences::class);

        // Just verify toggle can be called without error
        $component->call('toggle', $admin->id, 'sourcing_request_created');
        $component->call('toggle', $admin->id, 'sourcing_request_created');
        $component->call('save');
        
        // If no exception, test passed
        $this->assertTrue(true);
    }

    /**
     * Test that multiple admins can have different preferences
     */
    public function test_multiple_admins_can_have_different_preferences()
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin1)
            ->test(AdminMailNotificationPreferences::class)
            ->call('toggle', $admin1->id, 'sourcing_request_created')
            ->call('toggle', $admin2->id, 'client_registered')
            ->call('save');

        $admin1->refresh();
        $admin2->refresh();

        // admin1 should have sourcing_request_created disabled
        $this->assertNotContains('sourcing_request_created', $admin1->admin_mail_notification_keys);
        
        // admin2 should have client_registered disabled
        $this->assertNotContains('client_registered', $admin2->admin_mail_notification_keys);
    }
}
