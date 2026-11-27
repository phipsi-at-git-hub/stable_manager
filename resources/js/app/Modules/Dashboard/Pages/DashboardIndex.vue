<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AppLayout from '@Core/Layouts/AppLayout.vue';
import NavigationCard from '@Core/Components/NavigationCard.vue';

const { t } = useI18n();

const greeting = computed(() => {
    const hour = new Date().getHours();

    if (hour < 6) return t('application.general.label.goodNight') //'Gute Nacht';
    if (hour < 11) return t('application.general.label.goodMorning') //'Guten Morgen';
    if (hour < 18) return t('application.general.label.goodAfternoon') //'Guten Tag';
    return t('application.general.label.goodEvening') //'Guten Abend';
});
</script>

<template>
    <AppLayout :title="t('application.general.label.overview')">
        <div class="sm-page">
            <header class="sm-page-heading">
                <div>
                    <div class="sm-page-heading__eyebrow">{{ t('application.general.label.your_overview').toUpperCase() }}</div>
                    <h1>{{ greeting }}!</h1>
                    <p>
                        {{ t('application.general.label.welcome') }}
                    </p>
                </div>

                <v-chip color="success" variant="tonal" prepend-icon="mdi-check-circle-outline">
                    {{ t('application.general.label.in_sight') }}
                </v-chip>
            </header>

            <section class="sm-dashboard-section">
                <div class="sm-section-heading">
                    <div>
                        <h2>{{ t('application.general.label.your_sections') }}</h2>
                        <p>{{ t('application.general.label.directly_to_sections') }}</p>
                    </div>
                </div>

                <div class="sm-navigation-grid">
                    <NavigationCard
                        href="/community"
                        icon="mdi-account-group-outline"
                        title="Community"
                        description="Austausch, Neuigkeiten und gemeinsame Aktivitäten."
                        eyebrow="GEMEINSCHAFT"
                    />
                    <NavigationCard
                        href="/stable"
                        icon="mdi-horse-variant"
                        title="Stall"
                        description="Personen, Pferde und Informationen rund um den Stall."
                        eyebrow="DEIN KONTEXT"
                    />
                    <NavigationCard
                        href="/club"
                        icon="mdi-domain"
                        title="Verein"
                        description="Vereinsleben, Mitglieder und organisatorische Themen."
                        eyebrow="ORGANISATION"
                    />
                    <NavigationCard
                        href="/appointments"
                        icon="mdi-calendar-month-outline"
                        title="Termine"
                        description="Behalte anstehende Termine und Aktivitäten im Blick."
                        eyebrow="PLANUNG"
                    />
                </div>
            </section>

            <section class="sm-dashboard-lower">
                <div class="sm-panel">
                    <div class="sm-panel__heading">
                        <div>
                            <h2>Demnächst</h2>
                            <p>Hier erscheinen deine nächsten Termine.</p>
                        </div>
                        <v-icon icon="mdi-calendar-clock-outline" size="24" />
                    </div>
                    <div class="sm-empty-state">
                        <v-icon icon="mdi-calendar-blank-outline" size="30" />
                        <span>Aktuell sind keine Termine hinterlegt.</span>
                    </div>
                </div>

                <div class="sm-panel">
                    <div class="sm-panel__heading">
                        <div>
                            <h2>Aktuelles</h2>
                            <p>Neuigkeiten aus deinen Bereichen.</p>
                        </div>
                        <v-icon icon="mdi-bell-outline" size="24" />
                    </div>
                    <div class="sm-empty-state">
                        <v-icon icon="mdi-message-text-outline" size="30" />
                        <span>Es gibt noch keine neuen Mitteilungen.</span>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
