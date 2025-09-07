<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">🌍 Admin Map of Farms & Incidents</h1>
    <div id="map" style="height: 600px; width: 100%; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);"></div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';

const props = defineProps({
  farms: Array,
  incidents: Array,
  googleMapsApiKey: String
});

function loadGoogleMapsScript(callback: () => void) {
  if (window.google && window.google.maps) {
    callback();
    return;
  }
  if (document.getElementById('google-maps-script')) {
    // Script is already loading
    window.initMap = callback;
    return;
  }
  const script = document.createElement("script");
  script.id = "google-maps-script";
  script.src = `https://maps.googleapis.com/maps/api/js?key=${props.googleMapsApiKey}&callback=initMap`;
  script.async = true;
  window.initMap = callback;
  document.head.appendChild(script);
}

onMounted(() => {
  loadGoogleMapsScript(() => {
    const map = new window.google.maps.Map(document.getElementById("map"), {
      zoom: 8,
      center: { lat: 16.7, lng: 121.7 }
    });

    props.farms.forEach(farm => {
      if (farm.gps_lat && farm.gps_long) {
        new window.google.maps.Marker({
          position: { lat: parseFloat(farm.gps_lat), lng: parseFloat(farm.gps_long) },
          map,
          title: `Farm: ${farm.name}${farm.user ? ' (Farmer: ' + farm.user.name + ')' : ''}`,
          icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
        });
      }
    });

    props.incidents.forEach(incident => {
      if (incident.gps_lat && incident.gps_long) {
        new window.google.maps.Marker({
          position: { lat: parseFloat(incident.gps_lat), lng: parseFloat(incident.gps_long) },
          map,
          title: `Incident: ${incident.type} (${incident.severity})${incident.farm ? ' at Farm ' + incident.farm.name : ''}`,
          icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        });
      }
    });
  });
});
</script>

<style scoped>
#map {
  margin-bottom: 24px;
}
</style>
