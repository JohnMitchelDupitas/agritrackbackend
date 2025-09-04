<template>
    <AppLayout>
        <div class="page-container mx-auto max-w-2xl p-6">
            <div class="page-header mb-6 flex items-center justify-between">
                <div>
                    <h1 class="page-title bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-3xl font-bold text-gray-900 text-transparent">
                        Add Crop Cycle
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">Record a new planting or harvesting cycle</p>
                </div>
                <Link
                    href="/crop-cycles"
                    class="btn btn-secondary flex items-center gap-2 rounded-md bg-gray-100 px-4 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-200 hover:text-gray-800"
                >
                    <span>←</span> Back to List
                </Link>
            </div>

            <div class="card rounded-lg bg-white p-6 shadow-sm">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Farm Selection -->
                    <div class="form-group">
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Farm <span class="text-red-500">*</span> </label>
                        <div class="relative">
                            <select
                                v-model="form.farm_id"
                                required
                                class="w-full appearance-none rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-transparent focus:ring-2 focus:ring-green-500 focus:outline-none"
                            >
                                <option value="" disabled class="text-gray-500">Select a farm</option>
                                <option v-for="f in farms" :key="f.id" :value="f.id" class="py-1 text-gray-900">
                                    {{ f.name || 'Farm #' + f.id }} ({{ f.size_ha }} ha)
                                </option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Select the farm where this crop will be planted</p>
                    </div>

                    <!-- Crop Type -->
                    <div class="form-group">
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Crop Type <span class="text-red-500">*</span> </label>
                        <div class="relative">
                            <select
                                v-model="form.crop_type"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-transparent focus:ring-2 focus:ring-green-500 focus:outline-none"
                            >
                                <option value="" disabled class="text-gray-500">Select a crop</option>
                                <option v-for="crop in cropTypes" :key="crop" :value="crop" class="py-1 text-gray-900">
                                    {{ crop }}
                                </option>
                            </select>
                            <div v-if="form.crop_type" class="absolute top-2 right-2 text-green-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Select the type of crop being planted</p>
                    </div>

                    <!-- Variety -->
                    <div class="form-group" v-if="form.crop_type">
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Variety </label>
                        <div class="relative">
                            <select
                                v-model="form.variety"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-transparent focus:ring-2 focus:ring-green-500 focus:outline-none"
                            >
                                <option value="" disabled class="text-gray-500">Select a variety</option>
                                <option v-for="variety in availableVarieties" :key="variety" :value="variety" class="py-1 text-gray-900">
                                    {{ variety }}
                                </option>
                            </select>
                            <div v-if="form.variety" class="absolute top-2 right-2 text-green-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Select the variety of the crop</p>
                    </div>

                    <!-- Season Label (Auto-detected) -->
                    <div class="form-group">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Season <span class="text-xs text-blue-600">(Auto-detected)</span>
                        </label>
                        <div class="relative">
                            <div
                                class="pill-input flex w-full items-center justify-between rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-gray-900"
                            >
                                <span class="font-medium">{{ detectedSeason || 'Select planting date first' }}</span>
                                <div v-if="detectedSeason" class="flex items-center space-x-2">
                                    <span
                                        :class="[
                                            'badge inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                            getSeasonColorClass(detectedSeason),
                                        ]"
                                    >
                                        {{ detectedSeason }}
                                    </span>
                                    <svg class="icon-sm w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Season automatically determined based on Philippine climate patterns</p>
                    </div>

                    <!-- Dates Group -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Date Planted -->
                        <div class="form-group">
                            <label class="mb-2 block text-sm font-medium text-gray-700"> Date Planted <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <input
                                    type="date"
                                    v-model="form.date_planted"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-transparent focus:ring-2 focus:ring-green-500 focus:outline-none"
                                />
                                <div v-if="form.date_planted" class="absolute top-2 right-2 text-green-500">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">When was/will the crop be planted?</p>
                        </div>

                        <!-- Date Harvested -->
                        <div class="form-group">
                            <label class="mb-2 block text-sm font-medium text-gray-700"> Expected Harvest Date </label>
                            <div class="relative">
                                <input
                                    type="date"
                                    v-model="form.date_harvested"
                                    :min="form.date_planted"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-transparent focus:ring-2 focus:ring-green-500 focus:outline-none"
                                />
                                <div v-if="form.date_harvested" class="absolute top-2 right-2 text-yellow-500">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Optional: When do you expect to harvest?</p>
                        </div>
                    </div>

                    <!-- Yield -->
                    <div class="form-group">
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Expected Yield </label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input
                                type="number"
                                step="0.01"
                                v-model="form.yield_kg"
                                @keyup="validateYield"
                                @input="validateYield"
                                placeholder="0.00"
                                :class="[
                                    'w-full rounded-md border px-3 py-2 pr-12 pl-3 text-gray-900 focus:border-transparent focus:ring-2 focus:ring-green-500 focus:outline-none',
                                    yieldValidation.isValid === null
                                        ? 'border-gray-300'
                                        : yieldValidation.isValid
                                          ? 'border-green-300'
                                          : 'border-red-300',
                                ]"
                            />
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 sm:text-sm">kg</span>
                            </div>
                            <!-- Validation Icon -->
                            <div
                                v-if="form.yield_kg && yieldValidation.isValid !== null"
                                class="pointer-events-none absolute inset-y-0 right-8 flex items-center"
                            >
                                <svg
                                    v-if="yieldValidation.isValid"
                                    class="h-5 w-5 text-green-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <svg v-else class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                        <!-- Validation Message -->
                        <div v-if="form.yield_kg && yieldValidation.message" class="mt-1">
                            <p :class="['text-xs', yieldValidation.isValid ? 'text-green-600' : 'text-red-600']">
                                {{ yieldValidation.message }}
                            </p>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Optional: Estimated yield in kilograms (must be positive and reasonable)</p>
                    </div>

                    <!-- Error Messages -->
                    <div v-if="errors.length > 0" class="mb-4 border-l-4 border-red-500 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please correct the following:</h3>
                                <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                                    <li v-for="(msg, index) in errors" :key="index">{{ msg }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="actions flex items-center justify-end space-x-3">
                        <button
                            type="button"
                            @click="resetForm"
                            class="btn btn-ghost px-4 py-2 text-sm text-gray-700 hover:text-gray-900 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                        >
                            Reset
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary flex items-center space-x-2 rounded-md bg-green-600 px-6 py-2 text-white transition-colors hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            <span>{{ form.processing ? 'Saving...' : 'Save Crop Cycle' }}</span>
                            <svg
                                v-if="form.processing"
                                class="spinner h-4 w-4 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="spinner-track opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path
                                    class="spinner-indicator opacity-75"
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
import { computed, ref, watch } from 'vue';

interface Farm {
    id: number;
    name: string;
    size_ha: number;
}

// Add crop varieties and dropdown logic
interface CropVarieties {
    [key: string]: string[];
}

const cropVarietiesMap: CropVarieties = {
    Corn: [
        'Visayan White',
        'Lagkitan',
        'NK8840',
        'NK306',
        'NK6410',
        'NK6414',
        'DEKALB 9118S',
        'DK 9051',
        '30B80',
        'NT 8860',
        'IES Glut #2',
        'IES Glut #3',
        'IES Glut #4',
        'IES Glut #5',
    ],
    Rice: [
        'NSIC Rc 216',
        'Rc 160',
        'Rc 300',
        'Rc 222',
        'Tubigan 44',
        'Salinas 24',
        'Salinas 25',
        'Salinas 26',
        'Salinas 27',
        'Salinas 28',
        'Salinas 29',
        'Salinas 30',
        'Salinas 31',
        'Salinas 32',
        'Salinas 33',
        'Salinas 34',
        'Salinas 35',
        'Salinas 36',
        'Salinas 37',
        'Salinas 38',
    ],
    Mungbean: ['Pagasa 9', 'Pagasa 11', 'Pagasa 13', 'Pagasa 15', 'Pagasa 17', 'Pagasa 19', 'Pagasa 21', 'Mg14', 'Mg15'],
    Tobacco: ['Native', 'Virginia', 'Burley'],
    Banana: ['Señorita', 'Latundan', 'Lakatan'],
    Mango: [
        'Carabao',
        'Pico',
        'Katchamitha',
        'Mangoming',
        'Farrales',
        'Carotene',
        'Kyla Luz',
        'Tommy Atkins',
        'IPB Carabao 1',
        'Pahutan',
        'Paho',
        'Wani',
        'Baluno',
    ],
};

const cropTypes = Object.keys(cropVarietiesMap);

const props = defineProps<{
    farms: Farm[];
}>();

const form = useForm({
    farm_id: props.farms?.[0]?.id ?? null,
    crop_type: '',
    variety: '',
    season_label: '',
    date_planted: '',
    date_harvested: '',
    yield_kg: '',
});

const errors = ref<string[]>([]);

const availableVarieties = computed(() => (form.crop_type ? cropVarietiesMap[form.crop_type] : []));

// Auto-detect season based on Philippine climate patterns
const detectedSeason = computed(() => {
    if (!form.date_planted) return null;

    const plantingDate = new Date(form.date_planted);
    const month = plantingDate.getMonth() + 1; // getMonth() returns 0-11

    // Philippine Season Mapping:
    // Dry Season (Tag-init / Tag-araw) → December 1 to April 30 (months 12, 1, 2, 3, 4)
    // Wet Season (Tag-ulan) → May 1 to September 30 (months 5, 6, 7, 8, 9)
    // Transition / Early Dry Season → October 1 to November 30 (months 10, 11)

    if (month >= 12 || month <= 4) {
        return 'Dry Season (Tag-init)';
    } else if (month >= 5 && month <= 9) {
        return 'Wet Season (Tag-ulan)';
    } else {
        return 'Transition Season';
    }
});

// Get color class for season badges
function getSeasonColorClass(season: string): string {
    if (season.includes('Dry Season')) {
        return 'badge-dry';
    } else if (season.includes('Wet Season')) {
        return 'badge-wet';
    } else {
        return 'badge-transition';
    }
}

interface YieldValidation {
    isValid: boolean | null;
    message: string;
}

const yieldValidation = ref<YieldValidation>({
    isValid: null,
    message: '',
});

function validateYield() {
    const value = parseFloat(form.yield_kg);

    // Clear validation if no value
    if (!form.yield_kg) {
        yieldValidation.value = {
            isValid: null,
            message: '',
        };
        return;
    }

    // Check if it's a valid number
    if (isNaN(value)) {
        yieldValidation.value = {
            isValid: false,
            message: 'Please enter a valid number.',
        };
        return;
    }

    // Check if it's positive
    if (value <= 0) {
        yieldValidation.value = {
            isValid: false,
            message: 'Yield must be greater than 0.',
        };
        return;
    }

    // Check if it's reasonable (not too high - assuming max 100,000 kg per hectare)
    // Get selected farm size for context
    const selectedFarm = props.farms.find((f) => f.id === form.farm_id);
    const farmSize = selectedFarm?.size_ha || 1;
    const maxReasonableYield = farmSize * 100000; // 100,000 kg/ha is very high but possible

    if (value > maxReasonableYield) {
        yieldValidation.value = {
            isValid: false,
            message: `Yield seems too high for a ${farmSize} hectare farm. Maximum reasonable yield: ${maxReasonableYield.toLocaleString()} kg.`,
        };
        return;
    }

    // Check if it's too low (less than 100 kg total)
    if (value < 100) {
        yieldValidation.value = {
            isValid: false,
            message: 'Yield seems too low. Minimum reasonable yield: 100 kg.',
        };
        return;
    }

    // All validations passed
    yieldValidation.value = {
        isValid: true,
        message: `✓ Valid yield for ${farmSize} hectare farm`,
    };
}

// Watch for farm changes to re-validate yield
watch(
    () => form.farm_id,
    () => {
        if (form.yield_kg) {
            validateYield();
        }
    },
);

// Watch for planting date changes to auto-update season
watch(
    () => form.date_planted,
    (newDate) => {
        if (newDate && detectedSeason.value) {
            form.season_label = detectedSeason.value;
        }
    },
);

function resetForm() {
    form.reset();
    errors.value = [];
    yieldValidation.value = {
        isValid: null,
        message: '',
    };
}

function submit() {
    // Validate yield before submission
    if (form.yield_kg && !yieldValidation.value.isValid) {
        errors.value = ['Please fix the yield validation errors before submitting.'];
        return;
    }

    // Auto-set season label based on detected season
    if (detectedSeason.value) {
        form.season_label = detectedSeason.value;
    }

    form.post('/crop-cycles', {
        onError: (errs: Errors) => {
            errors.value = Object.values(errs).flat();
        },
        onSuccess: () => {
            window.location.href = '/crop-cycles';
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
    align-items: center;
    justify-content: space-between;
}
.page-title {
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
}

/* Pill input */
.pill-input {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
}

/* Badges */
.badge {
    border: 1px solid transparent;
    font-weight: 700;
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
    border-radius: 8px;
    border: 1px solid transparent;
    cursor: pointer;
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
}
.btn-ghost:hover {
    color: #111827;
    box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.25);
}
.actions {
    gap: 12px;
}

/* Spinner */
.spinner {
    animation: spin 1s linear infinite;
}
.spinner-track {
    opacity: 0.25;
}
.spinner-indicator {
    opacity: 0.75;
}
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
