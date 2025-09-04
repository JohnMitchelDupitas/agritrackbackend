<script setup lang="ts">
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Mail, ArrowLeft } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout title="Reset your password" description="Enter your email to receive a password reset link">
        <Head title="Forgot password" />

        <!-- Status Message -->
        <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
            <p class="text-sm font-medium text-green-800">{{ status }}</p>
        </div>

        <div class="space-y-6">
            <Form v-bind="PasswordResetLinkController.store.form()" v-slot="{ errors, processing }">
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
                            autocomplete="email" 
                            autofocus 
                            placeholder="Enter your email address"
                            class="pl-10 h-12 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition-colors bg-white text-gray-900"
                        />
                    </div>
                    <InputError :message="errors.email" />
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <Button 
                        class="w-full h-12 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" 
                        :disabled="processing"
                    >
                        <LoaderCircle v-if="processing" class="h-5 w-5 animate-spin mr-2" />
                        {{ processing ? 'Sending Reset Link...' : 'Send Reset Link' }}
                    </Button>
                </div>
            </Form>

            <!-- Back to Login -->
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    Remember your password?
                    <TextLink :href="login()" class="text-green-600 hover:text-green-700 font-medium underline underline-offset-2">
                        Sign in here
                    </TextLink>
                </p>
            </div>
        </div>
    </AuthLayout>
</template>
