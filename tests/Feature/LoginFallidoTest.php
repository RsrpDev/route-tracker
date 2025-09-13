<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginFallidoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test: Login falla con contraseña incorrecta
     * 
     * Descripción: Verifica que el sistema rechace intentos de login cuando la contraseña es incorrecta.
     * Debe devolver un mensaje genérico de credenciales incorrectas para no revelar información específica.
     */
    public function test_login_falla_con_password_incorrecto()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password_incorrecto'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                    'errors' => [
                        'email' => ['Las credenciales proporcionadas son incorrectas.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con cuenta inactiva
     * 
     * Descripción: Verifica que el sistema rechace intentos de login cuando la cuenta está marcada como inactiva.
     * Debe devolver un mensaje específico indicando que la cuenta no está activa.
     */
    public function test_login_falla_con_cuenta_inactiva()
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
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'La cuenta no está activa. Contacta al administrador.',
                    'errors' => [
                        'email' => ['La cuenta no está activa. Contacta al administrador.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con cuenta pendiente
     * 
     * Descripción: Verifica que el sistema rechace intentos de login cuando la cuenta está en estado pendiente.
     * Debe devolver un mensaje específico indicando que la cuenta no está activa.
     */
    public function test_login_falla_con_cuenta_pendiente()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'pending'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'La cuenta no está activa. Contacta al administrador.',
                    'errors' => [
                        'email' => ['La cuenta no está activa. Contacta al administrador.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con cuenta suspendida
     * 
     * Descripción: Verifica que el sistema rechace intentos de login cuando la cuenta está bloqueada.
     * Debe devolver un mensaje específico indicando que la cuenta no está activa.
     */
    public function test_login_falla_con_cuenta_suspendida()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'blocked'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'La cuenta no está activa. Contacta al administrador.',
                    'errors' => [
                        'email' => ['La cuenta no está activa. Contacta al administrador.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con email que no existe
     * 
     * Descripción: Verifica que el sistema rechace intentos de login con un email que no existe en la base de datos.
     * Debe devolver un mensaje genérico de credenciales incorrectas para no revelar información.
     */
    public function test_login_falla_con_email_inexistente()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'usuario@inexistente.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                    'errors' => [
                        'email' => ['Las credenciales proporcionadas son incorrectas.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con email correcto pero contraseña muy corta
     * 
     * Descripción: Verifica que el sistema maneje contraseñas cortas correctamente.
     * Como el LoginRequest no valida longitud mínima, debe fallar en la autenticación.
     */
    public function test_login_falla_con_password_muy_corto()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => '123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                    'errors' => [
                        'email' => ['Las credenciales proporcionadas son incorrectas.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con múltiples intentos incorrectos
     * 
     * Descripción: Verifica que el sistema maneje múltiples intentos de login incorrectos.
     * Debe mantener la consistencia en los mensajes de error para cada intento.
     */
    public function test_login_falla_con_multiples_intentos()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        // Primer intento incorrecto
        $response1 = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password_incorrecto1'
        ]);

        $response1->assertStatus(422);

        // Segundo intento incorrecto
        $response2 = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password_incorrecto2'
        ]);

        $response2->assertStatus(422);

        // Tercer intento incorrecto
        $response3 = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password_incorrecto3'
        ]);

        $response3->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                    'errors' => [
                        'email' => ['Las credenciales proporcionadas son incorrectas.']
                    ]
                ]);
    }

    /**
     * Test: Login falla con caracteres especiales en email
     * 
     * Descripción: Verifica que el sistema rechace emails con caracteres especiales o scripts.
     * Debe validar el formato de email y rechazar entradas maliciosas.
     */
    public function test_login_falla_con_caracteres_especiales_en_email()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com<script>alert("xss")</script>',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'El formato del email no es válido.',
                    'errors' => [
                        'email' => ['El formato del email no es válido.']
                    ]
                ]);
    }
}
