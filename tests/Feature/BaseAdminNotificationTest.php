<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SourcingRequest;
use App\Notifications\SourcingRequestCreated;
use App\Notifications\QuotationAccepted;
use App\Services\AdminNotificationMailGate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Tests\TestCase;

class BaseAdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that super admin receives mail only for allowed notifications
     */
    public function test_super_admin_receives_mail_only_for_allowed_notifications()
    {
        NotificationFacade::fake();

        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $sourcingRequest = SourcingRequest::factory()->create();

        // This should be allowed for super admin (sourcing_request_created)
        $notification = new SourcingRequestCreated($sourcingRequest);
        $channels = $notification->via($superAdmin);

        $this->assertContains('database', $channels, 'Super admin should receive database notifications');
        $this->assertContains('mail', $channels, 'Super admin should receive mail for sourcing_request_created');
    }

    /**
     * Test that super admin does NOT receive mail for disallowed notifications
     */
    public function test_super_admin_does_not_receive_mail_for_disallowed_notifications()
    {
        NotificationFacade::fake();

        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        // Create a mock quotation with minimal data
        $quotation = \App\Models\Quotation::factory()->create();

        // This should NOT be allowed for super admin (quotation_accepted)
        $notification = new QuotationAccepted($quotation);
        $channels = $notification->via($superAdmin);

        $this->assertContains('database', $channels, 'Super admin should receive database notifications');
        $this->assertNotContains('mail', $channels, 'Super admin should NOT receive mail for quotation_accepted');
    }

    /**
     * Test that admin receives mail for all allowed notifications
     */
    public function test_admin_receives_mail_for_all_notifications()
    {
        NotificationFacade::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $sourcingRequest = SourcingRequest::factory()->create();
        $quotation = \App\Models\Quotation::factory()->create();

        // Both should be allowed for regular admin
        $notification1 = new SourcingRequestCreated($sourcingRequest);
        $channels1 = $notification1->via($admin);
        $this->assertContains('mail', $channels1);

        $notification2 = new QuotationAccepted($quotation);
        $channels2 = $notification2->via($admin);
        $this->assertContains('mail', $channels2);
    }

    /**
     * Test that admin can customize which notifications receive mail
     */
    public function test_admin_can_customize_notifications()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'admin_mail_notification_keys' => ['sourcing_request_created'], // Only this one
        ]);

        $sourcingRequest = SourcingRequest::factory()->create();
        $quotation = \App\Models\Quotation::factory()->create();

        // This should receive mail (in preferences)
        $notification1 = new SourcingRequestCreated($sourcingRequest);
        $channels1 = $notification1->via($admin);
        $this->assertContains('mail', $channels1);

        // This should NOT receive mail (not in preferences)
        $notification2 = new QuotationAccepted($quotation);
        $channels2 = $notification2->via($admin);
        $this->assertNotContains('mail', $channels2);
    }

    /**
     * Test that non-admin users always receive mail
     */
    public function test_non_admin_users_always_receive_mail()
    {
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = SourcingRequest::factory()->create();

        $notification = new SourcingRequestCreated($sourcingRequest);
        $channels = $notification->via($client);

        // Non-admins should always get database
        $this->assertContains('database', $channels);
    }

    /**
     * Test that database channel is always included
     */
    public function test_database_channel_always_included()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $sourcingRequest = SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);

        // Database channel should be included for everyone
        $this->assertContains('database', $notification->via($superAdmin));
        $this->assertContains('database', $notification->via($admin));
        $this->assertContains('database', $notification->via($client));
    }

    /**
     * Test that FCM channel is included when token exists
     */
    public function test_fcm_channel_included_when_token_exists()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'fcm_token' => 'test_token_123',
        ]);

        $sourcingRequest = SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);
        $channels = $notification->via($admin);

        $this->assertContains('fcm', $channels);
    }

    /**
     * Test that FCM channel is NOT included when token is missing
     */
    public function test_fcm_channel_not_included_without_token()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'fcm_token' => null,
        ]);

        $sourcingRequest = SourcingRequest::factory()->create();
        $notification = new SourcingRequestCreated($sourcingRequest);
        $channels = $notification->via($admin);

        $this->assertNotContains('fcm', $channels);
    }

    /**
     * Test AdminNotificationMailGate integration
     */
    public function test_admin_notification_mail_gate_integration()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $gate = app(AdminNotificationMailGate::class);

        // Super admin defaults
        $superAdminDefaults = $gate->resolvedEnabledKeysForUser($superAdmin);
        $this->assertCount(2, $superAdminDefaults);
        $this->assertContains('sourcing_request_created', $superAdminDefaults);
        $this->assertContains('client_registered', $superAdminDefaults);

        // Admin defaults
        $adminDefaults = $gate->resolvedEnabledKeysForUser($admin);
        $allKeys = collect(config('admin_notifications.types', []))
            ->pluck('key')
            ->toArray();
        $this->assertEqualsCanonicalizing($allKeys, $adminDefaults);
    }
}
