<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TokensTest extends TestCase
{
    use DatabaseTransactions;

    private $admin;
    private $manager;
    private $url = '/admin/tokens';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->manager = User::factory()->create(['role_key' => PermissionService::R_MANAGER]);
    }

    /**
     * @return void
     */
    public function test_admin_can_see_tokens()
    {
        // Manager shouldn't see tokens
        $this->actingAs($this->manager);
        $response = $this->followingRedirects()->get($this->url);
        $response->assertStatus(403);

        // Admin can see tokens
        $this->actingAs($this->admin);
        $response = $this->followingRedirects()->get($this->url);
        $response->assertStatus(200);
        $response->assertSee('Last used at');
    }

    public function test_admin_can_create_token()
    {
        $token = Token::factory()->create();

        // Manager can not create token
        $this->actingAs($this->manager);
        $response = $this->followingRedirects()->post($this->url . '/store', $token->toArray());
        $response->assertStatus(403);

        // Admin can create token
        $this->actingAs($this->admin);
        $response = $this->followingRedirects()->post($this->url . '/store', $token->toArray());
        $response->assertStatus(200);

        $savedToken = Token::where('token', $token->token)->first();
        $this->assertEquals($token->token, $savedToken->token);
    }

    public function test_admin_can_delete_token()
    {
        $token = Token::factory()->create();

        // Manager can not delete token
        $this->actingAs($this->manager);
        $response = $this->followingRedirects()->get($this->url . '/destroy/' . $token->id);
        $response->assertStatus(403);

        // Admin can delete token
        $this->actingAs($this->admin);
        $response = $this->followingRedirects()->get($this->url . '/destroy/' . $token->id);
        $response->assertStatus(200);
        $this->assertNull(Token::where('token', $token->token)->first());
    }
}
