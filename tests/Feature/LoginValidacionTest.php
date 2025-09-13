<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginValidacionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test: Validación cuando el email está vacío
     * 
     * Descripción: Verifica que el sistema rechace intentos de login cuando el campo email está vacío.
     * El sistema debe devolver un error de validación con el mensaje apropiado en español.
     */
    public function test_login_falla_con_email_vacio()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'El email es requerido.',
                    'errors' => [
                        'email' => ['El email es requerido.']
                    ]
                ]);
    }

    /**
     * Test: Validación cuando la contraseña está vacía
     * 
     * Descripción: Verifica que el sistema rechace intentos de login cuando el campo password está vacío.
     * El sistema debe devolver un error de validación con el mensaje apropiado en español.
     */
    public function test_login_falla_con_password_vacio()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password'])
                ->assertJson([
                    'message' => 'La contraseña es requerida.',
                    'errors' => [
                        'password' => ['La contraseña es requerida.']
                    ]
                ]);
    }

    /**
     * Test: Validación cuando el email no tiene formato válido
     * 
     * Descripción: Verifica que el sistema rechace emails con formato inválido.
     * El sistema debe validar el formato de email y devolver un mensaje de error apropiado.
     */
    public function test_login_falla_con_email_invalido()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'email-invalido',
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
     * Test: Validación cuando ambos campos están vacíos
     * 
     * Descripción: Verifica que el sistema maneje correctamente cuando ambos campos (email y password) están vacíos.
     * Debe devolver errores de validación para ambos campos.
     */
    public function test_login_falla_con_campos_vacios()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email', 'password'])
                ->assertJson([
                    'message' => 'El email es requerido. (and 1 more error)',
                    'errors' => [
                        'email' => ['El email es requerido.'],
                        'password' => ['La contraseña es requerida.']
                    ]
                ]);
    }

    /**
     * Test: Validación cuando se envía un email que no existe
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
     * Test: Validación cuando la contraseña es muy corta
     * 
     * Descripción: Verifica que el sistema maneje contraseñas cortas correctamente.
     * Como el LoginRequest no valida longitud mínima, este test verifica el comportamiento actual.
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

        // Como el LoginRequest no valida longitud mínima, la validación falla en el controlador
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email'])
                ->assertJson([
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                    'errors' => [
                        'email' => ['Las credenciales proporcionadas son incorrectas.']
                    ]
                ]);
    }
}
