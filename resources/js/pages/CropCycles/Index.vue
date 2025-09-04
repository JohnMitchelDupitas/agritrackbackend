<template>
    <AppLayout>
    <div class="page-container">
        <!-- Professional Recommendations Section -->
        <RecommendationContainer :recommendations="recommendations || []" />
        <!-- Header Section -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Crop Cycles</h1>
                <p class="page-subtitle">Track and manage your farm's planting and harvesting cycles</p>
            </div>
            <div class="header-right">
                <Link href="/crop-cycles/create" class="btn btn-primary btn-icon">
                    <svg class="icon-sm mr-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Crop Cycle
                </Link>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Farm</th>
                            <th scope="col">Crop</th>
                            <th scope="col">Timeline</th>
                            <th scope="col">Status</th>
                            <th scope="col">Yield</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cycle in cycles.data" :key="cycle.id" class="row">
                            <td class="cell nowrap">
                                <div class="cell-title">{{ cycle.farm.name || `Farm #${cycle.farm_id}` }}</div>
                                <div class="cell-subtitle">{{ cycle.farm.size_ha }} hectares</div>
                            </td>
                            <td class="cell nowrap">
                                <span class="badge" :class="getCropTypeClass(cycle.crop_type)">
                                    {{ cycle.crop_type }}
                                </span>
                                <div v-if="cycle.variety" class="cell-subtitle mt-6">
                                    {{ cycle.variety }}
                                </div>
                            </td>
                            <td class="cell nowrap timeline">
                                <div class="timeline-col">
                                    <div class="timeline-row">
                                        <svg class="icon-xs icon-green mr-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                        {{ formatDate(cycle.date_planted) }}
                                    </div>
                                    <div v-if="cycle.date_harvested" class="timeline-row muted">
                                        <svg class="icon-xs icon-yellow mr-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"
                                            />
                                        </svg>
                                        {{ formatDate(cycle.date_harvested) }}
                                    </div>
                                    <!-- Season Label -->
                                    <div v-if="cycle.season_label" class="timeline-row tiny muted">
                                        <svg class="icon-xxs icon-blue mr-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                            />
                                        </svg>
                                        <span class="badge" :class="getSeasonColorClass(cycle.season_label)">
                                            {{ cycle.season_label }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="cell nowrap">
                                <span class="badge" :class="getStatusClass(cycle)">
                                    {{ getStatusText(cycle) }}
                                </span>
                            </td>
                            <td class="cell nowrap">
                                <div v-if="cycle.yield_kg" class="cell-title">{{ formatNumber(cycle.yield_kg) }} kg</div>
                                <div v-else class="cell-subtitle">Not yet harvested</div>
                            </td>
                        </tr>
                        <!-- Empty State -->
                        <tr v-if="cycles.data.length === 0">
                            <td colspan="5" class="empty-cell">
                                <div class="empty">
                                    <svg class="icon-lg icon-muted" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6m6 6v6m0-6h6m-6 0H6m6 6v6m0-6h6m-6 0H6m6 6v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                    <p class="empty-text">No crop cycles recorded yet</p>
                                    <inertia-link href="/crop-cycles/create" class="empty-link">
                                        Add your first crop cycle
                                        <svg class="icon-sm ml-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </inertia-link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <!-- <div class="mt-4 flex items-center justify-between rounded-lg border-t border-gray-200 bg-white px-4 py-3 shadow-sm sm:px-6">
            <div class="flex flex-1 justify-between sm:hidden">
                <button
                    v-if="cycles.prev_page_url"
                    @click="go(cycles.prev_page_url)"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Previous
                </button>
                <button
                    v-if="cycles.next_page_url"
                    @click="go(cycles.next_page_url)"
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Next
                </button>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ cycles.data.length }}</span> results - Page
                        <span class="font-medium">{{ cycles.current_page }}</span> of
                        <span class="font-medium">{{ cycles.last_page }}</span>
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <button
                            v-if="cycles.prev_page_url"
                            @click="go(cycles.prev_page_url)"
                            class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 transition-colors hover:bg-gray-50"
                        >
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                        <button
                            v-if="cycles.next_page_url"
                            @click="go(cycles.next_page_url)"
                            class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 transition-colors hover:bg-gray-50"
                        >
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div> -->
    </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import RecommendationContainer from '@/components/RecommendationContainer.vue';
import { Link } from '@inertiajs/inertia-vue3';

interface Farm {
    id: number;
    name: string;
    size_ha: number;
}

interface CropCycle {
    id: number;
    farm_id: number;
    farm: Farm;
    crop_type: string;
    variety?: string;
    season_label?: string;
    date_planted: string;
    date_harvested: string | null;
    yield_kg: number | null;
    status?: 'planned' | 'planted' | 'harvested';
}

interface Recommendation {
    id: number;
    category: string;
    message: string;
    source: 'rule' | 'ml';
    status: 'new' | 'seen' | 'done';
    created_at: string;
}

interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

