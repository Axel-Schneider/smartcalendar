<?php

namespace Database\Factories;

use App\Models\User;
use App\Notifications\ContactAddedNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $requester = User::all()->random();
        $user = null;
        do{
            $user = User::all()->random();
        }while($user->id == $requester->id);
        $status = rand(0, 1) ? 'waiting' : 'accept';
        if($status == 'waiting'){
            $user->notify(new ContactAddedNotification($requester));
        }
        return [
            "userRequest_id" => $requester->id,
            "user_id" => $user->id,
            "status" =>  $status,
        ];
    }
}
