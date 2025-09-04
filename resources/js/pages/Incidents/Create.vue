<template>
    <AppLayout>
        <div class="page-container">
            <div class="card">
                <h1 class="page-title">Log Incident</h1>
                <form @submit.prevent="submit" enctype="multipart/form-data" class="form">
                    <!-- Farm -->
                    <div class="form-group">
                        <label class="label">Farm</label>
                        <select v-model="form.farm_id" required class="select">
                            <option v-for="f in props.farms" :key="f.id" :value="f.id">{{ f.name }}</option>
                        </select>
                    </div>

                    <!-- Crop Cycle -->
                    <div class="form-group">
                        <label class="label">Crop Cycle (optional)</label>
                        <select v-model="form.crop_cycle_id" class="select">
                            <option value="">—</option>
                            <template v-for="f in props.farms" :key="f.id">
                                <option disabled>== {{ f.name }} ==</option>
                                <option v-for="cc in f.crop_cycles" :key="cc.id" :value="cc.id">{{ cc.crop_type }} ({{ cc.season_label }})</option>
                            </template>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label class="label">Type</label>
                        <select v-model="form.type" required class="select">
                            <option value="pest">Pest</option>
                            <option value="disease">Disease</option>
                            <option value="flood">Flood</option>
                            <option value="drought">Drought</option>
                            <option value="typhoon">Typhoon</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Severity -->
                    <div class="form-group">
                        <label class="label">Severity</label>
                        <select v-model="form.severity" class="select">
                            <option value="">—</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <!-- Impact -->
                    <div class="form-group">
                        <label class="label">Impact (%)</label>
                        <input type="number" min="0" max="100" v-model="form.impact_pct" class="input" />
                    </div>

                    <!-- Date -->
                    <div class="form-group">
                        <label class="label">Date Reported</label>
                        <input type="date" v-model="form.date_reported" required class="input" />
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label class="label">Notes</label>
                        <textarea v-model="form.notes" class="textarea" rows="3"></textarea>
                    </div>

                    <!-- Photo -->
                    <div class="form-group">
                        <label class="label">Upload Photo</label>
                        <input type="file" @change="handleFile" accept="image/*" class="input" />
                    </div>

                    <button type="submit" class="btn btn-danger btn-block">Save</button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm } from '@inertiajs/inertia-vue3';

interface CropCycle {
    id: number;
    crop_type: string;
    season_label?: string;
}
interface Farm {
    id: number;
    name: string;
    crop_cycles?: CropCycle[];
}

const props = defineProps<{ farms: Farm[] }>();

const form = useForm({
    farm_id: props.farms?.[0]?.id ?? '',
    crop_cycle_id: '', // <-- default blank
    type: '',
    severity: '',
    impact_pct: '',
    date_reported: '',
    notes: '',
    photo: null as File | null,
});

function handleFile(e: Event) {
    const input = e.target as HTMLInputElement;
    if (input?.files && input.files[0]) {
        form.photo = input.files[0];
    }
}

function submit() {
    form.post('/incidents', {
        forceFormData: true, // IMPORTANT for file upload
        onSuccess: () => {
            window.location.href = '/incidents';
        },
    });
}
</script>

<style scoped>
/* Layout */
.page-container {
    max-width: 640px;
    margin: 0 auto;
    padding: 24px;
}
.page-title {
    font-size: 24px;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 16px;
}

/* Card */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    padding: 24px;
}

/* Form */
.form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.form-group {
    display: block;
}
.label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
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
    color: #9ca3af;
}
.input:focus,
.select:focus,
.textarea:focus {
    outline: none;
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
}
.select {
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    background-color: #fff;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
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
.btn-block {
    width: 100%;
}
.btn-danger {
    background: #dc2626;
    color: #fff;
}
.btn-danger:hover {
    background: #b91c1c;
}
</style>
