<script setup lang="ts">
import { computed } from 'vue';

type ContextType = 'personal' | 'club' | 'stable';

const props = defineProps<{
    modelValue: ContextType;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: ContextType];
}>();

const contexts: Array<{
    value: ContextType;
    title: string;
    subtitle: string;
    icon: string;
}> = [
    {
        value: 'personal',
        title: 'Persönlicher Bereich',
        subtitle: 'Dein Bereich',
        icon: 'mdi-account-outline',
    },
    {
        value: 'club',
        title: 'Reitverein Sonnental',
        subtitle: 'Verein',
        icon: 'mdi-account-group-outline',
    },
    {
        value: 'stable',
        title: 'Stall Sonnental',
        subtitle: 'Stall',
        icon: 'mdi-horse-variant',
    },
];

const selected = computed(
    () => contexts.find((context) => context.value === props.modelValue) ?? contexts[0],
);
</script>

<template>
    <v-menu location="bottom start" :close-on-content-click="true">
        <template #activator="{ props: menuProps }">
            <v-btn
                v-bind="menuProps"
                class="width-limited"
                variant="text"
                prepend-icon="mdi-domain"
                append-icon="mdi-chevron-down"
                rounded="lg"
            >
                <span class="text-truncate">
                    {{ selected.title }}
                </span>
            </v-btn>
        </template>

        <v-list min-width="280" density="comfortable">
            <v-list-subheader>Arbeitskontext</v-list-subheader>

            <v-list-item
                v-for="context in contexts"
                :key="context.value"
                :active="modelValue === context.value"
                :title="context.title"
                :subtitle="context.subtitle"
                :prepend-icon="context.icon"
                @click="emit('update:modelValue', context.value)"
            />
        </v-list>
    </v-menu>
</template>
