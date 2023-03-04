<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\WeatherApiService;

class UserWeatherApiController extends Controller
{
    public function __construct(private WeatherApiService $weather_api_service)
    {
    }

    public function index()
    {
        $users_weather_data = $this->weather_api_service
            ->fetch_weather_data_for_all_user();

        return response()->json(data: $users_weather_data);
    }

    public function show(Request $request, User $user)
    {
        $user_weather_data = $this->weather_api_service
            ->fetch_weather_data_for_a_user($user);

        return response()->json(data: $user_weather_data);
    }
}
