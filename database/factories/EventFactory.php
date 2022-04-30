<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = Carbon::now()->subDays(rand(1, 10));

        return [
            'user_id' => User::all()->random()->id,
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'startDate' => $start,
            'endDate' => $start->clone()->addHours(rand(1, 10)),
            'fullDay' => rand(0, 1),
            'recurring' => 0,
        ];
    }
}
