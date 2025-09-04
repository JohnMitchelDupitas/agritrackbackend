<script setup lang="ts">
import NewPasswordController from '@/actions/App/Http/Controllers/Auth/NewPasswordController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, Shield, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
</script>

<template>
    <AuthLayout title="Set new password" description="Enter your new password below">
        <Head title="Reset password" />

        <Form
            v-bind="NewPasswordController.store.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="space-y-6"
        >
            <!-- Email Field (Read-only) -->
            <div class="space-y-2">
                <Label for="email" class="text-sm font-medium text-gray-700">Email Address</Label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Mail class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input 
                        id="email" 
                        type="email" 
                        name="email" 
                        autocomplete="email" 
                        v-model="inputEmail" 
                        class="pl-10 h-12 border-gray-300 bg-gray-50 text-gray-600 rounded-lg text-gray-900" 
                        readonly 
                    />
                </div>
                <InputError :message="errors.email" />
            </div>

            <!-- New Password Field -->
            <div class="space-y-2">
                <Label for="password" class="text-sm font-medium text-gray-700">New Password</Label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Lock class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        autocomplete="new-password"
                        class="pl-10 pr-12 h-12 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition-colors bg-white text-gray-900"
                        autofocus
                        placeholder="Enter your new password"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <Eye v-if="!showPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="errors.password" />
            </div>

            <!-- Confirm Password Field -->
            <div class="space-y-2">
                <Label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm New Password</Label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Shield class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input
                        id="password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        name="password_confirmation"
                        autocomplete="new-password"
                        class="pl-10 pr-12 h-12 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition-colors bg-white text-gray-900"
                        placeholder="Confirm your new password"
                    />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <Eye v-if="!showConfirmPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <Button 
                type="submit" 
                class="w-full h-12 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" 
                :disabled="processing"
            >
                <LoaderCircle v-if="processing" class="h-5 w-5 animate-spin mr-2" />
                {{ processing ? 'Updating Password...' : 'Update Password' }}
            </Button>
        </Form>
    </AuthLayout>
</template>
