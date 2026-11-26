<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '@Modules/Auth/Layouts/AuthLayout.vue';

const passwordVisible = ref(false);

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Anmelden | StableManager" />

    <AuthLayout>
        <section class="sm-login">
            <div class="sm-login__eyebrow">SCHÖN, DASS DU DA BIST</div>
            <h2>Willkommen zurück.</h2>
            <p class="sm-login__description">
                Melde dich an und öffne deinen persönlichen Bereich.
            </p>

            <v-form class="sm-login__form" @submit.prevent="submit">
                <div class="sm-login__field">
                    <label for="login">Benutzername oder E-Mail-Adresse</label>
                    <v-text-field
                        id="login"
                        v-model="form.login"
                        placeholder="Deine Zugangsdaten"
                        prepend-inner-icon="mdi-account-outline"
                        autocomplete="username"
                        :error-messages="form.errors.login"
                        :disabled="form.processing"
                        hide-details="auto"
                    />
                </div>

                <div class="sm-login__field">
                    <label for="password">Passwort</label>
                    <v-text-field
                        id="password"
                        v-model="form.password"
                        placeholder="Dein Passwort"
                        prepend-inner-icon="mdi-lock-outline"
                        :append-inner-icon="passwordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                        :type="passwordVisible ? 'text' : 'password'"
                        autocomplete="current-password"
                        :error-messages="form.errors.password"
                        :disabled="form.processing"
                        hide-details="auto"
                        @click:append-inner="passwordVisible = !passwordVisible"
                    />
                </div>

                <v-checkbox
                    v-model="form.remember"
                    label="Angemeldet bleiben"
                    density="compact"
                    hide-details
                    :disabled="form.processing"
                />

                <v-btn
                    type="submit"
                    size="x-large"
                    block
                    rounded="lg"
                    class="sm-login__submit"
                    :loading="form.processing"
                    :disabled="!form.login || !form.password"
                >
                    Anmelden
                    <v-icon icon="mdi-arrow-right" end />
                </v-btn>

                <v-alert
                    v-if="form.errors.login"
                    type="error"
                    variant="tonal"
                    density="compact"
                >
                    {{ form.errors.login }}
                </v-alert>
            </v-form>

            <div class="sm-login__security">
                <v-icon icon="mdi-shield-check-outline" />
                <span>Deine Daten werden geschützt verarbeitet.</span>
            </div>
        </section>
    </AuthLayout>
</template>
