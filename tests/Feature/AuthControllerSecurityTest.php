<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Tests\TestCase;

class AuthControllerSecurityTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_allows_authenticated_user_to_access_user_profile()
    {
        // Tworzenie użytkownika z rolą 'user'
        $user = User::factory()->create(['role' => 'user']);

        // Udawanie uwierzytelnienia jako użytkownik
        $response = $this->actingAs($user)->getJson('/api/auth/profile');

        // Sprawdzenie, czy odpowiedź jest udana
        $response->assertSuccessful();

        // Sprawdzenie struktury JSON odpowiedzi
        $response->assertJsonStructure(['id', 'name', 'login', 'role']);
    }

    /** @test */
    public function it_allows_authenticated_admin_to_access_user_profile()
    {
        // Tworzenie użytkownika z rolą 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Udawanie uwierzytelnienia jako administrator
        $response = $this->actingAs($admin)->getJson('/api/auth/profile');

        // Sprawdzenie, czy odpowiedź jest udana
        $response->assertSuccessful();

        // Sprawdzenie struktury JSON odpowiedzi
        $response->assertJsonStructure(['id', 'name', 'login', 'role']);
    }

    /** @test */
    public function it_denies_access_to_unauthenticated_user()
    {
        // Wywołanie żądania bez uwierzytelnienia
        $response = $this->getJson('/api/auth/profile');

        // Sprawdzenie, czy odpowiedź zawiera błąd 401 (Unauthorized)
        $response->assertUnauthorized();
    }
}
