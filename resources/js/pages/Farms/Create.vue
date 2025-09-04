<template>
    <AppLayout>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Add Farm & Field</h1>
            <Link href="/farms" class="btn btn-secondary btn-icon">← Back to Farms</Link>
        </div>

        <div class="card">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="form-group">
                    <label class="label">Farm Name <span class="req">*</span></label>
                    <input
                        v-model="form.name"
                        @input="validateName"
                        @keyup="validateName"
                        type="text"
                        :class="['input', nameError ? 'is-invalid' : '']"
                        placeholder="Enter farm name"
                        required
                    />
                    <p v-if="nameError" class="validation text-invalid">{{ nameError }}</p>
                </div>

                <div class="form-group">
                    <label class="label">Size (ha)</label>
                    <input
                        v-model="form.size_ha"
                        @input="validateSize"
                        @keyup="validateSize"
                        type="number"
                        step="0.01"
                        min="0"
                        :class="['input', sizeError ? 'is-invalid' : '']"
                        placeholder="0.00"
                    />
                    <p v-if="sizeError" class="validation text-invalid">{{ sizeError }}</p>
                </div>

                <div class="form-group">
                    <label class="label">Soil Type</label>
                    <select v-model="form.soil_type" class="select">
                        <option value="" disabled class="option-muted">Select soil type</option>
                        <option value="Clay Loam" class="option">Clay Loam</option>
                        <option value="Sandy Loam" class="option">Sandy Loam</option>
                        <option value="Silty Loam" class="option">Silty Loam</option>
                        <option value="Loam" class="option">Loam</option>
                        <option value="Clay" class="option">Clay</option>
                        <option value="Sandy Soil" class="option">Sandy Soil</option>
                        <option value="Volcanic Soil (Andosol)" class="option">Volcanic Soil (Andosol)</option>
                        <option value="Peat Soil" class="option">Peat Soil</option>
                        <option value="Calcareous Soil" class="option">Calcareous Soil (Limestone-rich)</option>
                        <option value="Alluvial Soil" class="option">Alluvial Soil</option>
                    </select>
                </div>

                <div class="grid-2 grid">
                    <div class="form-group">
                        <label class="label">Latitude</label>
                        <input
                            v-model="form.gps_lat"
                            @input="validateLatitude"
                            @keyup="validateLatitude"
                            type="number"
                            step="0.000001"
                            :class="['input', latError ? 'is-invalid' : '']"
                            placeholder="0.000000"
                        />
                        <p v-if="latError" class="validation text-invalid">{{ latError }}</p>
                    </div>

                    <div class="form-group">
                        <label class="label">Longitude</label>
                        <input
                            v-model="form.gps_long"
                            @input="validateLongitude"
                            @keyup="validateLongitude"
                            type="number"
                            step="0.000001"
                            :class="['input', longError ? 'is-invalid' : '']"
                            placeholder="0.000000"
                        />
                        <p v-if="longError" class="validation text-invalid">{{ longError }}</p>
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
                    <textarea
                        v-model="form.boundary_geojson"
                        @input="validateGeoJSON"
                        @keyup="validateGeoJSON"
                        :class="['textarea', geojsonError ? 'is-invalid' : '']"
                        rows="3"
                        placeholder="Paste GeoJSON boundary data here..."
                    ></textarea>
                    <p v-if="geojsonError" class="validation text-invalid">{{ geojsonError }}</p>
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
                    <button type="button" @click="resetForm" class="btn btn-ghost">Reset</button>
                    <button type="submit" class="btn btn-primary btn-icon" :disabled="form.processing">
                        <span>{{ form.processing ? 'Saving...' : 'Save Farm' }}</span>
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

const form = useForm({
    name: '',
    size_ha: '',
    soil_type: '',
    gps_lat: '',
    gps_long: '',
    irrigation: '',
    boundary_geojson: '',
});