defineProps<{
    cycles: PaginatedData<CropCycle>;
    recommendations?: Recommendation[];
}>();

function formatDate(dateString: string): string {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function formatNumber(num: number): string {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(num);
}

function getCropTypeClass(cropType: string): string {
    const classes = {
        Rice: 'badge-crop-rice',
        Corn: 'badge-crop-corn',
        Vegetables: 'badge-crop-veggies',
    } as const;
    return classes[cropType as keyof typeof classes] || 'badge-neutral';
}

function getStatusClass(cycle: CropCycle): string {
    if (cycle.date_harvested) {
        return 'badge-success';
    }
    const plantedDate = new Date(cycle.date_planted);
    const today = new Date();
    if (plantedDate > today) {
        return 'badge-warning';
    }
    return 'badge-info';
}

function getStatusText(cycle: CropCycle): string {
    if (cycle.date_harvested) {
        return 'Harvested';
    }
    const plantedDate = new Date(cycle.date_planted);
    const today = new Date();
    if (plantedDate > today) {
        return 'Planned';
    }
    return 'Growing';
}

function getSeasonColorClass(season: string): string {
    if (season.includes('Dry Season')) {
        return 'badge-dry';
    } else if (season.includes('Wet Season')) {
        return 'badge-wet';
    } else {
        return 'badge-transition';
    }
}
</script>

<style scoped>
/* Layout */
.page-container {
    max-width: 1120px;
    margin: 0 auto;
    padding: 24px;
}
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
}
.header-left {
    min-width: 0;
    flex: 1;
}
.header-right {
    margin-top: 0;
    margin-left: 0;
}
.page-title {
    font-size: 28px;
    font-weight: 800;
    line-height: 1.2;
    background: linear-gradient(90deg, #16a34a, #166534);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
.page-subtitle {
    margin-top: 4px;
    font-size: 13px;
    color: #6b7280;
}

/* Card */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.06),
        0 1px 2px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}
.table-wrap {
    width: 100%;
    overflow-x: auto;
}

/* Table */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.data-table thead tr {
    background: #f9fafb;
}
.data-table thead th {
    text-align: left;
    padding: 12px 24px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #6b7280;
    font-weight: 700;
    border-bottom: 1px solid #e5e7eb;
}
.data-table tbody tr {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 120ms ease;
}
.data-table tbody tr:hover {
    background: #f8fafc;
}
.cell {
    padding: 16px 24px;
    font-size: 14px;
    color: #111827;
    vertical-align: middle;
}
.nowrap {
    white-space: nowrap;
}
.cell-title {
    font-weight: 600;
    color: #111827;
}
.cell-subtitle {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

/* Timeline */
.timeline-col {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.timeline-row {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #111827;
}
.timeline-row.muted {
    color: #6b7280;
}
.timeline-row.tiny {
    font-size: 12px;
}

/* Icons */
.icon-xxs {
    width: 12px;
    height: 12px;
}
.icon-xs {
    width: 16px;
    height: 16px;
}
.icon-sm {
    width: 20px;
    height: 20px;
}
.icon-lg {
    width: 48px;
    height: 48px;
}
.mr-6 {
    margin-right: 6px;
}
.ml-6 {
    margin-left: 6px;
}
.icon-green {
    color: #10b981;
}
.icon-yellow {
    color: #f59e0b;
}
.icon-blue {
    color: #3b82f6;
}
.icon-muted {
    color: #9ca3af;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
}
.badge-neutral {
    background: #f3f4f6;
    color: #374151;
    border-color: #e5e7eb;
}
.badge-success {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.badge-warning {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}
.badge-info {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}
.badge-crop-rice {
    background: #fef9c3;
    color: #854d0e;
    border-color: #fde68a;
}
.badge-crop-corn {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.badge-crop-veggies {
    background: #e0f2fe;
    color: #075985;
    border-color: #bae6fd;
}
.badge-dry {
    background: #ffedd5;
    color: #7c2d12;
    border-color: #fed7aa;
}
.badge-wet {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}
.badge-transition {
    background: #fef9c3;
    color: #854d0e;
    border-color: #fde68a;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    border: 1px solid transparent;
    cursor: pointer;
    transition:
        background-color 150ms ease,
        color 150ms ease,
        box-shadow 150ms ease;
    font-size: 14px;
    padding: 10px 14px;
}
.btn-icon {
    padding: 10px 16px;
}
.btn-primary {
    background: #16a34a;
    color: #fff;
}
.btn-primary:hover {
    background: #15803d;
}

/* Empty state */
.empty-cell {
    padding: 40px 24px;
    text-align: center;
}
.empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
}
.empty-text {
    font-size: 14px;
    color: #6b7280;
}
.empty-link {
    display: inline-flex;
    align-items: center;
    font-size: 14px;
    color: #16a34a;
    text-decoration: none;
}
.empty-link:hover {
    color: #22c55e;
}

@media (min-width: 640px) {
    .page-header {
        gap: 16px;
    }
}
</style>
