<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Provider;

/**
 * Policy para el modelo Provider
 */
class ProviderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Account $user): bool
    {
        // Todos los usuarios autenticados pueden ver proveedores
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Account $user, Provider $provider): bool
    {
        // Usuario puede ver su propio proveedor
        if ($user->account_id === $provider->account_id) {
            return true;
        }

        // Admin puede ver cualquier proveedor
        if ($user->account_type === 'admin') {
            return true;
        }

        // Padres y escuelas pueden ver proveedores
        return in_array($user->account_type, ['parent', 'school']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Account $user): bool
    {
        // Solo admins pueden crear proveedores
        return $user->account_type === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Account $user, Provider $provider): bool
    {
        // Usuario puede actualizar su propio proveedor
        if ($user->account_id === $provider->account_id) {
            return true;
        }

        // Admin puede actualizar cualquier proveedor
        if ($user->account_type === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Account $user, Provider $provider): bool
    {
        // Solo admins pueden eliminar proveedores
        return $user->account_type === 'admin';
    }
}
