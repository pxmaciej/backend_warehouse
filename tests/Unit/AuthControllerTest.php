<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $authController;

    public function setUp(): void
    {
        parent::setUp();
        $this->authController = new AuthController();
    }

    /** @test */
    public function it_logs_in_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'login' => 'testuser',
            'password' => Hash::make('testpassword'),
        ]);

        $request = Request::create('auth/login', 'POST', [
            'login' => 'testuser',
            'password' => 'testpassword',
        ]);

        $response = $this->authController->login($request);

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('access_token', $responseData);
    }

    /** @test */
    public function testRegister()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'role' => 'user',
            'phone' => '1234567890',
            'login' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'message' => 'User successfully registered',
            'user' => [
                'name' => 'John Doe',
                'login' => 'johndoe',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'login' => 'johndoe',
        ]);
    }

    /** @test */
    public function it_logs_out_user()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $request = Request::create('api/auth/logout', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $response = $this->authController->logout($request);

        $this->assertNull(auth()->user());
    }

    /** @test */
    public function it_deletes_user()
    {
        $user = User::factory()->create();

        $this->authController->destroy($user->id);

        $this->assertDeleted('users', ['id' => $user->id]);
    }


}
