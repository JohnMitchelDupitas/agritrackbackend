<script setup lang="ts">
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100 flex items-center justify-center p-6">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Main content -->
        <div class="relative w-full max-w-md">
            <!-- Auth card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ title }}</h2>
                    <p class="text-gray-600 text-sm">{{ description }}</p>
                </div>
                
                <slot />
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-xs text-gray-500">
                    Empowering farmers with smart technology
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
