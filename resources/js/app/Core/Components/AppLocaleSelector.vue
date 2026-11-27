<script setup lang="ts">
import { computed, mergeProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { useLocale } from 'vuetify';

import type { AppLocale } from '@Core/Localization';

const { locale: i18nLocale } = useI18n({
    useScope: 'global',
});

const { t } = useI18n();

const { current: vuetifyLocale } = useLocale();

const locales: Array<{
    value: AppLocale;
    label: string;
    subtitle: string;
}> = [
    {
        value: 'de-DE',
        label: 'Deutsch',
        subtitle: 'German',
    },
    {
        value: 'en-US',
        label: 'English',
        subtitle: 'English',
    },
];

const currentLocale = computed(
    () => i18nLocale.value as AppLocale,
);

function applyLocale(locale: AppLocale): void {
    if (currentLocale.value === locale) {
        return;
    }

    i18nLocale.value = locale;
    vuetifyLocale.value = locale;

    router.post('/locale', {
        locale,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<template>
    <v-menu location="bottom end" :close-on-content-click="true">
        <template #activator="{ props: menuProps }">
            <v-tooltip :text="t('application.general.tooltip.choose_locale')" location="bottom">
                <template #activator="{ props: tooltipProps }">
                    <v-btn
                        v-bind="mergeProps(menuProps, tooltipProps)"
                        class="icon-button"
                        icon="mdi-translate"
                        variant="text"
                        :aria-label="t('application.general.tooltip.choose_locale')"
                    />
                </template>
            </v-tooltip>
        </template>

        <v-list density="compact" min-width="180">
            <v-list-subheader>{{ t('application.general.settings.choose_locale') }}</v-list-subheader>

            <v-list-item
                v-for="item in locales"
                :key="item.value"
                :active="currentLocale === item.value"
                :title="item.label"
                :subtitle="item.subtitle"
                :prepend-icon="
                    currentLocale === item.value
                        ? 'mdi-check-circle'
                        : 'mdi-circle-outline'
                "
                @click="applyLocale(item.value)"
            />
        </v-list>
    </v-menu>
</template>
