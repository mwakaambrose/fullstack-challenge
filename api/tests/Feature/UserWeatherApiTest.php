<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Weather;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserWeatherApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_fetch_users_with_their_current_weather_data()
    {
        User::factory(count: 2)->create();
        Weather::factory(count: 20)->create();
        $this->get(uri: "/api/v1/users")
            ->assertStatus(200)
            ->assertJsonStructure(structure: [
                'meta' => [
                    'status',
                    'message',
                    'version',
                ],
                'data'
            ])
            ->assertJson([
                'meta' => [
                    'status' => 200,
                    'message' => 'success',
                    'version' => 'v1',
                ],
                "data" => User::with("weather")->get()->toArray()
            ]);
    }

    public function test_can_fetch_users_with_their_weather_data_and_weather_forecast_data()
    {
        User::factory(count: 2)->create();
        Weather::factory(count: 20)->create();
        $user = User::inRandomOrder()->first();
        $weather_forecast = $user->weather()->whereType(Weather::TYPES[1])->get();
        
        $this->get(uri: "/api/v1/users/{$user->id}")
            ->assertStatus(200)
            ->assertJsonStructure(structure: [
                'meta' => [
                    'status',
                    'message',
                    'version',
                ],
                'data'
            ])
            ->assertJson([
                'meta' => [
                    'status' => 200,
                    'message' => 'success',
                    'version' => 'v1',
                ],
                "data" => [
                    "user" => $user->toArray(),
                    "weather" => [
                        "forecasts" => $weather_forecast->toArray(),
                    ],
                ]
            ]);
    }
}
