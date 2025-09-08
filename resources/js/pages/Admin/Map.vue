<template>
  <AdminLayout>
    <div>
      <h1>🌍 Admin Map (Leaflet)</h1>
      <div id="map" style="height: 800px; width: 100%;"></div>
    </div>
  </AdminLayout>
  
</template>

<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import { onMounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'


// Props passed from controller
interface FarmUser { name: string }
interface Farm { lat?: number; lng?: number; name: string; user: FarmUser }
interface IncidentFarm { name: string }
interface Incident { gps_lat?: number; gps_lng?: number; type: string; severity: string; farm: IncidentFarm }

const props = withDefaults(defineProps<{ farms?: Farm[]; incidents?: Incident[] }>(), {
  farms: () => [],
  incidents: () => []
})

onMounted(() => {
  // Initialize map
  const map = L.map('map').setView([16.7, 121.7], 7) // Center on Isabela

  // Add OSM tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map)

  // Farm markers
  props.farms.forEach(farm => {
    if (farm.lat && farm.lng) {
      L.marker([farm.lat, farm.lng], {
        icon: L.icon({
          iconUrl: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -30]
        })
      })
        .addTo(map)
        .bindPopup(`<b>Farm:</b> ${farm.name}<br><b>Farmer:</b> ${farm.user.name}`)
    }
  })

  // Incident markers
  props.incidents.forEach(incident => {
    if (incident.gps_lat && incident.gps_lng) {
      L.marker([incident.gps_lat, incident.gps_lng], {
        icon: L.icon({
          iconUrl: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -30]
        })
      })
        .addTo(map)
        .bindPopup(`<b>Incident:</b> ${incident.type}<br><b>Severity:</b> ${incident.severity}<br><b>Farm:</b> ${incident.farm.name}`)
    }
  })
})
</script>

<style>
#map {
  border: 2px solid #ccc;
  border-radius: 8px;
}
</style>
