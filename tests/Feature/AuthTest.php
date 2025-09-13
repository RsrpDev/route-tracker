<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\ParentProfile;
use App\Models\Provider;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test 1: Usuario puede registrarse como padre
     */
    public function test_user_can_register_as_parent()
    {
        $parentData = [
            'full_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'account_type' => 'parent',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => $this->faker->address,
        ];

        $response = $this->postJson('/api/v1/auth/register', $parentData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status',
                        'parent_profile'
                    ],
                    'abilities'
                ]);

        $this->assertDatabaseHas('accounts', [
            'email' => $parentData['email'],
            'account_type' => 'parent',
            'account_status' => 'active'
        ]);

        $this->assertDatabaseHas('parents', [
            'address' => $parentData['address']
        ]);
    }

    /**
     * Test 2: Usuario puede registrarse como proveedor
     */
    public function test_user_can_register_as_provider()
    {
        $providerData = [
            'full_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'account_type' => 'provider',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'provider_type' => 'company',
            'display_name' => $this->faker->company,
            'contact_email' => $this->faker->safeEmail,
            'contact_phone' => $this->faker->phoneNumber,
            'default_commission_rate' => 5.00,
        ];

        $response = $this->postJson('/api/v1/auth/register', $providerData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status',
                        'provider'
                    ],
                    'abilities'
                ]);

        $this->assertDatabaseHas('accounts', [
            'email' => $providerData['email'],
            'account_type' => 'provider',
            'account_status' => 'active'
        ]);

        $this->assertDatabaseHas('providers', [
            'provider_type' => 'company',
            'provider_status' => 'pending'
        ]);
    }

    /**
     * Test 3: Usuario puede registrarse como escuela
     */
    public function test_user_can_register_as_school()
    {
        $schoolData = [
            'full_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'account_type' => 'school',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'legal_name' => $this->faker->company,
            'rector_name' => $this->faker->name,
            'nit' => $this->faker->numerify('##########'),
            'address' => $this->faker->address,
        ];

        $response = $this->postJson('/api/v1/auth/register', $schoolData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status',
                        'school'
                    ],
                    'abilities'
                ]);

        $this->assertDatabaseHas('accounts', [
            'email' => $schoolData['email'],
            'account_type' => 'school',
            'account_status' => 'active'
        ]);

        $this->assertDatabaseHas('schools', [
            'legal_name' => $schoolData['legal_name'],
            'nit' => $schoolData['nit']
        ]);
    }

    /**
     * Test 4: Usuario puede hacer login con credenciales válidas
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account',
                    'abilities'
                ]);
    }

    /**
     * Test 5: Usuario no puede hacer login con credenciales inválidas
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test 6: Usuario no puede hacer login con cuenta inactiva
     */
    public function test_user_cannot_login_with_inactive_account()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'inactive'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test 7: Usuario autenticado puede hacer logout
     */
    public function test_authenticated_user_can_logout()
    {
        $account = Account::factory()->create([
            'account_status' => 'active'
        ]);

        $token = $account->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
                ->assertJson(['message' => 'Logout exitoso']);

        // Verificar que el token fue eliminado
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $account->account_id
        ]);
    }

    /**
     * Test 8: Validación de datos requeridos en registro
     */
    public function test_registration_requires_valid_data()
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'full_name',
                    'email',
                    'account_type',
                    'password'
                ]);
    }
}
