<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Weather;
use App\Services\WeatherApiService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WeatherApiServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_getUsersCurrentWeather_fetch_user_weather_data_and_saves_it()
    {
        $user = User::factory()->create();
        WeatherApiService::getUsersCurrentWeather($user);
        $this->assertDatabaseHas('weather', [
            'user_id' => $user->id,
            'type' => 'current',
        ]);
    }

    public function test_getUsers5DayWeatherForecast_fetch_user_weather_data_and_saves_it()
    {
        $user = User::factory()->create();
        WeatherApiService::getUsers5DayWeatherForecast($user);
        $this->assertDatabaseHas('weather', [
            'user_id' => $user->id,
            'type' => 'forecast',
        ]);
    }
}
