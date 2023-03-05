<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Weather;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherApiService
{
    /**
     * Get the weather data for a user from the openweathermap API
     * and save it to the database.
     *
     * @param  User  $user  The user to get the weather data for
     */
    public static function getUsersCurrentWeather(User $user)
    {
        $api_key = config("openweathermap.api_key");
        $base_url = config("openweathermap.base_url");
        $data = Http::get(url: $base_url . "/weather", query: [
            "lat" => $user->latitude,
            "lon" => $user->longitude,
            "appid" => $api_key,
            "units" => "metric",
        ])->json();

        if ($data["cod"] != 200) {
            throw new Exception($data["message"]);
        }

        DB::beginTransaction();
        try {
            Weather::whereUserId($user->id)
                ->whereType(Weather::TYPES[0])
                ->delete();
            $weather = new Weather([
                "type" => Weather::TYPES[0],
                "weather" => json_encode(isset($data["weather"]) ? $data["weather"] : []),
                "main" => json_encode(isset($data["main"]) ? $data["main"] : []),
                "wind" => json_encode(isset($data["wind"]) ? $data["wind"] : []),
                "rain" => json_encode(isset($data["rain"]) ? $data["rain"] :  []),
                "clouds" => json_encode(isset($data["clouds"]) ? $data["clouds"] : []),
            ]);
            $weather->user()->associate($user);
            $weather->save();
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error($throwable->getMessage());
        }
    }

    /**
     * Get weather forecast data for a user from the openweathermap API
     * and save it to the database.
     *
     * @param  User  $user  The user to get the weather data for
     */
    public static function getUsers5DayWeatherForecast(User $user)
    {
        $api_key = config("openweathermap.api_key");
        $base_url = config("openweathermap.base_url");
        $data = Http::get(url: $base_url . "/forecast", query: [
            "lat" => $user->latitude,
            "lon" => $user->longitude,
            "appid" => $api_key,
            "units" => "metric",
            "cnt" => "5"
        ])->json();
        
        if ($data["cod"] != 200) {
            throw new Exception($data["message"]);
        }

        DB::beginTransaction();
        try {
            Weather::whereUserId($user->id)
                ->whereType(Weather::TYPES[1])
                ->delete();
            foreach ($data["list"] as $weather_data) {
                $weather = new Weather([
                    "type" => Weather::TYPES[1],
                    "weather" => json_encode($weather_data["weather"]),
                    "main" => json_encode($weather_data["main"]),
                    "wind" => json_encode($weather_data["wind"]),
                    "rain" => json_encode(isset($weather_data["rain"]) ?? []),
                    "clouds" => json_encode($weather_data["clouds"]),
                    "datetime_txt" => $weather_data["dt_txt"],
                ]);
                $weather->user()->associate($user);
                $weather->save();
            }
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error($throwable->getMessage());
        }
    }

    /**
     * Get the weather data for all users from the cache if it exists
     * and if it doesn't exist, fetch it from the database and cache it
     *
     * @return  array  An array containing the current weather 
     * and the weather forecast
     */
    public function fetch_weather_data_for_all_user()
    {
        return Cache::get(
            key: Weather::CACHE_KEY . "-" . "all",
            default: function () {
                return User::with("weather")->get();
            }
        );
    }

    /**
     * Get the weather data for a user from the cache if it exists
     * and if it doesn't exist, fetch it from the database and cache it
     *
     * @param   User  $user
     *
     * @return  array  An array containing the current weather 
     * and the weather forecast
     */
    public function fetch_weather_data_for_a_user(User $user)
    {
        return Cache::get(
            key: Weather::CACHE_KEY . "-" . $user->id,
            default: function () use ($user) {
                $current_weather = $user->weather()->whereType(Weather::TYPES[0])->first();
                $weather_forecast = $user->weather()->whereType(Weather::TYPES[1])->get();
                return [
                    "user" => $user,
                    "weather" => [
                        "current" => $current_weather,
                        "forecasts" => $weather_forecast,
                    ],
                ];
            }
        );
    }
}
