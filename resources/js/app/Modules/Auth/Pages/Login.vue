<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
//import AuthLayout from '@/Modules/Auth/Layouts/AuthLayout.vue';
import AuthLayout from '../Layouts/AuthLayout.vue';

const passwordVisible = ref(false);

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => {
            form.reset('password');
        },
    });
}
</script>

<template>
    <Head title="Anmelden" />

    <AuthLayout>
        <div class="login-content">
            <div class="welcome-label">
                SCHÖN, DASS DU DA BIST
            </div>

            <h2>Willkommen zurück!</h2>

            <p class="login-description">
                Melde dich an und entdecke deinen persönlichen
                StableManager-Bereich.
            </p>

            <v-form
                class="login-form"
                @submit.prevent="submit"
            >
                <div class="field-group">
                    <label for="login">
                        Benutzername oder E-Mail-Adresse
                    </label>

                    <v-text-field
                        id="login"
                        v-model="form.login"
                        placeholder="Deine Zugangsdaten"
                        prepend-inner-icon="mdi-account-outline"
                        variant="outlined"
                        density="comfortable"
                        color="success"
                        autocomplete="username"
                        :error-messages="form.errors.login"
                        :disabled="form.processing"
                        hide-details="auto"
                    />
                </div>

                <div class="field-group">
                    <label for="password">
                        Passwort
                    </label>

                    <v-text-field
                        id="password"
                        v-model="form.password"
                        placeholder="Dein Passwort"
                        prepend-inner-icon="mdi-lock-outline"
                        :append-inner-icon="
                            passwordVisible
                                ? 'mdi-eye-off-outline'
                                : 'mdi-eye-outline'
                        "
                        :type="passwordVisible ? 'text' : 'password'"
                        variant="outlined"
                        density="comfortable"
                        color="success"
                        autocomplete="current-password"
                        :error-messages="form.errors.password"
                        :disabled="form.processing"
                        hide-details="auto"
                        @click:append-inner="
                            passwordVisible = !passwordVisible
                        "
                    />
                </div>

                <div class="login-options">
                    <v-checkbox
                        v-model="form.remember"
                        label="Angemeldet bleiben"
                        color="success"
                        density="compact"
                        hide-details
                        :disabled="form.processing"
                    />
                </div>

                <v-btn
                    type="submit"
                    color="success"
                    size="x-large"
                    block
                    rounded="lg"
                    elevation="0"
                    class="login-button"
                    :loading="form.processing"
                    :disabled="!form.login || !form.password"
                >
                    Anmelden
                    <v-icon
                        icon="mdi-arrow-right"
                        end
                    />
                </v-btn>

                <v-alert
                    v-if="form.errors.login"
                    type="error"
                    variant="tonal"
                    density="compact"
                    class="mt-4"
                >
                    {{ form.errors.login }}
                </v-alert>
            </v-form>

            <div class="login-divider">
                <span></span>
                <v-icon
                    icon="mdi-horse-variant"
                    size="20"
                />
                <span></span>
            </div>

            <div class="login-note">
                <v-icon
                    icon="mdi-shield-check-outline"
                    size="20"
                />

                <span>
                    Deine Daten sind sicher und werden geschützt
                    verarbeitet.
                </span>
            </div>
        </div>
    </AuthLayout>
</template>

<style scoped>
.login-content {
    width: 100%;
}

.welcome-label {
    margin-bottom: 14px;
    color: #a17b50;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
}

.login-content h2 {
    margin-bottom: 12px;
    color: #35483d;
    font-size: 36px;
    font-weight: 650;
    letter-spacing: -1.2px;
}

.login-description {
    margin-bottom: 36px;
    color: #817d73;
    font-size: 14px;
    line-height: 1.7;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.field-group label {
    color: #465348;
    font-size: 13px;
    font-weight: 600;
}

.login-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: -8px;
}

.login-button {
    min-height: 54px;
    margin-top: 4px;
    background-color: #35483d;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.2px;
}

.login-button:hover {
    background-color: #293a30;
}

.login-divider {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 36px 0 24px;
    color: #b18b5e;
}

.login-divider span {
    flex: 1;
    height: 1px;
    background: #e7e1d7;
}

.login-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #938d82;
    font-size: 12px;
    text-align: center;
}

.login-note :deep(.v-icon) {
    color: #718776;
    flex-shrink: 0;
}
</style>
