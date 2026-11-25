<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

withDefaults(defineProps<{
    href: string;
    icon: string;
    title: string;
    description: string;
    eyebrow?: string;
}>(), {
    eyebrow: 'BEREICH ÖFFNEN',
});
</script>

<template>
    <Link :href="href" class="sm-navigation-card">
        <span class="sm-navigation-card__icon">
            <v-icon :icon="icon" size="25" />
        </span>

        <span class="sm-navigation-card__body">
            <span class="sm-navigation-card__eyebrow">{{ eyebrow }}</span>
            <span class="sm-navigation-card__title">{{ title }}</span>
            <span class="sm-navigation-card__description">{{ description }}</span>
        </span>

        <v-icon
            class="sm-navigation-card__arrow"
            icon="mdi-arrow-up-right"
            size="20"
        />
    </Link>
</template>
