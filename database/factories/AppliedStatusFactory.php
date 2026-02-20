<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AppliedStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        static $status = ['Applied', 'Refused', 'Aceppted'];

        $statu = array_shift($status);

        return [
            'status' => $this->faker->word()
        ];
    }
}
