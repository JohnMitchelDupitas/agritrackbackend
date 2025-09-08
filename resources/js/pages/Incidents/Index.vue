<template>
    <AppLayout>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Incidents</h1>
            <a href="/incidents/create" class="btn btn-danger btn-icon">➕ Log Incident</a>
        </div>

        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Farm</th>
                        <th>Crop Cycle</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Impact (%)</th>
                        <th>Notes</th>
                        <th>Photo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="incident in incidents.data" :key="incident.id" class="row">
                        <td class="cell nowrap">{{ formatDate(incident.created_at) }}</td>
                        <td class="cell nowrap">{{ incident.farm?.name ?? '—' }}</td>
                        <td class="cell nowrap">
                            <span v-if="incident.crop_cycle">
                                {{ incident.crop_cycle.crop_type
                                }}<span v-if="incident.crop_cycle.season_label"> ({{ incident.crop_cycle.season_label }})</span>
                            </span>
                            <span v-else>—</span>
                        </td>
                        <td class="cell nowrap">
                            <span class="badge" :class="getTypeClass(incident.type)">{{ incident.type }}</span>
                        </td>
                        <td class="cell nowrap">
                            <span class="badge" :class="getSeverityClass(incident.severity)">{{ incident.severity ?? '—' }}</span>
                        </td>
                        <td class="cell nowrap">{{ incident.impact_pct ?? '—' }}</td>
                        <td class="cell nowrap max-w-xs truncate" :title="incident.notes">{{ incident.notes ?? '—' }}</td>
                        <td class="cell nowrap">
                            <img v-if="incident.photo_path" :src="`/storage/${incident.photo_path}`" alt="Incident Photo" class="thumb" />
                            <span v-else class="text-muted">—</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { computed } from 'vue';
const props = defineProps<{ incidents: { data: any[] } }>();
const incidents = computed(() => props.incidents);

function formatDate(date: string) {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function getTypeClass(type: string) {
    const map: Record<string, string> = {
        pest: 'badge-type-pest',
        disease: 'badge-type-disease',
        flood: 'badge-type-flood',
        drought: 'badge-type-drought',
        typhoon: 'badge-type-typhoon',
        other: 'badge-neutral',
    };
    return map[type] || 'badge-neutral';
}

function getSeverityClass(severity: string) {
    const map: Record<string, string> = {
        low: 'badge-sev-low',
        medium: 'badge-sev-medium',
        high: 'badge-sev-high',
    };
    return map[severity] || 'badge-neutral';
}
</script>

<style scoped>
/* Layout */
.page-container {
    max-width: 960px;
    margin: 0 auto;
    padding: 24px;
}
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.page-title {
    font-size: 24px;
    font-weight: 800;
    color: #1f2937;
}

/* Card & Table */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.06),
        0 1px 2px rgba(0, 0, 0, 0.04);
    overflow-x: auto;
}
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}
.data-table thead tr {
    background: #f9fafb;
}
.data-table thead th {
    text-align: left;
    padding: 12px 16px;
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
    padding: 12px 16px;
    color: #111827;
    vertical-align: middle;
}
.nowrap {
    white-space: nowrap;
}
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.max-w-xs {
    max-width: 16rem;
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
.badge-neutral {
    background: #f3f4f6;
    color: #374151;
    border-color: #e5e7eb;
}
.badge-type-pest {
    background: #fef9c3;
    color: #92400e;
    border-color: #fde68a;
}
.badge-type-disease {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.badge-type-flood {
    background: #dbeafe;
    color: #1e3a8a;
    border-color: #bfdbfe;
}
.badge-type-drought {
    background: #ffedd5;
    color: #9a3412;
    border-color: #fed7aa;
}
.badge-type-typhoon {
    background: #e0e7ff;
    color: #3730a3;
    border-color: #c7d2fe;
}
.badge-sev-low {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.badge-sev-medium {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}
.badge-sev-high {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fecaca;
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
.btn-icon {
    padding: 10px 16px;
}
.btn-danger {
    background: #dc2626;
    color: #fff;
}
.btn-danger:hover {
    background: #b91c1c;
}

/* Images */
.thumb {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}
.text-muted {
    color: #9ca3af;
}
</style>
