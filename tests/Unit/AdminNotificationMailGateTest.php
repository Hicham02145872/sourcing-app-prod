<?php

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\SourcingRequestCreated;
use App\Services\AdminNotificationMailGate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationMailGateTest extends TestCase
{
    use RefreshDatabase;

    private AdminNotificationMailGate $gate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gate = app(AdminNotificationMailGate::class);
    }

    /**
     * Test that all keys are returned from allKeys()
     */
    public function test_all_keys_returns_all_notification_types()
    {
        $allKeys = AdminNotificationMailGate::allKeys();

        $this->assertIsArray($allKeys);
        $this->assertNotEmpty($allKeys);
        
        $expectedKeys = collect(config('admin_notifications.types', []))
            ->pluck('key')
            ->toArray();
        
        $this->assertEqualsCanonicalizing($expectedKeys, $allKeys);
    }

    /**
     * Test default enabled keys for admin role
     */
    public function test_default_enabled_keys_for_admin_role()
    {
        $adminDefaults = AdminNotificationMailGate::defaultEnabledKeysForRole('admin');

        $allKeys = AdminNotificationMailGate::allKeys();
        $this->assertEqualsCanonicalizing($allKeys, $adminDefaults);
    }

    /**
     * Test default enabled keys for super_admin role
     */
    public function test_default_enabled_keys_for_super_admin_role()
    {
        $superAdminDefaults = AdminNotificationMailGate::defaultEnabledKeysForRole('super_admin');

        $expectedKeys = config('admin_notifications.super_admin_default_keys', []);
        $this->assertEqualsCanonicalizing($expectedKeys, $superAdminDefaults);
        $this->assertCount(2, $superAdminDefaults);
    }

    /**
     * Test resolved enabled keys for user with null preferences
     */
    public function test_resolved_enabled_keys_for_user_with_null_preferences()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => null,
        ]);

        $resolved = $this->gate->resolvedEnabledKeysForUser($admin);

        $expectedKeys = AdminNotificationMailGate::defaultEnabledKeysForRole('admin');
        $this->assertEqualsCanonicalizing($expectedKeys, $resolved);
    }

    /**
     * Test resolved enabled keys for user with empty preferences
     */
    public function test_resolved_enabled_keys_for_user_with_empty_preferences()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => [],
        ]);

        $resolved = $this->gate->resolvedEnabledKeysForUser($admin);

        // Empty array means no notifications
        $this->assertEmpty($resolved);
    }

    /**
     * Test resolved enabled keys for user with custom preferences
     */
    public function test_resolved_enabled_keys_for_user_with_custom_preferences()
    {
        $customKeys = ['sourcing_request_created', 'client_registered'];
        
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => $customKeys,
        ]);

        $resolved = $this->gate->resolvedEnabledKeysForUser($admin);

        $this->assertEqualsCanonicalizing($customKeys, $resolved);
    }

    /**
     * Test that invalid keys are filtered out
     */
    public function test_invalid_keys_are_filtered_out()
    {
        $keysWithInvalid = ['sourcing_request_created', 'invalid_key_xyz', 'client_registered'];
        
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => $keysWithInvalid,
        ]);

        $resolved = $this->gate->resolvedEnabledKeysForUser($admin);

        $this->assertNotContains('invalid_key_xyz', $resolved);
        $this->assertContains('sourcing_request_created', $resolved);
        $this->assertContains('client_registered', $resolved);
    }

    /**
     * Test allowsMail for admin with allowed notification
     */
    public function test_allows_mail_for_admin_with_allowed_notification()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sourcingRequest = \App\Models\SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);

        $allowed = $this->gate->allowsMail($admin, $notification);

        $this->assertTrue($allowed);
    }

    /**
     * Test allowsMail for admin with disallowed notification
     */
    public function test_allows_mail_for_admin_with_disallowed_notification()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => [], // No notifications allowed
        ]);
        
        $sourcingRequest = \App\Models\SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);

        $allowed = $this->gate->allowsMail($admin, $notification);

        $this->assertFalse($allowed);
    }

    /**
     * Test allowsMail for non-admin user (should always be true)
     */
    public function test_allows_mail_for_non_admin_user()
    {
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = \App\Models\SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);

        $allowed = $this->gate->allowsMail($client, $notification);

        $this->assertTrue($allowed);
    }

    /**
     * Test notification key mapping
     */
    public function test_notification_key_mapping()
    {
        $sourcingRequest = \App\Models\SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);

        $key = $this->gate->notificationKey($notification);

        $this->assertEquals('sourcing_request_created', $key);
    }

    /**
     * Test that super admin receives only default notifications by default
     */
    public function test_super_admin_receives_only_default_notifications()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $sourcingRequest = \App\Models\SourcingRequest::factory()->create();

        // sourcing_request_created is in defaults for super admin
        $notification1 = new SourcingRequestCreated($sourcingRequest);
        $this->assertTrue($this->gate->allowsMail($superAdmin, $notification1));

        // quotation_accepted is NOT in defaults for super admin
        $quotation = \App\Models\Quotation::factory()->create();
        $notification2 = new \App\Notifications\QuotationAccepted($quotation);
        $this->assertFalse($this->gate->allowsMail($superAdmin, $notification2));
    }
}
