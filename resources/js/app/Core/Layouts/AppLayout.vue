<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppTopbar from '@Core/Navigation/AppTopbar.vue';
import AppSidebar from '@Core/Navigation/AppSidebar.vue';

type ContextType = 'personal' | 'club' | 'stable';

const props = withDefaults(defineProps<{
    title?: string;
}>(), {
    title: 'Übersicht',
});

const context = ref<ContextType>('stable');
const navigationOpen = ref(false);
</script>

<template>
    <v-app class="sm-app">
        <Head :title="`${props.title} | StableManager`" />

        <div class="sm-application">
            <AppTopbar
                :context="context"
                @update:context="context = $event"
                @toggle-navigation="navigationOpen = !navigationOpen"
            />

            <div class="sm-shell">
                <AppSidebar
                    class="sm-shell__sidebar"
                    :context="context"
                />

                <main class="sm-shell__main">
                    <slot />
                </main>
            </div>

            <v-navigation-drawer
                v-model="navigationOpen"
                temporary
                location="left"
                width="280"
                class="sm-mobile-drawer"
            >
                <AppSidebar :context="context" />
            </v-navigation-drawer>
        </div>
    </v-app>
</template>
