<template>
  <div>
    <h1>🌤 Weather for My Farm</h1>
    <div v-if="!weather">No farm location set.</div>

    <div v-else>
      <h2>Current Weather</h2>
      <p><b>{{ weather.name }}</b> - {{ weather.weather[0].description }}</p>
      <p>🌡 Temp: {{ weather.main.temp }} °C</p>
      <p>💧 Humidity: {{ weather.main.humidity }} %</p>
      <p>💨 Wind: {{ weather.wind.speed }} m/s</p>

      <h2>Forecast (Next 5 Days)</h2>
      <ul>
        <li v-for="f in forecast.list.slice(0,5)" :key="f.dt">
          {{ new Date(f.dt*1000).toLocaleString() }} - {{ f.main.temp }}°C, {{ f.weather[0].description }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  weather: Object,
  forecast: Object
})
</script>
