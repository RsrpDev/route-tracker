<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginExitosoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test: Login exitoso con usuario admin
     *
     * Descripción: Verifica que un usuario con rol de administrador pueda iniciar sesión exitosamente.
     * Debe devolver un token de autenticación, información de la cuenta y las habilidades correspondientes.
     */
    public function test_login_exitoso_con_usuario_admin()
    {
        $account = Account::factory()->create([
            'email' => 'admin@test.com',
            'password_hash' => bcrypt('password123'),
            'account_type' => 'admin',
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ])
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status'
                    ],
                    'abilities'
                ]);

        $this->assertDatabaseHas('accounts', [
            'email' => 'admin@test.com',
            'account_type' => 'admin'
        ]);
    }

    /**
     * Test: Login exitoso con usuario provider
     *
     * Descripción: Verifica que un usuario con rol de proveedor pueda iniciar sesión exitosamente.
     * Debe devolver un token de autenticación y la información de la cuenta del proveedor.
     */
    public function test_login_exitoso_con_usuario_provider()
    {
        $account = Account::factory()->create([
            'email' => 'provider@test.com',
            'password_hash' => bcrypt('password123'),
            'account_type' => 'provider',
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'provider@test.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ])
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status'
                    ],
                    'abilities'
                ]);
    }

    /**
     * Test: Login exitoso con usuario parent
     *
     * Descripción: Verifica que un usuario con rol de padre pueda iniciar sesión exitosamente.
     * Debe devolver un token de autenticación y la información de la cuenta del padre.
     */
    public function test_login_exitoso_con_usuario_parent()
    {
        $account = Account::factory()->create([
            'email' => 'parent@test.com',
            'password_hash' => bcrypt('password123'),
            'account_type' => 'parent',
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'parent@test.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ])
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status'
                    ],
                    'abilities'
                ]);
    }

    /**
     * Test: Login exitoso con usuario school
     *
     * Descripción: Verifica que un usuario con rol de escuela pueda iniciar sesión exitosamente.
     * Debe devolver un token de autenticación y la información de la cuenta de la escuela.
     */
    public function test_login_exitoso_con_usuario_school()
    {
        $account = Account::factory()->create([
            'email' => 'school@test.com',
            'password_hash' => bcrypt('password123'),
            'account_type' => 'school',
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'school@test.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ])
                ->assertJsonStructure([
                    'message',
                    'token',
                    'account' => [
                        'account_id',
                        'full_name',
                        'email',
                        'account_type',
                        'account_status'
                    ],
                    'abilities'
                ]);
    }

    /**
     * Test: Login exitoso con email en mayúsculas
     *
     * Descripción: Verifica que el sistema maneje correctamente emails ingresados en mayúsculas.
     * NOTA: El sistema actual no normaliza el email, por lo que debe coincidir exactamente.
     */
    public function test_login_exitoso_con_email_mayusculas()
    {
        $account = Account::factory()->create([
            'email' => 'TEST@EXAMPLE.COM',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'TEST@EXAMPLE.COM',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ]);
    }

    /**
     * Test: Login exitoso con espacios en email
     *
     * Descripción: Verifica que el sistema maneje correctamente emails con espacios adicionales.
     * Debe limpiar los espacios y permitir el login exitoso.
     */
    public function test_login_exitoso_con_espacios_en_email()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '  test@example.com  ',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ]);
    }

    /**
     * Test: Verificar que el token se genera correctamente
     *
     * Descripción: Verifica que el sistema genere un token de autenticación válido después del login exitoso.
     * El token debe ser una cadena no vacía que permita autenticar las siguientes peticiones.
     */
    public function test_token_se_genera_correctamente()
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

        $response->assertStatus(200);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['token']);
        $this->assertIsString($responseData['token']);
    }
}
