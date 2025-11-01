<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use Illuminate\Support\Facades\Session;

class SourcingRequestTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données en mémoire pour chaque test.

    protected function setUp(): void
    {
        parent::setUp();
        Session::start();
    }

    /** @test */
    public function a_client_can_create_a_sourcing_request(): void
    {
        // 1. ARRANGE : On prépare le contexte.
        // Crée un utilisateur client et des données nécessaires (catégorie, pays, etc.).
        // Note: Cela suppose que vous avez des Factories pour vos modèles.
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $service = Service::factory()->create();

        $sourcingRequestData = [
            'product_name' => 'A very specific product',
            'category_id' => $category->id,
            'destinations' => [
                [
                    'country_id' => $country->id,
                    'service_id' => $service->id,
                    'quantity' => 100,
                ]
            ]
        ];

        // 2. ACT : On exécute l'action que l'on veut tester.
        // On se connecte en tant que client et on envoie une requête POST sur l'endpoint de création.
        $response = $this->actingAs($client)
                             ->withHeaders([
                                 'X-CSRF-TOKEN' => Session::token(),
                             ])
                             ->post(route('client.sourcing-requests.store'), $sourcingRequestData);        // 3. ASSERT : On vérifie que le résultat est celui attendu.
        // On s'attend à être redirigé vers le tableau de bord avec un message de succès.
        $response->assertRedirect(route('client.dashboard'));
        $response->assertSessionHas('status', 'Sourcing request created successfully!');

        // On vérifie également que les données ont bien été enregistrées en base de données.
        $this->assertDatabaseHas('sourcing_requests', [
            'user_id' => $client->id,
            'product_name' => 'A very specific product',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('sourcing_request_destinations', [
            'quantity' => 100,
        ]);
    }

    /** @test */
    public function an_admin_can_view_a_list_of_sourcing_requests(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $service = Service::factory()->create();

        // Create some sourcing requests
        $sourcingRequest1 = \App\Models\SourcingRequest::factory()->create([
            'user_id' => User::factory()->create(['role' => 'client'])->id,
            'category_id' => $category->id,
        ]);
        $sourcingRequest1->destinations()->create([
            'country_id' => $country->id,
            'service_id' => $service->id,
            'quantity' => 50,
        ]);

        $sourcingRequest2 = \App\Models\SourcingRequest::factory()->create([
            'user_id' => User::factory()->create(['role' => 'client'])->id,
            'category_id' => $category->id,
        ]);
        $sourcingRequest2->destinations()->create([
            'country_id' => $country->id,
            'service_id' => $service->id,
            'quantity' => 75,
        ]);

        // Act
        $response = $this->actingAs($admin)->get(route('admin.sourcing-requests.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('admin.sourcing-requests.index');
        $response->assertSee($sourcingRequest1->product_name);
        $response->assertSee($sourcingRequest2->product_name);
    }
}
