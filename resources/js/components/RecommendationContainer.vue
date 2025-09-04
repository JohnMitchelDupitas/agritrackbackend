<template>
  <div v-if="recommendations && recommendations.length > 0" class="mb-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center space-x-3">
        <!-- <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div> -->
        <div>
          <h2 class="text-xl font-semibold text-gray-900">Smart Recommendations</h2>
          <p class="text-sm text-gray-600">Rule-based insights to optimize your farming</p>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
          {{ recommendations.length }} {{ recommendations.length === 1 ? 'recommendation' : 'recommendations' }}
        </span>
        <button 
          @click="dismissAll"
          class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
        >
          Dismiss All
        </button>
      </div>
    </div>

    <!-- Recommendations Grid -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 overflow-x-auto pb-2">
      <RecommendationCard
        v-for="recommendation in recommendations"
        :key="recommendation.id"
        :recommendation="recommendation"
        @dismiss="handleDismiss"
        @mark-seen="handleMarkSeen"
        @mark-done="handleMarkDone"
      />
    </div>

    <!-- Empty State (shouldn't show if we have recommendations) -->
    <div v-if="recommendations.length === 0" class="text-center py-8">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">All Caught Up!</h3>
      <p class="text-gray-600">No new recommendations at the moment. Keep up the great work!</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import RecommendationCard from './RecommendationCard.vue';
import { Inertia } from '@inertiajs/inertia';



interface Recommendation {
  id: number;
  category: string;
  message: string;
  source: 'rule' | 'ml';
  status: 'new' | 'seen' | 'done';
  created_at: string;
  
}

defineProps<{
  recommendations: Recommendation[];
}>();

function handleDismiss(id: number) {
  Inertia.put(`/recommendations/${id}/dismiss`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      // Optionally show a success message
    }
  });
}

function handleMarkSeen(id: number) {
  Inertia.put(`/recommendations/${id}/mark-seen`, {}, {
    preserveScroll: true
  });
}

function handleMarkDone(id: number) {
  Inertia.put(`/recommendations/${id}/mark-done`, {}, {
    preserveScroll: true
  });
}

function dismissAll() {
  if (confirm('Are you sure you want to dismiss all recommendations?')) {
    Inertia.put('/recommendations/dismiss-all', {}, {
      preserveScroll: true
    });
  }
}
</script>
