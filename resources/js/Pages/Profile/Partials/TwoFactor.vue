<script setup lang="ts">
import { computed, provide } from "vue";
import Enable from "@/Components/TwoFactor/Enable.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Confirm from "@/Components/TwoFactor/Confirm.vue";
import { PageProps } from "@/types";
import RecoveryCodes from "@/Components/TwoFactor/RecoveryCodes.vue";

defineProps<{
    confirmedPassword?: boolean;
    status?: String;
}>();

const tfaEnabled = computed(
    () => usePage<PageProps>().props.auth.user.two_factor_secret !== null,
);
const tfaConfirmed = computed(
    () => usePage<PageProps>().props.auth.user.two_factor_confirmed_at !== null,
);

const twoFactorEnableForm = useForm({});

provide("twoFactorEnableForm", twoFactorEnableForm);
</script>

<template>
    <section>
        <Enable v-if="!tfaEnabled" />
        <Confirm
            v-if="tfaEnabled && !tfaConfirmed"
            :status="status"
            :confirmed-password="confirmedPassword"
        />
        <RecoveryCodes v-if="tfaEnabled && tfaConfirmed" />
    </section>
</template>
