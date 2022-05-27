<?php

namespace Database\Factories;

use App\Models\ToDo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => $this->faker->words(3, true),
            'todo_id' => ToDo::all()->random()->id,
            'complete' => rand(0, 1),
        ];
    }
}
