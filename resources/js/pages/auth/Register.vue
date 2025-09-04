<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, User, Mail, Lock, Shield } from 'lucide-vue-next';
import { reactive } from 'vue';

// Validation state
const validation = reactive({
    name: { isValid: null as boolean | null, message: '' },
    email: { isValid: null as boolean | null, message: '' },
    password: { isValid: null as boolean | null, message: '' },
    password_confirmation: { isValid: null as boolean | null, message: '' }
});

// Validation functions
function validateName(value: string) {
    if (!value) {
        validation.name = { isValid: null, message: '' };
        return;
    }
    if (value.length < 2) {
        validation.name = { isValid: false, message: 'Name must be at least 2 characters long' };
        return;
    }
    if (value.length > 50) {
        validation.name = { isValid: false, message: 'Name must be less than 50 characters' };
        return;
    }
    if (!/^[a-zA-Z\s]+$/.test(value)) {
        validation.name = { isValid: false, message: 'Name can only contain letters and spaces' };
        return;
    }
    validation.name = { isValid: true, message: '✓ Valid name' };
}

function validateEmail(value: string) {
    if (!value) {
        validation.email = { isValid: null, message: '' };
        return;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        validation.email = { isValid: false, message: 'Please enter a valid email address' };
        return;
    }
    validation.email = { isValid: true, message: '✓ Valid email' };
}

function validatePassword(value: string) {
    if (!value) {
        validation.password = { isValid: null, message: '' };
        return;
    }
    if (value.length < 8) {
        validation.password = { isValid: false, message: 'Password must be at least 8 characters long' };
        return;
    }
    if (!/(?=.*[a-z])/.test(value)) {
        validation.password = { isValid: false, message: 'Password must contain at least one lowercase letter' };
        return;
    }
    if (!/(?=.*[A-Z])/.test(value)) {
        validation.password = { isValid: false, message: 'Password must contain at least one uppercase letter' };
        return;
    }
    if (!/(?=.*\d)/.test(value)) {
        validation.password = { isValid: false, message: 'Password must contain at least one number' };
        return;
    }
    validation.password = { isValid: true, message: '✓ Strong password' };
}

function validatePasswordConfirmation(value: string, password: string) {
    if (!value) {
        validation.password_confirmation = { isValid: null, message: '' };
        return;
    }
    if (value !== password) {
        validation.password_confirmation = { isValid: false, message: 'Passwords do not match' };
        return;
    }
    validation.password_confirmation = { isValid: true, message: '✓ Passwords match' };
}
</script>

