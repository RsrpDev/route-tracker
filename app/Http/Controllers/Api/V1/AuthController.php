<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Http\Resources\Api\V1\AccountResource;
use App\Models\Account;
use App\Models\ParentProfile;
use App\Models\Provider;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

/**
 * Controlador para autenticación de usuarios
 *
 * Rutas:
 * - POST /api/v1/auth/register - Registrar nuevo usuario
 * - POST /api/v1/auth/login - Iniciar sesión
 * - POST /api/v1/auth/logout - Cerrar sesión
 * - GET /api/v1/auth/me - Obtener usuario actual
 * - POST /api/v1/auth/change-password - Cambiar contraseña
 * - POST /api/v1/auth/forgot-password - Solicitar reset de contraseña
 * - POST /api/v1/auth/reset-password - Resetear contraseña
 *
 * Permisos: Ninguno para login/register/forgot/reset, auth:sanctum para me/logout/change-password
 */
class AuthController extends Controller
{
    /**
     * Registrar nuevo usuario
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Hash de la contraseña
        $validated['password_hash'] = Hash::make($validated['password']);
        unset($validated['password']);

        // Crear cuenta
        $account = Account::create($validated);

        // Crear perfil según el tipo de cuenta
        switch ($account->account_type) {
            case 'parent':
                ParentProfile::create([
                    'account_id' => $account->account_id,
                    'address' => $request->input('address'),
                ]);
                break;
            case 'provider':
                Provider::create([
                    'account_id' => $account->account_id,
                    'provider_type' => $request->input('provider_type'),
                    'display_name' => $request->input('display_name'),
                    'contact_email' => $request->input('contact_email'),
                    'contact_phone' => $request->input('contact_phone'),
                    'linked_school_id' => $request->input('linked_school_id'),
                    'default_commission_rate' => $request->input('default_commission_rate', 5.00),
                    'provider_status' => 'pending',
                ]);
                break;
            case 'school':
                School::create([
                    'account_id' => $account->account_id,
                    'legal_name' => $request->input('legal_name'),
                    'rector_name' => $request->input('rector_name'),
                    'nit' => $request->input('nit'),
                    'phone_number' => $request->input('phone_number'),
                    'address' => $request->input('address'),
                ]);
                break;
        }

        // Crear token con habilidades basadas en el rol
        $abilities = $account->getTokenAbilities();
        $token = $account->createToken('auth-token', $abilities)->plainTextToken;

        // Cargar relaciones para el response
        $account->load(['parentProfile', 'provider', 'school']);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'token' => $token,
            'account' => new AccountResource($account),
            'abilities' => $abilities,
        ], 201);
    }

    /**
     * Iniciar sesión de usuario
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $account = Account::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password_hash)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar que la cuenta esté activa
        if ($account->account_status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['La cuenta no está activa. Contacta al administrador.'],
            ]);
        }

        // Crear token con habilidades basadas en el rol
        $abilities = $account->getTokenAbilities();
        $token = $account->createToken('auth-token', $abilities)->plainTextToken;

        // Cargar relaciones para el response
        $account->load(['parentProfile', 'provider', 'school']);

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'account' => new AccountResource($account),
            'abilities' => $abilities,
        ]);
    }

    /**
     * Cerrar sesión del usuario actual
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout exitoso',
        ]);
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function me(Request $request): JsonResponse
    {
        $account = $request->user();

        // Cargar relaciones según el tipo de cuenta
        switch ($account->account_type) {
            case 'parent':
                $account->load('parentProfile.students');
                break;
            case 'provider':
                $account->load('provider.drivers', 'provider.vehicles', 'provider.routes');
                break;
            case 'school':
                $account->load('school.students', 'school.providers');
                break;
        }

        return response()->json([
            'account' => new AccountResource($account),
            'abilities' => $account->getTokenAbilities(),
        ]);
    }

    /**
     * Cambiar contraseña del usuario autenticado
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $account = $request->user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $account->password_hash)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta',
            ], 422);
        }

        // Actualizar contraseña
        $account->update([
            'password_hash' => Hash::make($request->new_password),
        ]);

        // Revocar todos los tokens (forzar re-login)
        $account->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña cambiada exitosamente. Debes iniciar sesión nuevamente.',
        ]);
    }

    /**
     * Solicitar reset de contraseña
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:accounts,email',
        ]);

        $status = Password::broker('accounts')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Se ha enviado un enlace de reset a tu email.',
            ]);
        }

        return response()->json([
            'message' => 'No se pudo enviar el enlace de reset.',
        ], 400);
    }

    /**
     * Resetear contraseña
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:accounts,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('accounts')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($account, $password) {
                $account->forceFill([
                    'password_hash' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Revocar todos los tokens existentes
            $account = Account::where('email', $request->email)->first();
            if ($account) {
                $account->tokens()->delete();
            }

            return response()->json([
                'message' => 'Contraseña reseteada exitosamente.',
            ]);
        }

        return response()->json([
            'message' => 'No se pudo resetear la contraseña.',
        ], 400);
    }

    /**
     * Refrescar token (opcional)
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $account = $request->user();

        // Revocar token actual
        $request->user()->currentAccessToken()->delete();

        // Crear nuevo token con las mismas habilidades
        $abilities = $account->getTokenAbilities();
        $token = $account->createToken('auth-token', $abilities)->plainTextToken;

        return response()->json([
            'message' => 'Token refrescado exitosamente',
            'token' => $token,
            'abilities' => $abilities,
        ]);
    }
}
