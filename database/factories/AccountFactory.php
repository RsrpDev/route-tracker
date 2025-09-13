<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'password_hash' => bcrypt('password123'),
            'account_type' => $this->faker->randomElement(['admin', 'provider', 'parent', 'school']),
            'account_status' => 'active',
        ];
    }



    /**
     * Indicate that the account is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => 'admin',
        ]);
    }

    /**
     * Indicate that the account is a provider.
     */
    public function provider(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => 'provider',
        ]);
    }

    /**
     * Indicate that the account is a parent.
     */
    public function parent(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => 'parent',
        ]);
    }

    /**
     * Indicate that the account is a school.
     */
    public function school(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => 'school',
        ]);
    }

    /**
     * Indicate that the account is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the account is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_status' => 'pending',
        ]);
    }
}
