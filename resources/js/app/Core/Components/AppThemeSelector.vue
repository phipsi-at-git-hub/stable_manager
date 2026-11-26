<script setup lang="ts">
import { mergeProps } from 'vue';

import { useAppTheme } from '@Core/Theme';

const { currentTheme, themes, applyTheme } = useAppTheme();
</script>

<template>
    <v-menu location="bottom end" :close-on-content-click="true">
        <template #activator="{ props: menuProps }">
            <v-tooltip text="Theme auswählen" location="bottom">
                <template #activator="{ props: tooltipProps }">
                    <v-btn
                        v-bind="mergeProps(menuProps, tooltipProps)"
                        class="icon-button"
                        icon="mdi-palette-outline"
                        variant="text"
                        aria-label="Theme auswählen"
                    />
                </template>
            </v-tooltip>
        </template>

        <v-list density="compact" min-width="220">
            <v-list-subheader>Design auswählen</v-list-subheader>

            <v-list-item
                v-for="(details, name) in themes"
                :key="name"
                :active="currentTheme === name"
                :title="details.label"
                :subtitle="details.family"
                :prepend-icon="
                    currentTheme === name
                        ? 'mdi-check-circle'
                        : 'mdi-circle-outline'
                "
                @click="applyTheme(name)"
            />
        </v-list>
    </v-menu>
</template>
