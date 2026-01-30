<?php

namespace Database\Factories;

use App\Models\ApiSources;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AppliedJobsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'location' => $this->faker->city(),
            'url' => $this->faker->url(),
            'status' => $this->faker->numberBetween(1, 3),
            'source_id' => ApiSources::inRandomOrder()->first()->id ?? ApiSources::factory(),
        ];
    }
}
