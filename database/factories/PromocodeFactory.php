<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromocodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $assignedAT = rand(0,1)? now()->subMinutes(rand(1,1000)) : null;
        return [
            'promocode' => 'GMB'. mb_strtoupper(Str::random(5)),
            'assigned_at' =>$assignedAT,
            'user_agent' =>$assignedAT ? 'some user agent' : null,
            'remote_addr' =>$assignedAT ? 'some ip' : null,
            'bets_count' =>$assignedAT ? rand(0,200) : null,

        ];
    }

}