<template>
    <AuthBase title="Create an account" description="Join AgriTrack to manage your farm smarter">
        <Head title="Register" />

        <Form
            v-bind="RegisteredUserController.store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="space-y-6"
        >
            <!-- Name Field -->
            <div class="space-y-2">
                <Label for="name" class="text-sm font-medium text-gray-700">Full Name</Label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <User class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input 
                        id="name" 
                        type="text" 
                        required 
                        autofocus 
                        :tabindex="1" 
                        autocomplete="name" 
                        name="name" 
                        placeholder="Enter your full name"
                        @keyup="validateName($event.target.value)"
                        @input="validateName($event.target.value)"
                        :class="[
                            'pl-10 h-12 border rounded-lg transition-colors bg-white text-gray-900',
                            validation.name.isValid === null ? 'border-gray-300 focus:border-green-500 focus:ring-green-500' : 
                            validation.name.isValid ? 'border-green-300 focus:border-green-500 focus:ring-green-500' : 'border-red-300 focus:border-red-500 focus:ring-red-500'
                        ]"
                    />
                </div>
                <!-- Validation Message -->
                <div v-if="validation.name.message" class="text-xs" :class="validation.name.isValid ? 'text-green-600' : 'text-red-600'">
                    {{ validation.name.message }}
                </div>
                <InputError :message="errors.name" />
            </div>

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
                        required 
                        :tabindex="2" 
                        autocomplete="email" 
                        name="email" 
                        placeholder="Enter your email address"
                        @keyup="validateEmail($event.target.value)"
                        @input="validateEmail($event.target.value)"
                        :class="[
                            'pl-10 h-12 border rounded-lg transition-colors bg-white text-gray-900',
                            validation.email.isValid === null ? 'border-gray-300 focus:border-green-500 focus:ring-green-500' : 
                            validation.email.isValid ? 'border-green-300 focus:border-green-500 focus:ring-green-500' : 'border-red-300 focus:border-green-500 focus:ring-red-500'
                        ]"
                    />
                </div>
                <!-- Validation Message -->
                <div v-if="validation.email.message" class="text-xs" :class="validation.email.isValid ? 'text-green-600' : 'text-red-600'">
                    {{ validation.email.message }}
                </div>
                <InputError :message="errors.email" />
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <Label for="password" class="text-sm font-medium text-gray-700">Password</Label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Lock class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input 
                        id="password" 
                        type="password" 
                        required 
                        :tabindex="3" 
                        autocomplete="new-password" 
                        name="password" 
                        placeholder="Create a strong password"
                        @keyup="validatePassword($event.target.value)"
                        @input="validatePassword($event.target.value)"
                        :class="[
                            'pl-10 h-12 border rounded-lg transition-colors bg-white text-gray-900',
                            validation.password.isValid === null ? 'border-gray-300 focus:border-green-500 focus:ring-green-500' : 
                            validation.password.isValid ? 'border-green-300 focus:border-green-500 focus:ring-green-500' : 'border-red-300 focus:border-red-500 focus:ring-red-500'
                        ]"
                    />
                </div>
                <!-- Validation Message -->
                <div v-if="validation.password.message" class="text-xs" :class="validation.password.isValid ? 'text-green-600' : 'text-red-600'">
                    {{ validation.password.message }}
                </div>
                <InputError :message="errors.password" />
            </div>

            <!-- Confirm Password Field -->
            <div class="space-y-2">
                <Label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm Password</Label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Shield class="h-5 w-5 text-gray-400" />
                    </div>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        @keyup="validatePasswordConfirmation($event.target.value, $event.target.form?.password?.value || '')"
                        @input="validatePasswordConfirmation($event.target.value, $event.target.form?.password?.value || '')"
                        :class="[
                            'pl-10 h-12 border rounded-lg transition-colors bg-white text-gray-900',
                            validation.password_confirmation.isValid === null ? 'border-gray-300 focus:border-green-500 focus:ring-green-500' : 
                            validation.password_confirmation.isValid ? 'border-green-300 focus:border-green-500 focus:ring-green-500' : 'border-red-300 focus:border-red-500 focus:ring-red-500'
                        ]"
                    />
                </div>
                <!-- Validation Message -->
                <div v-if="validation.password_confirmation.message" class="text-xs" :class="validation.password_confirmation.isValid ? 'text-green-600' : 'text-red-600'">
                    {{ validation.password_confirmation.message }}
                </div>
                <InputError :message="errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <Button 
                type="submit" 
                class="w-full h-12 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" 
                :tabindex="5" 
                :disabled="processing"
            >
                <LoaderCircle v-if="processing" class="h-5 w-5 animate-spin mr-2" />
                {{ processing ? 'Creating Account...' : 'Create Account' }}
            </Button>

            <!-- Terms and Privacy -->
            <p class="text-xs text-gray-500 text-center">
                By creating an account, you agree to our 
                <a href="#" class="text-green-600 hover:text-green-700 underline">Terms of Service</a> 
                and 
                <a href="#" class="text-green-600 hover:text-green-700 underline">Privacy Policy</a>
            </p>

            <!-- Login Link -->
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <TextLink :href="login()" class="text-green-600 hover:text-green-700 font-medium underline underline-offset-2" :tabindex="6">
                        Sign in here
                    </TextLink>
                </p>
            </div>
        </Form>
    </AuthBase>
</template>
