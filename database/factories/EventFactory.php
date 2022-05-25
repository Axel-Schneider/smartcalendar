<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\ToDo;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;


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
        if(rand(0, 1)){
            $todo = new ToDo();
            $todo->name = $this->faker->word();
            $todo->save();
        }else {
            $todo = null;
        }

        return [
            'user_id' => User::all()->random()->id,
            'title' => $this->faker->word(),
            'description' => $this->faker->text(255),
            'startDate' => $start,
            'endDate' => $start->clone()->addHours(rand(1, 10)),
            'fullDay' => rand(0, 1),
            'todo_id' => $todo,
            'recurring' => 0,
        ];
    }
}