const errors = ref<string[]>([]);
const nameError = ref<string>('');
const sizeError = ref<string>('');
const latError = ref<string>('');
const longError = ref<string>('');
const geojsonError = ref<string>('');

function validateName() {
    if (!form.name.trim()) {
        nameError.value = 'Farm name field is required.';
    } else {
        nameError.value = '';
    }
}

function validateSize() {
    const size = parseFloat(form.size_ha);
    if (isNaN(size) || size < 0.1) {
        sizeError.value = 'Farm size must be at least 0.1.';
    } else {
        sizeError.value = '';
    }
}

function validateLatitude() {
    const lat = parseFloat(form.gps_lat);
    if (form.gps_lat && (isNaN(lat) || lat < -90 || lat > 90)) {
        latError.value = 'Latitude must be between -90 and 90 degrees.';
    } else {
        latError.value = '';
    }
}

function validateLongitude() {
    const long = parseFloat(form.gps_long);
    if (form.gps_long && (isNaN(long) || long < -180 || long > 180)) {
        longError.value = 'Longitude must be between -180 and 180 degrees.';
    } else {
        longError.value = '';
    }
}

function validateGeoJSON() {
    if (!form.boundary_geojson.trim()) {
        // Empty GeoJSON is valid (optional field)
        geojsonError.value = '';
        return;
    }

    try {
        const geojson = JSON.parse(form.boundary_geojson);

        // Basic GeoJSON validation
        if (!geojson.type || !geojson.features) {
            geojsonError.value = 'Invalid GeoJSON format. Must include type and features.';
            return;
        }

        if (geojson.type !== 'FeatureCollection') {
            geojsonError.value = 'GeoJSON must be a FeatureCollection.';
            return;
        }

        if (!Array.isArray(geojson.features) || geojson.features.length === 0) {
            geojsonError.value = 'GeoJSON must contain at least one feature.';
            return;
        }

        // Validate each feature
        for (let i = 0; i < geojson.features.length; i++) {
            const feature = geojson.features[i];
            if (!feature.type || feature.type !== 'Feature') {
                geojsonError.value = `Feature ${i + 1} must have type 'Feature'.`;
                return;
            }

            if (!feature.geometry || !feature.geometry.type) {
                geojsonError.value = `Feature ${i + 1} must have valid geometry.`;
                return;
            }

            // Check for valid geometry types
            const validTypes = ['Polygon', 'MultiPolygon', 'Point', 'LineString', 'MultiPoint', 'MultiLineString'];
            if (!validTypes.includes(feature.geometry.type)) {
                geojsonError.value = `Feature ${i + 1} has invalid geometry type: ${feature.geometry.type}`;
                return;
            }
        }

        geojsonError.value = '';
    } catch {
        geojsonError.value = 'Invalid JSON format. Please check your GeoJSON syntax.';
    }
}

function resetForm() {
    form.reset();
    errors.value = [];
    nameError.value = '';
    sizeError.value = '';
    latError.value = '';
    longError.value = '';
    geojsonError.value = '';
}

function submit() {
    // Validate all fields before submitting
    validateName();
    validateSize();
    validateLatitude();
    validateLongitude();
    validateGeoJSON();

    // Only submit if no validation errors
    if (!nameError.value && !sizeError.value && !latError.value && !longError.value && !geojsonError.value) {
        form.post('/farms', {
            onError: (errs: Errors) => {
                errors.value = Object.values(errs).flat();
            },
            onSuccess: () => {
                errors.value = [];
                form.reset();
                nameError.value = '';
                sizeError.value = '';
                latError.value = '';
                longError.value = '';
                geojsonError.value = '';
            },
        });
    }
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
.form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Labels */
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

/* Inputs */
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
.is-invalid {
    border-color: #fca5a5 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25) !important;
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

/* Validation */
.validation {
    margin-top: 6px;
    font-size: 12px;
}
.text-invalid {
    color: #dc2626;
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
