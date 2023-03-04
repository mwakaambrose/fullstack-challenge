<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Weather;
use App\Services\WeatherApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FetchWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fullstack-challenge:fetch-weather-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will fetch weather data from the openweathermap 
        API and store it in the database then cache it for 30mins.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (User::all() as $user) {
            WeatherApiService::getUsersCurrentWeather($user);
            WeatherApiService::getUsers5DayWeatherForecast($user);
        }
    }
}
