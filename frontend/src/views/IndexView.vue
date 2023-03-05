<template>
  <main>
    <div class="flex flex-col items-center justify-center w-screen min-h-screen text-white p-10 bg-gray-900">
      <h1 class="text-4xl font-bold">Weather App</h1>
      <div v-for="user in users" :key="user.id"
        class="flex flex-col space-y-6 w-full max-w-screen-md bg-gray-700 p-10 mt-10 rounded-xl ring-8 ring-white ring-opacity-40">
        <RouterLink :to="{ name: 'detail', params: { 'id': user.id } }">
          <div class="flex justify-between items-center">
            <span class="font-semibold text-lg w-1/4">{{ user.name }}</span>
            <div class="flex items-center w-1/4 pr-10">
              <span class="font-semibold">
                {{ JSON.parse(user.weather.weather)[0].description.capitalize() }}
              </span>
            </div>
            <WeatherIcon :weather="JSON.parse(user.weather.weather)[0].main" />
            <span class="font-semibold text-lg w-1/4 text-right">
              {{ JSON.parse(user.weather.main).temp_min }}° / {{ JSON.parse(user.weather.main).temp_max }}°
            </span>
          </div>
        </RouterLink>
      </div>
      <h5 class="mt-10 text-sm">&copy Mwaka Ambrose - Fullstack Challenge</h5>
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
      users: [],
    };
  },
  methods: {
    getWeather() {
      fetch("http://localhost/api/v1/users")
        .then((response) => response.json())
        .then((data) => {
          this.users = data.data;
        });
    },
  },
  mounted() {
    this.getWeather();
  },
}
</script>
