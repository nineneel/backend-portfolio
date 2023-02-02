<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'service_id' => rand(1, 20),
            'project_name' => fake()->words(3, true),
            'slug' => fake()->slug(),
            'agency' => fake()->words(2, true),
            'url' => fake()->url(),
            'overview' => fake()->paragraphs(3, true),
            'development_date' => fake()->dateTimeBetween('-2 years', '-1 years'),
        ];
    }
}
