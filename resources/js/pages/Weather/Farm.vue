
<template>
  <AppLayout>
    <div class="weather-page">
      <div class="page-header">
        <h1 class="page-title">Weather for My Farm</h1>
      </div>

      <div v-if="!weather" class="empty-state">No farm location set.</div>

      <div v-else class="content-grid">
        <section class="panel">
          <header class="panel-header">
            <h2 class="panel-title">{{ weather.name }}</h2>
          </header>
          <div class="panel-body">
            <div class="stats-grid">
              <div class="stat">
                <p class="stat-label">Condition</p>
                <p class="stat-value capitalize">{{ weather.weather[0].description }}</p>
              </div>
              <div class="stat">
                <p class="stat-label">Temperature</p>
                <p class="stat-value">{{ weather.main.temp }} °C</p>
              </div>
              <div class="stat">
                <p class="stat-label">Feels Like</p>
                <p class="stat-value">{{ weather.main.feels_like }} °C</p>
              </div>
              <div class="stat">
                <p class="stat-label">Humidity</p>
                <p class="stat-value">{{ weather.main.humidity }} %</p>
              </div>
              <div class="stat">
                <p class="stat-label">Wind</p>
                <p class="stat-value">{{ weather.wind.speed }} m/s</p>
              </div>
              <div class="stat">
                <p class="stat-label">Pressure</p>
                <p class="stat-value">{{ weather.main.pressure }} hPa</p>
              </div>
              <div class="stat">
                <p class="stat-label">Visibility</p>
                <p class="stat-value">{{ formatVisibility(weather.visibility) }}</p>
              </div>
              <div class="stat">
                <p class="stat-label">Cloud Cover</p>
                <p class="stat-value">{{ weather.clouds.all }} %</p>
              </div>
              <div class="stat">
                <p class="stat-label">Rain (1h)</p>
                <p class="stat-value">{{ weather.rain && weather.rain['1h'] ? weather.rain['1h'] + ' mm' : '—' }}</p>
              </div>
              <div class="stat">
                <p class="stat-label">Sunrise</p>
                <p class="stat-value">{{ formatTime(weather.sys?.sunrise) }}</p>
              </div>
              <div class="stat">
                <p class="stat-label">Sunset</p>
                <p class="stat-value">{{ formatTime(weather.sys?.sunset) }}</p>
              </div>
            </div>
          </div>
        </section>

        <section class="panel">
          <header class="panel-header">
            <h2 class="panel-title">Forecast (Next 5)</h2>
          </header>
          <div class="panel-body">
            <ul class="forecast-list">
              <li v-for="f in safeForecast" :key="f.dt" class="forecast-item">
                <div class="forecast-time">{{ new Date(f.dt*1000).toLocaleString() }}</div>
                <div class="forecast-value">{{ f.main.temp }}°C • <span class="capitalize">{{ f.weather[0].description }}</span><span class="forecast-meta"> | Wind {{ f.wind.speed }} m/s • Hum {{ f.main.humidity }}% • POP {{ formatPop(f.pop) }}{{ formatRain(f.rain) }}</span></div>
              </li>
            </ul>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>


<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { computed } from 'vue'

const props = defineProps<{ weather?: any; forecast?: any }>()

const safeForecast = computed(() => {
  const list = props.forecast?.list as any[] | undefined
  return Array.isArray(list) ? list.slice(0, 5) : []
})

function formatVisibility(v?: number) {
  if (typeof v !== 'number') return '—'
  if (v >= 1000) return (v / 1000).toFixed(1) + ' km'
  return v + ' m'
}

function formatTime(unix?: number) {
  if (!unix) return '—'
  try {
    return new Date(unix * 1000).toLocaleTimeString()
  } catch {
    return '—'
  }
}

function formatPop(pop?: number) {
  if (typeof pop !== 'number') return '—'
  return Math.round(pop * 100) + '%'
}

function formatRain(rain?: any) {
  if (!rain) return ''
  const mm = rain['3h'] ?? rain['1h']
  return typeof mm === 'number' ? ` • Rain ${mm} mm` : ''
}
</script>

<style scoped>
.weather-page {
  display: block;
  padding: 16px;
  color: #111827;
  background: transparent;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.page-title {
  margin: 0;
  font-size: 24px;
  font-weight: 600;
  letter-spacing: 0.2px;
}

.empty-state {
  color: #6b7280;
}

.content-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
}

@media (min-width: 768px) {
  .content-grid {
    grid-template-columns: 1fr 1fr;
  }
}

.panel {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
  color: #111827;
}

.panel-header {
  padding: 12px 16px;
  border-bottom: 1px solid #e5e7eb;
}

.panel-title {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  color: #111827;
}

.panel-body {
  padding: 16px;
}

.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.stat-label {
  margin: 0 0 4px 0;
  color: #6b7280;
  font-size: 13px;
}

.stat-value {
  margin: 0;
  font-size: 15px;
  font-weight: 500;
  color: #111827;
}

.forecast-list {
  margin: 0;
  padding: 0;
  list-style: none;
  border-top: 1px solid #f3f4f6;
}

.forecast-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid #f3f4f6;
}

.forecast-time {
  color: #6b7280;
  font-size: 13px;
}

.forecast-value {
  font-size: 14px;
  font-weight: 600;
  color: #111827;
}

.capitalize { text-transform: capitalize; }
 
.forecast-meta {
  font-weight: 500;
  color: #374151;
}
</style>
