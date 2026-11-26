<script setup lang="ts">
import AppThemeSelector from '@Core/Components/AppThemeSelector.vue';
import AppLocaleSelector from '@Core/Components/AppLocaleSelector.vue';
</script>

<template>
    <v-app class="sm-app">
        <div class="sm-auth-layout">
            <section class="sm-auth-visual">
                <div class="sm-auth-visual__orb sm-auth-visual__orb--one"></div>
                <div class="sm-auth-visual__orb sm-auth-visual__orb--two"></div>

                <a href="/" class="sm-brand sm-brand--auth">
                    <span class="sm-brand__mark">
                        <v-icon icon="mdi-horse-variant" />
                    </span>
                    <span class="sm-brand__name">StableManager</span>
                </a>

                <div class="sm-auth-visual__message">
                    <div class="sm-auth-visual__eyebrow">GEMEINSAM VERBUNDEN</div>
                    <h1>Alles rund um Pferde und Menschen. An einem Ort.</h1>
                    <p>
                        Organisiere deinen Alltag, bleib mit deiner Gemeinschaft
                        verbunden und behalte im Blick, was wirklich wichtig ist.
                    </p>

                    <div class="sm-auth-visual__benefits">
                        <div>
                            <v-icon icon="mdi-horse-variant" />
                            <span>Menschen und Pferde zusammenbringen</span>
                        </div>
                        <div>
                            <v-icon icon="mdi-account-group-outline" />
                            <span>Gemeinschaft einfach organisieren</span>
                        </div>
                        <div>
                            <v-icon icon="mdi-calendar-check-outline" />
                            <span>Gemeinsame Aktivitäten im Blick behalten</span>
                        </div>
                    </div>
                </div>

                <div class="sm-auth-visual__footer">
                    <span>StableManager</span>
                    <span>Für die Pferde-Community</span>
                </div>
            </section>

            <main class="sm-auth-content">
                <div class="sm-auth-content__toolbar">
                    <AppThemeSelector />

                    <AppLocaleSelector />
                </div>

                <div class="sm-auth-content__inner">
                    <div class="sm-auth-mobile-brand sm-brand">
                        <span class="sm-brand__mark">
                            <v-icon icon="mdi-horse-variant" />
                        </span>
                        <span class="sm-brand__name">StableManager</span>
                    </div>

                    <slot />
                </div>

                <footer class="sm-auth-content__footer">
                    <span>© {{ new Date().getFullYear() }} StableManager</span>
                    <span>Dein Bereich. Deine Gemeinschaft.</span>
                </footer>
            </main>
        </div>
    </v-app>
</template>
