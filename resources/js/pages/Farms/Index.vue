<template>
    <AppLayout>
    <div class="page-container">
        <!-- Professional Recommendations Section -->
        <RecommendationContainer :recommendations="recommendations || []" />

        <!-- Header Section -->
        <div class="page-header-block">
            <!-- User Greeting -->
            <div class="user-greet" v-if="user">
                <h2 class="user-name">Hello, {{ user?.name || 'Farmer' }}!</h2>
                <p class="user-sub">Welcome to your farm management dashboard</p>
            </div>

            <!-- Title and Add Button -->
            <div class="page-header">
                <h1 class="page-title">My Farms</h1>
                <Link href="/farms/create" class="btn btn-primary btn-icon">➕ Add Farm</Link>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size (ha)</th>
                        <th>Soil Type</th>
                        <th>Irrigation</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="farm in farms.data" :key="farm.id" class="row">
                        <td class="cell nowrap">
                            <div class="cell-title">{{ farm.name }}</div>
                        </td>
                        <td class="cell nowrap">
                            <div class="cell-text">{{ farm.size_ha }}</div>
                        </td>
                        <td class="cell nowrap">
                            <div class="cell-text">{{ farm.soil_type }}</div>
                        </td>
                        <td class="cell nowrap">
                            <span class="badge" :class="farm.irrigation === 'irrigated' ? 'badge-info' : 'badge-warning'">
                                {{ farm.irrigation }}
                            </span>
                        </td>
                        <td class="cell nowrap">
                            <div class="cell-text">{{ farm.lat }}, {{ farm.lng }}</div>
                        </td>
                        <td class="cell nowrap">
                            <div class="actions-row">
                                <Link :href="`/farms/${farm.id}/edit`" class="link link-primary">Edit</Link>
                                <button @click="destroy(farm.id)" class="link link-danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <!-- Empty State -->
                    <tr v-if="farms.data.length === 0">
                        <td colspan="6" class="empty-cell">
                            <div class="empty">
                                <svg class="icon-lg icon-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <p class="empty-text">No farms recorded yet</p>
                                <Link href="/farms/create" class="empty-link">
                                    Add your first farm
                                    <svg class="icon-sm ml-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="farms.links"
            class="mt-4 flex items-center justify-between rounded-lg border-t border-gray-200 bg-white px-4 py-3 shadow-sm sm:px-6"
        >
            <span v-for="link in farms.links" :key="link.label">
                <a v-if="link.url" :href="link.url" v-html="link.label" class="px-2 text-gray-700 hover:text-gray-900"></a>
                <span v-else v-html="link.label" class="px-2 text-gray-500"></span>
            </span>
        </div>
    </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import RecommendationContainer from '@/components/RecommendationContainer.vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/inertia-vue3';
import { defineProps } from 'vue';

interface Farm {
    id: number;
    name: string;
    size_ha: number;
    soil_type: string;
    irrigation: string;
    lat: number;
    lng: number;
}

interface Recommendation {
    id: number;
    category: string;
    message: string;
    source: 'rule' | 'ml';
    status: 'new' | 'seen' | 'done';
    created_at: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface PaginatedData<T> {
    data: T[];
    links: { label: string; url: string | null }[];
}

defineProps<{
    farms: PaginatedData<Farm>;
    user: User;
    recommendations?: Recommendation[];
}>();

function destroy(id: number) {
    if (confirm('Are you sure you want to delete this farm?')) {
        Inertia.delete(`/farms/${id}`);
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
.page-header-block {
    margin-bottom: 24px;
}
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
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
.user-greet {
    margin-bottom: 12px;
}
.user-name {
    font-size: 20px;
    font-weight: 700;
    color: #dababa;
}
.user-sub {
    margin-top: 4px;
    font-size: 13px;
    color: #9ba6bb;
}

/* Card & Table */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.06),
        0 1px 2px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}
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
.cell-text {
    color: #111827;
}

/* Actions */
.actions-row {
    display: flex;
    gap: 8px;
}
.link {
    font-size: 14px;
    text-decoration: none;
}
.link-primary {
    color: #2563eb;
}
.link-primary:hover {
    color: #1d4ed8;
    text-decoration: underline;
}
.link-danger {
    color: #dc2626;
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
}
.link-danger:hover {
    color: #b91c1c;
    text-decoration: underline;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
}
.badge-info {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}
.badge-warning {
    background: #fef3c7;
    color: #92400e;
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
    text-decoration: none;
}
.btn-primary {
    background: #16a34a;
    color: #fff;
}
.btn-primary:hover {
    background: #15803d;
}
.btn-icon {
    padding: 10px 16px;
}

/* Empty */
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
.icon-lg {
    width: 48px;
    height: 48px;
}
.icon-sm {
    width: 20px;
    height: 20px;
}
.ml-6 {
    margin-left: 6px;
}
.icon-muted {
    color: #9ca3af;
}
</style>
