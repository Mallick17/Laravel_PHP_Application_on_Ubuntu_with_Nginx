<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'priority' => $this->faker->randomElement(['low','high','medium']),
            'status' => $this->faker->randomElement(['in-progress','completed']),
            'completion_percentage' => $this->faker->randomFloat(2,0,100),
            'user_id'=>User::all()->random()->id,//selecting random id from the user model

        ];
    }
}
