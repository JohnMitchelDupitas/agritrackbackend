<template>
  <AdminLayout>
  <div class="weather-admin-page">
    <div class="page-header">
      <h1 class="page-title">Weather in {{ province }}</h1>
    </div>

    <div v-if="!weather" class="empty-state">Loading...</div>

    <div v-else class="content-grid">
      <section class="panel panel-narrow">
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
          </div>
        </div>
      </section>

      <section class="panel panel-wide">
        <header class="panel-header">
          <h2 class="panel-title">Summary</h2>
        </header>
        <div class="panel-body">
          <p class="summary-text">This province overview aggregates current conditions for quick admin review.</p>
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
  </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import { computed } from 'vue'

const props = defineProps<{ weather?: any; province?: string; forecast?: any }>()

const safeForecast = computed(() => {
  const list = props.forecast?.list as any[] | undefined
  return Array.isArray(list) ? list.slice(0, 5) : []
})

function formatVisibility(v?: number) {
  if (typeof v !== 'number') return '—'
  if (v >= 1000) return (v / 1000).toFixed(1) + ' km'
  return v + ' m'
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
.weather-admin-page {
  display: block;
  padding: 16px;
  color: #111827;
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

@media (min-width: 640px) {
  .content-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (min-width: 1024px) {
  .content-grid {
    grid-template-columns: 1fr 2fr;
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

.summary-text {
  margin: 0;
  color: #374151;
  font-size: 14px;
}

.capitalize { text-transform: capitalize; }
</style>
