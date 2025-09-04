<template>
    <AppLayout>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Edit Farm & Field</h1>
            <Link href="/farms" class="btn btn-secondary btn-icon">← Back to Farms</Link>
        </div>

        <div class="card">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="form-group">
                    <label class="label">Farm Name <span class="req">*</span></label>
                    <input v-model="form.name" type="text" class="input" placeholder="Enter farm name" required />
                </div>

                <div class="form-group">
                    <label class="label">Size (ha)</label>
                    <input v-model="form.size_ha" type="number" step="0.01" min="0" class="input" placeholder="0.00" />
                </div>

                <div class="form-group">
                    <label class="label">Soil Type</label>
                    <input v-model="form.soil_type" type="text" class="input" placeholder="e.g., Clay, Loam, Sandy" />
                </div>

                <div class="grid-2 grid">
                    <div class="form-group">
                        <label class="label">Latitude</label>
                        <input v-model="form.gps_lat" type="number" step="0.000001" class="input" placeholder="0.000000" />
                    </div>

                    <div class="form-group">
                        <label class="label">Longitude</label>
                        <input v-model="form.gps_long" type="number" step="0.000001" class="input" placeholder="0.000000" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Irrigation</label>
                    <select v-model="form.irrigation" class="select">
                        <option value="" disabled class="option-muted">Select irrigation type</option>
                        <option value="rainfed" class="option">Rainfed</option>
                        <option value="irrigated" class="option">Irrigated</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="label">Boundary GeoJSON (optional)</label>
                    <textarea v-model="form.boundary_geojson" class="textarea" rows="3" placeholder="Paste GeoJSON boundary data here..."></textarea>
                </div>

                <!-- Error Messages -->
                <div v-if="errors.length > 0" class="alert alert-error">
                    <div class="alert-row">
                        <div class="alert-icon">
                            <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="alert-body">
                            <h3 class="alert-title">Please correct the following:</h3>
                            <ul class="alert-list">
                                <li v-for="(err, index) in errors" :key="index">{{ err }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="actions">
                    <Link href="/farms" class="btn btn-ghost">Cancel</Link>
                    <button type="submit" class="btn btn-primary btn-icon" :disabled="form.processing">
                        <span>{{ form.processing ? 'Updating...' : 'Update Farm' }}</span>
                        <svg v-if="form.processing" class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="spinner-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path
                                class="spinner-indicator"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { Errors } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps<{ farm: any }>();

const form = useForm({
    name: props.farm.name ?? '',
    size_ha: props.farm.size_ha ?? '',
    soil_type: props.farm.soil_type ?? '',
    gps_lat: props.farm.lat ?? '',
    gps_long: props.farm.lng ?? '',
    irrigation: props.farm.irrigation ?? '',
    boundary_geojson: props.farm.boundary_geojson ?? '',
});

const errors = ref<string[]>([]);

function submit() {
    form.put(`/farms/${props.farm.id}`, {
        onError: (errs: Errors) => {
            errors.value = Object.values(errs).flat();
        },
        onSuccess: () => {
            errors.value = [];
        },
    });
}
</script>

<style scoped>
/* Layout */
.page-container {
    max-width: 768px;
    margin: 0 auto;
    padding: 24px;
}
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
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

/* Card */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.06),
        0 1px 2px rgba(0, 0, 0, 0.04);
    padding: 24px;
}

/* Form */
.form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}
.req {
    color: #ef4444;
}
.input,
.select,
.textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #111827;
    background: #fff;
    transition:
        box-shadow 120ms ease,
        border-color 120ms ease;
}
.input::placeholder,
.textarea::placeholder {
    color: #6b7280;
}
.input:focus,
.select:focus,
.textarea:focus {
    outline: none;
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35);
}
.select {
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    background-color: #fff;
}
.option {
    color: #111827;
}
.option-muted {
    color: #9ca3af;
}

/* Grid */
.grid {
    display: grid;
    gap: 24px;
}
.grid-2 {
    grid-template-columns: 1fr;
}
@media (min-width: 768px) {
    .grid-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Alerts */
.alert {
    padding: 12px 16px;
    border-left: 4px solid;
    margin-bottom: 16px;
    border-radius: 8px;
}
.alert-error {
    background: #fef2f2;
    border-color: #ef4444;
}
.alert-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.alert-icon {
    flex-shrink: 0;
    color: #f87171;
}
.alert-body {
    margin-left: 0;
}
.alert-title {
    font-size: 14px;
    font-weight: 700;
    color: #991b1b;
}
.alert-list {
    margin-top: 8px;
    padding-left: 18px;
    list-style: disc;
    font-size: 14px;
    color: #b91c1c;
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
}
.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.btn-icon {
    padding: 10px 14px;
}
.btn-primary {
    background: #16a34a;
    color: #fff;
}
.btn-primary:hover {
    background: #15803d;
}
.btn-secondary {
    background: #f3f4f6;
    color: #4b5563;
}
.btn-secondary:hover {
    background: #e5e7eb;
    color: #1f2937;
}
.btn-ghost {
    background: transparent;
    color: #374151;
    border-color: transparent;
    padding: 8px 12px;
    text-decoration: none;
}
.btn-ghost:hover {
    color: #111827;
    box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.25);
}
.actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
}

/* Spinner */
.spinner {
    width: 16px;
    height: 16px;
}
.spinner-track {
    opacity: 0.25;
}
.spinner-indicator {
    opacity: 0.75;
    animation: spin 1s linear infinite;
    transform-origin: 12px 12px;
}
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
