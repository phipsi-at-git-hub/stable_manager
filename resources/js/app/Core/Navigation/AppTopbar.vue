<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AppContextSelector from './AppContextSelector.vue';
import AppThemeSelector from '@Core/Components/AppThemeSelector.vue';

type ContextType = 'personal' | 'club' | 'stable';

defineProps<{
    context: ContextType;
}>();

const emit = defineEmits<{
    'update:context': [value: ContextType];
    'toggle-navigation': [];
}>();

function logout() {
    router.post('/logout');
}
</script>

<template>
    <header class="sm-topbar">
        <div class="sm-topbar__left">
            <v-btn
                class="sm-topbar__mobile-menu"
                icon="mdi-menu"
                variant="text"
                aria-label="Navigation öffnen"
                @click="emit('toggle-navigation')"
            />

            <a href="/dashboard" class="sm-brand" aria-label="Zur Übersicht">
                <span class="sm-brand__mark">
                    <v-icon icon="mdi-horse-variant" />
                </span>
                <span class="sm-brand__name">StableManager</span>
            </a>

            <nav class="sm-topbar__global-nav" aria-label="Globale Navigation">
                <a href="/dashboard" class="sm-topbar__link sm-topbar__link--active">Start</a>
                <a href="/community" class="sm-topbar__link">Community</a>
            </nav>
        </div>

        <div class="sm-topbar__right">
            <AppContextSelector
                :model-value="context"
                @update:model-value="emit('update:context', $event)"
            />

            <v-tooltip text="Benachrichtigungen" location="bottom">
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        icon="mdi-bell-outline"
                        variant="text"
                        aria-label="Benachrichtigungen"
                    />
                </template>
            </v-tooltip>

            <AppThemeSelector />

            <v-menu location="bottom end">
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        class="sm-profile-trigger"
                        variant="text"
                        rounded="lg"
                    >
                        <v-avatar color="primary" size="34">
                            <v-icon icon="mdi-account" />
                        </v-avatar>
                        <v-icon icon="mdi-chevron-down" size="small" />
                    </v-btn>
                </template>

                <v-list density="compact" min-width="190">
                    <v-list-item
                        title="Mein Profil"
                        prepend-icon="mdi-account-outline"
                        href="/profile"
                    />
                    <v-list-item
                        title="Einstellungen"
                        prepend-icon="mdi-cog-outline"
                        href="/settings"
                    />
                    <v-divider class="my-1" />
                    <v-list-item
                        title="Abmelden"
                        prepend-icon="mdi-logout"
                        @click="logout"
                    />
                </v-list>
            </v-menu>
        </div>
    </header>
</template>
