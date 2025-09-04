<script setup lang="ts">
import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const showPassword = ref(false);
</script>

<template>
    <AuthBase title="Welcome back" description="Sign in to your AgriTrack account">
        <Head title="Log in" />

        <!-- Status Message -->
        <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
            <p class="text-sm font-medium text-green-800">{{ status }}</p>
        </div>

        <Form
            v-bind="AuthenticatedSessionController.store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="space-y-6"
        >
            <!-- Email Field -->
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
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="Enter your email address"
                        class="pl-10 h-12 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition-colors bg-white text-gray-900"
                    />
                </div>
                <InputError :message="errors.email" />
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-sm font-medium text-gray-700">Password</Label>
                    <TextLink v-if="canResetPassword" :href="request()" class="text-sm text-green-600 hover:text-green-700 font-medium" :tabindex="5">
                        Forgot password?
                    </TextLink>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Lock class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="pl-10 pr-12 h-12 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition-colors bg-white text-gray-900"
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

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                    <Checkbox id="remember" name="remember" :tabindex="3" class="text-green-600" />
                    <span class="text-sm text-gray-700">Remember me</span>
                </Label>
            </div>

            <!-- Submit Button -->
            <Button 
                type="submit" 
                class="w-full h-12 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" 
                :tabindex="4" 
                :disabled="processing"
            >
                <LoaderCircle v-if="processing" class="h-5 w-5 animate-spin mr-2" />
                {{ processing ? 'Signing In...' : 'Sign In' }}
            </Button>

            <!-- Register Link -->
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <TextLink :href="register()" class="text-green-600 hover:text-green-700 font-medium underline underline-offset-2" :tabindex="5">
                        Create one here
                    </TextLink>
                </p>
            </div>
        </Form>
    </AuthBase>
</template>
