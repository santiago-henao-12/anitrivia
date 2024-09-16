<script setup lang="ts">
import { InertiaForm, useForm, router } from "@inertiajs/vue3";
import Message from "primevue/message";
import { inject, onMounted, ref } from "vue";
import axios from "axios";
import Button from "primevue/button";
import InputOtp from "primevue/inputotp";

const props = defineProps<{
    status?: String;
    confirmedPassword?: boolean;
}>();

/**
 * Obtains Qr Code on Component Mount
 */
onMounted(async () => {
    try {
        // When the components renders after enabling TFA, make the query right away
        if (props.confirmedPassword) {
            const response = await axios.get(route("two-factor.qr-code"));

            qrSvg.value = response.data.svg;
        }
    } catch (error) {
        console.error(error);
    }
});

/**
 * @const oneTimePassword Code used to verify two factor authentication.
 */
const oneTimePassword = ref<number | null>(null);

/**
 * @constant confirmPasswordForm Form to visit the password confirmation page in case it's necesary.
 */
const passwordConfirmForm = useForm({});
const passwordConfirm = () => {
    passwordConfirmForm.get(route("password.confirm"), {
        preserveScroll: true,
    });
};

/**
 * @constant twoFactorConfirmForm Form to confirm two factor authentication.
 */
const twoFactorConfirmForm = useForm({});
const twoFactorConfirm = () => {
    twoFactorConfirmForm.post(route("password.confirm"), {
        preserveScroll: true,
    });
};

/**
 * @constant qrSvg Holds the data for the Qr that contains the shared secret for two factor authentication.
 */
const qrSvg = ref<string>("");

/**
 * @constant twoFactorEnableForm Reference to the form submitted to enable two factor authentication.
 */
const twoFactorEnableForm = inject<InertiaForm<{}>>(
    "twoFactorEnableForm",
    useForm({}),
);
</script>

<template>
    <div class="flex flex-col md:container md:mx-auto">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Confirm Two Factor Authetication
            </h2>
        </header>

        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <Message
                v-if="
                    twoFactorEnableForm.recentlySuccessful &&
                    status === 'two-factor-authentication-enabled'
                "
                severity="success"
            >
                Two Factor Authentication enabled, please continue the set-up
                process as described below.
            </Message>
        </Transition>
        <form
            @submit.prevent="passwordConfirm"
            class="mt-6 space-y-6"
            v-if="!confirmedPassword"
        >
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                To continue with the setup of the Two Factor Authentication
                please confirm your password.
            </p>
            <Button
                type="submit"
                :disabled="passwordConfirmForm.processing"
                label="Confirm Password"
            />
        </form>
        <form @submit.prevent="twoFactorConfirm" class="mt-6 space-y-6" v-else>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Scan the Qr Code bellow using an authenticator app like Google
                Authenticator and confirm by entering a one time code provided
                by the app.
            </p>
            <div v-html="qrSvg"></div>
            <InputOtp v-model="oneTimePassword" :length="6"/>
            <Button
                type="submit"
                :disabled="passwordConfirmForm.processing"
                label="Confirm"
            />
        </form>
    </div>
</template>
