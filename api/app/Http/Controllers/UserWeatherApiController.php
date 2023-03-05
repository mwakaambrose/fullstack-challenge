<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\ApiDecorator;
use Illuminate\Http\Response;
use App\Services\WeatherApiService;

class UserWeatherApiController extends ApiDecorator
{
    public function __construct(private WeatherApiService $weather_api_service)
    {
    }

    /**
     * Display a listing of the users with their current 
     * weather data.
     *
     * @return Response
     */
    public function index()
    {
        try {
            return $this->success(
                data: $this->weather_api_service->fetch_weather_data_for_all_user()
            );
        } catch (\Throwable $th) {
            return $this->error(data: $th->getMessage(), status: ApiDecorator::SERVER_ERROR);
        }
    }

    /**
     * Display the detailed user weather data.
     *
     * @param  \App\Models\User  $user
     * @return Response
     */
    public function show(User $user)
    {
        try {
            return $this->success(
                data: $this->weather_api_service->fetch_weather_data_for_a_user($user),
            );
        } catch (\Throwable $th) {
            return $this->error(data: $th->getMessage(), status: ApiDecorator::SERVER_ERROR);
        }
    }
}
