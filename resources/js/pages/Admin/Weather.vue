<template>
  <div>
    <h1>🌍 Weather in {{ province }}</h1>
    <div v-if="!weather">Loading...</div>
    <div v-else>
      <p>{{ weather.name }} - {{ weather.weather[0].description }}</p>
      <p>🌡 Temp: {{ weather.main.temp }} °C</p>
      <p>💧 Humidity: {{ weather.main.humidity }} %</p>
      <p>💨 Wind: {{ weather.wind.speed }} m/s</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  weather: Object,
  province: String
})
</script>
