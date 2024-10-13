<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'adm@mail.com',
            'password' => bcrypt('password'),
            'status' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'adm@mail.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $response = $this->post('/login', [
            'email' => 'adm@mail.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'adm2@mail.com',
            'password' => bcrypt('password'),
            'status' => 'admin'
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
