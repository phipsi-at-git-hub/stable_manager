
<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

type ContextType = 'personal' | 'club' | 'stable';

const props = defineProps<{
    context: ContextType;
}>();

const page = usePage();

const currentPath = computed(() => page.url.split('?')[0]);

const personalItems = [
    { title: 'Übersicht', icon: 'mdi-view-dashboard-outline', href: '/dashboard' },
    { title: 'Mein Profil', icon: 'mdi-account-outline', href: '/profile' },
    { title: 'Community', icon: 'mdi-account-group-outline', href: '/community' },
];

const clubItems = [
    { title: 'Vereinsübersicht', icon: 'mdi-view-dashboard-outline', href: '/club' },
    { title: 'Personen', icon: 'mdi-account-multiple-outline', href: '/club/people' },
    { title: 'Aktivitäten', icon: 'mdi-calendar-star', href: '/club/activities' },
    { title: 'Informationen', icon: 'mdi-information-outline', href: '/club/information' },
];

const stableItems = [
    { title: 'Stallübersicht', icon: 'mdi-view-dashboard-outline', href: '/stable' },
    { title: 'Personen', icon: 'mdi-account-multiple-outline', href: '/stable/people' },
    { title: 'Pferde', icon: 'mdi-horse-variant', href: '/stable/horses' },
    { title: 'Reitbetrieb', icon: 'mdi-calendar-clock-outline', href: '/stable/riding' },
    { title: 'Anlagen', icon: 'mdi-map-marker-outline', href: '/stable/facilities' },
    { title: 'Aufgaben', icon: 'mdi-clipboard-check-outline', href: '/stable/tasks' },
    { title: 'Termine', icon: 'mdi-calendar-month-outline', href: '/stable/appointments' },
];

const items = computed(() => {
    if (props.context === 'club') return clubItems;
    if (props.context === 'stable') return stableItems;

    return personalItems;
});

const sectionTitle = computed(() => ({
    personal: 'PERSÖNLICH',
    club: 'VEREIN',
    stable: 'STALL',
}[props.context]));

function isActive(path: string): boolean {
    return currentPath.value === path;
}
</script>

<template>
    <aside class="sm-sidebar">
        <div class="sm-sidebar__section-label">
            {{ sectionTitle }}
        </div>

        <v-list nav density="comfortable" class="sm-sidebar__list">
            <v-list-item
                v-for="item in items"
                :key="item.href"
                class="navigation-item navigation-item--full-width"
                :class="{ 'navigation-item--active': isActive(item.href) }"
                :title="item.title"
                :prepend-icon="item.icon"
                :href="item.href"
                rounded="lg"
            />
        </v-list>

        <div class="sm-sidebar__bottom">
            <v-divider class="mb-3" />

            <v-list nav density="compact">
                <v-list-item
                    class="navigation-item navigation-item--full-width"
                    :class="{ 'navigation-item--active': isActive('/settings') }"
                    title="Einstellungen"
                    prepend-icon="mdi-cog-outline"
                    href="/settings"
                    rounded="lg"
                />
            </v-list>

            <div class="sm-sidebar__version">
                Development Environment
            </div>
        </div>
    </aside>
</template>
