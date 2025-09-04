<template>
  <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-300 recommendation-card">
    <!-- Header -->
    <div class="flex items-start justify-between mb-2">
      <div class="flex items-center space-x-2 min-w-0 flex-1">
        <div>
          <h3 class="text-sm font-semibold text-gray-900 truncate">{{ recommendation.category }}</h3>
          <p class="text-xs text-gray-600">{{ getSourceLabel(recommendation.source) }}</p>
        </div>
      </div>
      <div class="flex items-center space-x-1 flex-shrink-0">
        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
          {{ recommendation.status }}
        </span>
        <button 
          @click="$emit('dismiss', recommendation.id)"
          class="text-gray-400 hover:text-gray-600 transition-colors p-0.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Content -->
    <div class="mb-2 content">
      <p class="text-xs text-gray-700 leading-relaxed">
        <span v-if="recommendation.farm" class="block mb-1">
          <span class="font-semibold text-blue-700">Farm:</span>
          <span class="font-semibold ml-1">{{ recommendation.farm.name }}</span>
        </span>
        <span v-if="!showFull && recommendation.message.length > 80">
          {{ recommendation.message.slice(0, 80) }}...
          <button @click="showFull = true" class="text-blue-600 text-xs ml-1 underline">More</button>
        </span>
        <span v-else>
          {{ recommendation.message }}
          <button v-if="showFull" @click="showFull = false" class="text-blue-600 text-xs ml-1 underline">Less</button>
        </span>
      </p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between actions">
      <div class="flex items-center space-x-2">
        <button 
          @click="$emit('markSeen', recommendation.id)"
          class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-3 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500 transition-colors"
        >
          Seen
        </button>
        <button 
          @click="$emit('markDone', recommendation.id)"
          class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-3 font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-green-500 transition-colors"
        >
          Done
        </button>
      </div>
      <div class="text-xs text-gray-500">
        {{ formatDate(recommendation.created_at) }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
const showFull = ref(false);

interface Farm {
  id: number;
  name: string;
}


interface Recommendation {
  id: number;
  category: string;
  message: string;
  source: 'rule' | 'ml';
  status: 'new' | 'seen' | 'done';
  created_at: string;
  farm?: Farm;
}

defineProps<{
  recommendation: Recommendation;
}>();

defineEmits<{
  dismiss: [id: number];
  markSeen: [id: number];
  markDone: [id: number];
}>();

function getSourceLabel(source: string): string {
  return source === 'ml' ? 'AI Recommendation' : 'System Alert';
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}
</script>

<style scoped>
.recommendation-card {
  min-height: 140px;
  display: flex;
  flex-direction: column;
}

.recommendation-card .content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.recommendation-card .actions {
  margin-top: auto;
}
</style>
