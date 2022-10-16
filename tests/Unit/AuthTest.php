<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\TestCase;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
/** 
* Login as default API user and get token back.
*
* @return void
*/
    public function testSuccessfulLogin()
    {
        $user = factory(User::class)->create([
           'email' => 'sample@test.com',
           'password' => bcrypt('sample123'),
        ]);


        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $this->json('POST', 'api/auth/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "access_token",
                "token_type",
                "expires_in",
                "user" => [
                   'id',
                   'name',
                   'email',
                   'role',
                   'created_at',
                   'updated_at',
                ],
            ]);

        $this->assertAuthenticated();
    }

}

