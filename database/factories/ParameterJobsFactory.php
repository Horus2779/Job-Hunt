<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ParameterJobsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parameter' => $this->faker->randomElement(['Laravel', 'Developer', 'PHP']),
            'logic_operator' => $this->faker->randomElement(['OR', 'AND']),
        ];
    }
}
