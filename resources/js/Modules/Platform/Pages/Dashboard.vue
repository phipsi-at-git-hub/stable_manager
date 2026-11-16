<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard | StableManager" />

    <main class="dashboard">
        <header>
            <h1>StableManager</h1>
            <p>Die Entwicklungsumgebung läuft.</p>
        </header>

        <section class="status">
            <h2>Erste Testausgabe</h2>
            <p>
                Laravel, Inertia, Vue und Vite sind miteinander verbunden.
            </p>
            <span class="status-indicator">
                System erreichbar
            </span>
        </section>

        <footer>
            StableManager – Development Environment
        </footer>
    </main>
</template>

<style scoped>
.dashboard {
    max-width: 1000px;
    margin: 80px auto;
    padding: 32px;
    font-family: Arial, sans-serif;
    color: #243247;
}

h1 {
    font-size: 36px;
    margin-bottom: 8px;
}

header p {
    color: #64748b;
}

.status {
    margin-top: 48px;
    padding: 28px;
    border: 1px solid #dce3eb;
    border-radius: 12px;
}

.status-indicator {
    display: inline-block;
    margin-top: 16px;
    padding: 8px 14px;
    background: #dcfce7;
    color: #166534;
    border-radius: 6px;
    font-size: 14px;
}

footer {
    margin-top: 64px;
    color: #94a3b8;
    font-size: 13px;
}
</style>
