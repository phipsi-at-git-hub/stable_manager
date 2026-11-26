
<script setup lang="ts">
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

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

const page = usePage();

const currentPath = computed(() => page.url.split('?')[0]);

function isActive(path: string): boolean {
    return currentPath.value === path;
}

function logout() {
    router.post('/logout');
}
</script>

<template>
    <header class="app-header">
        <div class="app-header__left">
            <v-btn
                class="app-header__mobile-menu"
                icon="mdi-menu"
                variant="text"
                aria-label="Navigation öffnen"
                @click="emit('toggle-navigation')"
            />

            <a href="/dashboard" class="app-brand" aria-label="Zur Übersicht">
                <span class="app-brand__mark">
                    <v-icon icon="mdi-horse-variant" />
                </span>

                <span class="app-brand__name">StableManager</span>
            </a>

            <nav class="app-header__global-nav" aria-label="Globale Navigation">
                <a
                    href="/dashboard"
                    class="navigation-item app-header__link"
                    :class="{ 'navigation-item--active': isActive('/dashboard') }"
                >
                    Start
                </a>

                <a
                    href="/community"
                    class="navigation-item app-header__link"
                    :class="{ 'navigation-item--active': isActive('/community') }"
                >
                    Community
                </a>
            </nav>
        </div>

        <div class="app-header__right">
            <AppContextSelector
                :model-value="context"
                @update:model-value="emit('update:context', $event)"
            />

            <v-tooltip text="Benachrichtigungen" location="bottom">
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        class="icon-button"
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
                        class="icon-button"
                        variant="text"
                        icon
                        aria-label="Benutzermenü öffnen"
                    >
                        <v-avatar size="34">
                            <v-icon icon="mdi-account" />
                        </v-avatar>
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
