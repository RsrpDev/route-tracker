<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginSeguridadTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test: Login con inyección SQL en email
     * 
     * Descripción: Verifica que el sistema bloquee intentos de inyección SQL en el campo email.
     * Debe validar el formato de email y rechazar entradas maliciosas.
     */
    public function test_login_bloquea_inyeccion_sql_en_email()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => "'; DROP TABLE accounts; --",
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

    /**
     * Test: Login con inyección SQL en password
     * 
     * Descripción: Verifica que el sistema maneje intentos de inyección SQL en el campo password.
     * Debe fallar en la autenticación sin exponer información sensible.
     */
    public function test_login_bloquea_inyeccion_sql_en_password()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => "'; DROP TABLE accounts; --"
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
     * Test: Login con XSS en email
     * 
     * Descripción: Verifica que el sistema bloquee intentos de XSS en el campo email.
     * Debe validar el formato de email y rechazar entradas con scripts.
     */
    public function test_login_bloquea_xss_en_email()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '<script>alert("XSS")</script>@example.com',
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

    /**
     * Test: Login con XSS en password
     * 
     * Descripción: Verifica que el sistema maneje intentos de XSS en el campo password.
     * Debe fallar en la autenticación sin exponer información sensible.
     */
    public function test_login_bloquea_xss_en_password()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => '<script>alert("XSS")</script>'
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
     * Test: Login con email muy largo
     * 
     * Descripción: Verifica que el sistema maneje emails excesivamente largos.
     * Como el LoginRequest no valida longitud máxima, debe fallar en la autenticación.
     */
    public function test_login_bloquea_email_muy_largo()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $emailLargo = str_repeat('a', 300) . '@example.com';

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $emailLargo,
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
     * Test: Login con password muy largo
     * 
     * Descripción: Verifica que el sistema maneje contraseñas excesivamente largas.
     * Como el LoginRequest no valida longitud máxima, debe fallar en la autenticación.
     */
    public function test_login_bloquea_password_muy_largo()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $passwordLargo = str_repeat('a', 1000);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => $passwordLargo
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
     * Test: Login con caracteres nulos
     * 
     * Descripción: Verifica que el sistema maneje caracteres nulos en los campos de entrada.
     * Debe rechazar entradas con caracteres de control.
     */
    public function test_login_bloquea_caracteres_nulos()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => "test\x00@example.com",
            'password' => "password\x00123"
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Login con caracteres de control
     * 
     * Descripción: Verifica que el sistema maneje caracteres de control en los campos de entrada.
     * Debe rechazar entradas con caracteres especiales de control.
     */
    public function test_login_bloquea_caracteres_control()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => "test\x07@example.com",
            'password' => "password\x08123"
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Login con email que contiene espacios múltiples
     * 
     * Descripción: Verifica que el sistema maneje correctamente emails con espacios múltiples.
     * Debe limpiar los espacios y permitir el login exitoso.
     */
    public function test_login_maneja_espacios_multiples_en_email()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '   test@example.com   ',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ]);
    }

    /**
     * Test: Login con email que contiene tabulaciones
     * 
     * Descripción: Verifica que el sistema maneje correctamente emails con tabulaciones.
     * Debe limpiar los caracteres de control y permitir el login exitoso.
     */
    public function test_login_maneja_tabulaciones_en_email()
    {
        $account = Account::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'account_status' => 'active'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => "\ttest@example.com\t",
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Login exitoso'
                ]);
    }

    /**
     * Test: Verificar que no se expone información sensible en errores
     * 
     * Descripción: Verifica que el sistema no exponga información sensible como contraseñas o hashes
     * en los mensajes de error. Debe mantener la seguridad de la información del usuario.
     */
    public function test_login_no_expone_informacion_sensible()
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
                ->assertJsonMissing([
                    'password_hash',
                    'password'
                ]);
    }
}
