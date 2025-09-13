<?php

namespace App\Policies;

use App\Models\Account;

/**
 * Policy para el modelo Account
 */
class AccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Account $user): bool
    {
        // Solo admins pueden ver todas las cuentas
        return $user->account_type === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Account $user, Account $account): bool
    {
        // Usuario puede ver su propia cuenta
        if ($user->account_id === $account->account_id) {
            return true;
        }

        // Admin puede ver cualquier cuenta
        if ($user->account_type === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Account $user): bool
    {
        // Solo admins pueden crear cuentas
        return $user->account_type === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Account $user, Account $account): bool
    {
        // Usuario puede actualizar su propia cuenta
        if ($user->account_id === $account->account_id) {
            return true;
        }

        // Admin puede actualizar cualquier cuenta
        if ($user->account_type === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Account $user, Account $account): bool
    {
        // Solo admins pueden eliminar cuentas
        return $user->account_type === 'admin';
    }
}
