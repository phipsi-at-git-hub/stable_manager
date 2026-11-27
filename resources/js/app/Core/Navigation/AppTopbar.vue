<script setup lang="ts">
import { computed, mergeProps } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import AppContextSelector from './AppContextSelector.vue';
import AppThemeSelector from '@Core/Components/AppThemeSelector.vue';
import AppLocaleSelector from '@Core/Components/AppLocaleSelector.vue';

const { t } = useI18n();

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

function logout(): void {
    router.post('/logout');
}
</script>

<template>
    <header class="app-header">
        <div class="app-header__left">
            <v-tooltip :text="t('navigation.general.tooltip.open_navigation_menu')" location="bottom">
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        class="app-header__mobile-menu"
                        icon="mdi-menu"
                        variant="text"
                        :aria-label="t('navigation.general.tooltip.open_navigation_menu')"
                        @click="emit('toggle-navigation')"
                    />
                </template>
            </v-tooltip>

            <a href="/dashboard" class="app-brand" aria-label="Zur Übersicht">
                <span class="app-brand__mark">
                    <v-icon icon="mdi-horse-variant" />
                </span>

                <span class="app-brand__name">{{ t('application.name') }}</span>
            </a>

            <nav class="app-header__global-nav" aria-label="Globale Navigation">
                <v-tooltip :text="t('navigation.general.tooltip.start')" location="bottom">
                    <template #activator="{ props }">
                        <a
                            v-bind="props"
                            href="/dashboard"
                            class="navigation-item app-header__link"
                            :class="{ 'navigation-item--active': isActive('/dashboard') }"
                        >
                            {{ t('navigation.general.label.start') }}
                        </a>
                    </template>
                </v-tooltip>

                <v-tooltip :text="t('navigation.general.tooltip.community')" location="bottom">
                    <template #activator="{ props }">
                        <a
                            v-bind="props"
                            href="/community"
                            class="navigation-item app-header__link"
                            :class="{ 'navigation-item--active': isActive('/community') }"
                        >
                            {{ t('navigation.general.label.community') }}
                        </a>
                    </template>
                </v-tooltip>
            </nav>
        </div>

        <div class="app-header__right">
            <AppContextSelector
                :model-value="context"
                @update:model-value="emit('update:context', $event)"
            />

            <v-tooltip :text="t('navigation.general.tooltip.notifications')" location="bottom">
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        class="icon-button"
                        icon="mdi-bell-outline"
                        variant="text"
                        :aria-label="t('navigation.general.tooltip.notifications')"
                    />
                </template>
            </v-tooltip>

            <AppThemeSelector />

            <AppLocaleSelector />

            <v-menu location="bottom end">
                <template #activator="{ props: menuProps }">
                    <v-tooltip :text="t('navigation.general.tooltip.open_user_menu')" location="bottom">
                        <template #activator="{ props: tooltipProps }">
                            <v-btn
                                v-bind="mergeProps(menuProps, tooltipProps)"
                                class="icon-button"
                                variant="text"
                                icon
                                :aria-label="t('navigation.general.tooltip.open_user_menu')"
                            >
                                <v-avatar size="34">
                                    <v-icon icon="mdi-account" />
                                </v-avatar>
                            </v-btn>
                        </template>
                    </v-tooltip>
                </template>

                <v-list density="compact" min-width="190">
                    <v-list-item
                        :title="t('navigation.general.label.my_profile')"
                        prepend-icon="mdi-account-outline"
                        href="/profile"
                    />

                    <v-list-item
                        :title="t('navigation.general.label.settings')"
                        prepend-icon="mdi-cog-outline"
                        href="/settings"
                    />

                    <v-divider class="my-1" />

                    <v-list-item
                        :title="t('navigation.general.label.logout')"
                        prepend-icon="mdi-logout"
                        @click="logout"
                    />
                </v-list>
            </v-menu>
        </div>
    </header>
</template>
