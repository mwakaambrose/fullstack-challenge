<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Weather;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Weather>
 */
class WeatherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {        
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(array: Weather::TYPES),
            'weather' => '[{"id": 500, "icon": "10d", "main": "Rain", "description": "light rain"}]',
            'main' => '{"temp": 83.12, "humidity": 79, "pressure": 1009, "temp_max": 83.12, "temp_min": 83.12, "sea_level": 1009, "feels_like": 91.29, "grnd_level": 1009}',
            'wind' => '{"deg": 153, "gust": 12.37, "speed": 11.79}',
            'rain' => '{"1h": 0.25}',
            'clouds' => '{"all": 93}',
        ];
    }
}
