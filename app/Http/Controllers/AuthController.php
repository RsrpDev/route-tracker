<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Models\Account;
use App\Models\ParentProfile;
use App\Models\Provider;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controlador de Autenticación - Route Tracker
 *
 * Este controlador maneja toda la autenticación del sistema, incluyendo
 * login, registro y logout. Soporta diferentes tipos de cuentas y
 * redirige automáticamente según el rol del usuario.
 *
 * Funcionalidades principales:
 * - Autenticación de usuarios (login/logout)
 * - Registro de nuevos usuarios
 * - Creación automática de perfiles según tipo de cuenta
 * - Redirección basada en roles
 * - Validación de credenciales
 *
 * Tipos de cuenta soportados:
 * - parent: Padre de familia
 * - provider: Proveedor de transporte
 * - school: Colegio
 * - admin: Administrador (solo por seeders)
 */
class AuthController extends Controller
{
    /**
     * Mostrar formulario de inicio de sesión
     *
     * @return \Illuminate\View\View Vista del formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar inicio de sesión
     *
     * Valida las credenciales del usuario y lo autentica en el sistema.
     * Si las credenciales son válidas, regenera la sesión y redirige
     * al usuario según su rol.
     *
     * @param Request $request Datos del formulario de login
     * @return \Illuminate\Http\RedirectResponse Redirección según el rol
     * @throws ValidationException Si las credenciales son inválidas
     */
    public function login(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Extraer credenciales
        $credentials = $request->only('email', 'password');

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Regenerar sesión por seguridad
            $request->session()->regenerate();

            // Redirigir según el rol del usuario
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Lanzar excepción si las credenciales son inválidas
        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros.'],
        ]);
    }

    /**
     * Mostrar formulario de registro
     *
     * @return \Illuminate\View\View Vista del formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de nuevo usuario
     *
     * Valida los datos del formulario de registro, crea la cuenta del usuario
     * y su perfil correspondiente según el tipo de cuenta seleccionado.
     * Luego autentica al usuario y lo redirige según su rol.
     *
     * @param Request $request Datos del formulario de registro
     * @return \Illuminate\Http\RedirectResponse Redirección según el rol
     */
    public function register(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:30',
            'id_number' => 'required|string|max:50|unique:accounts,id_number',
            'account_type' => 'required|in:parent,provider,school',
            'address' => 'required|string|max:255',
        ]);

        // Crear cuenta principal
        $account = Account::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'id_number' => $request->id_number,
            'account_type' => $request->account_type,
            'account_status' => 'active',
        ]);

        // Crear perfil específico según el tipo de cuenta
        switch ($account->account_type) {
            case 'parent':
                // Crear perfil de padre de familia
                ParentProfile::create([
                    'account_id' => $account->account_id,
                    'address' => $request->address,
                ]);
                break;

            case 'provider':
                // Crear perfil de proveedor (empresa por defecto)
                Provider::create([
                    'account_id' => $account->account_id,
                    'provider_type' => 'company',
                    'display_name' => $request->full_name,
                    'contact_email' => $request->email,
                    'contact_phone' => $request->phone_number,
                    'linked_school_id' => null,
                    'default_commission_rate' => 5.00,
                    'provider_status' => 'pending',
                ]);
                break;

            case 'school':
                // Crear perfil de colegio
                School::create([
                    'account_id' => $account->account_id,
                    'legal_name' => $request->full_name,
                    'rector_name' => 'Por definir',
                    'nit' => $request->id_number,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                ]);
                break;
        }

        // Autenticar al usuario recién registrado
        Auth::login($account);

        // Redirigir según el rol del usuario
        return $this->redirectBasedOnRole($account);
    }

    /**
     * Cerrar sesión del usuario
     *
     * Cierra la sesión del usuario autenticado, invalida la sesión
     * y regenera el token CSRF por seguridad.
     *
     * @param Request $request Datos de la petición
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal
     */
    public function logout(Request $request)
    {
        // Cerrar sesión del usuario
        Auth::logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Redirigir a la página principal
        return redirect('/');
    }

    /**
     * Redirigir al usuario según su rol
     *
     * Determina el dashboard apropiado para el usuario basado en su
     * tipo de cuenta y lo redirige a la ruta correspondiente.
     *
     * @param Account $user Usuario autenticado
     * @return \Illuminate\Http\RedirectResponse Redirección al dashboard apropiado
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->account_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'provider':
                return redirect()->route('provider.dashboard');
            case 'parent':
                return redirect()->route('parent.dashboard');
            case 'school':
                return redirect()->route('dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
}
