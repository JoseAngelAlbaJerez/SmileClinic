<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'DNI' => fake()->numerify('#########'),

            'specialty' => fake()->randomElement(['Endodontist','Orthodontist','Periodontist','Prosthodontist','Oral and Maxillofacial Surgeon','Oral and Maxillofacial Radiologist','Dentist Anesthesiologists','Oral and Maxillofacial Pathologist','Pediatric Dentist or Pedodontist'] ),
            'phone_number' => fake()->phoneNumber(),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'position' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'active' => true,
            'branch_id' => Branch::inRandomOrder()->first()->id ?? 1,
            'avatar' => null,

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
