<script setup lang="ts">
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { InertiaForm, useForm } from "@inertiajs/vue3";
import { inject } from "vue";

const twoFactorEnableForm = inject<InertiaForm<{}>>("twoFactorEnableForm", useForm({}));

const enableTwoFactorAuth = () => {
    twoFactorEnableForm?.post(route("two-factor.enable"), {
        preserveScroll: true
    });
};
</script>

<template>
    <div>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Enable Two Factor Authetication
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Enable this feature to make your account safer by requiring to
                input a 6 digit one-time-use code.
            </p>
        </header>

        <form @submit.prevent="enableTwoFactorAuth" class="mt-6 space-y-6">
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="twoFactorEnableForm.processing" label="Enable" />
            </div>
        </form>
    </div>
</template>
