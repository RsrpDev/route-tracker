<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros.'],
        ]);
    }

    /**
     * Mostrar el formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191|unique:accounts',
            'phone' => 'nullable|string|max:30',
            'account_type' => 'required|in:parent,provider,school,admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $account = Account::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'account_type' => $request->account_type,
            'password_hash' => Hash::make($request->password),
            'account_status' => 'active',
        ]);

        Auth::login($account);

        return redirect()->route('dashboard');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Redirigir al usuario según su rol
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
                return redirect()->route('dashboard'); // Las escuelas van al dashboard general por ahora
            default:
                return redirect()->route('dashboard');
        }
    }
}
