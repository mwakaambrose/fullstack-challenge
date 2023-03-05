<template>
  <main>
    <div class="flex flex-col items-center justify-center w-screen min-h-screen text-white p-10 bg-gray-900">
      <h1 class="text-4xl font-bold mb-8">Weather App</h1>
      <!-- Component Start -->
      <div class="w-full max-w-screen-md bg-gray-700 p-10 rounded-xl ring-8 ring-white ring-opacity-40">
        <div class="flex justify-between">
          <div class="flex flex-col">
            <span class="text-6xl font-bold">
              {{ JSON.parse(weather.current.main).temp }}°C
            </span>
            <span class="font-semibold mt-3 text-gray-500" v-if="!isLoading">
              {{ JSON.parse(weather.current.weather)[0].description.capitalize() }}
            </span>
          </div>
          <WeatherIcon 
            size="h-24 w-24"
            :weather="JSON.parse(weather.current.weather)[0].main" 
            v-if="!isLoading"/>
        </div>
        <div class="flex justify-between mt-12">
          <p>
            {{ user.name }}
            {{ user.email }}
          </p>
          <p>{{ user.longitude }}, {{ user.latitude }}</p>
        </div>
      </div>
      <div
        class="flex flex-col space-y-6 w-full max-w-screen-md bg-gray-700 p-10 mt-10 rounded-xl ring-8 ring-white ring-opacity-40">
        <div v-for="forecast in weather.forecasts" :key="forecast.id" class="flex justify-between items-center">
          <span class="font-semibold w-2/4">
            {{ prepDate(forecast.datetime_txt) }} - {{ forecast.datetime_txt.split(" ")[1] }}
          </span>
          <div class="flex items-center w-1/4 pr-10">
            <span class="font-semibold overflow-hidden">
              {{ JSON.parse(forecast.weather)[0].description.capitalize() }}
            </span>
          </div>
          <WeatherIcon :weather="JSON.parse(forecast.weather)[0].main" />
          <span class="font-semibold w-1/4 text-right">
            {{ JSON.parse(forecast.main).temp_min }}° / {{ JSON.parse(forecast.main).temp_max }}°
          </span>
        </div>
      </div>
      <h5 class="mt-10 text-sm">&copy Mwaka Ambrose - Fullstack Challenge</h5>
      <!-- Component End  -->
    </div>
  </main>
</template>
<script lang="ts">
import WeatherIcon from "@/components/WeatherIcon.vue";
export default {
  components: {
    WeatherIcon,
  },
  data() {
    return {
      user: {},
      weather: {
        current: {
          main: "{}",
          weather: "[{'description': 'loading...'}]",
        },
      },
      user_id: this.$route.params.id,
      isLoading: true,
    };
  },
  methods: {
    getWeather() {
      this.isLoading = true;
      fetch(`http://localhost/api/v1/users/${this.user_id}`)
        .then((response) => response.json())
        .then((data) => {
          this.user = data.data.user;
          this.weather = data.data.weather;
          this.isLoading = false;
        });
    },
    prepDate(date: string) {
      const dateObj = new Date(date);
      const options  = { year: "numeric", month: "long", day: "numeric" };
      return dateObj.toLocaleDateString("en-US", options);
    },
  },
  created() {
    this.getWeather();
  },
}
</script>