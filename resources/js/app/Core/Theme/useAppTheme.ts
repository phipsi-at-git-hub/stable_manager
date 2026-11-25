import { computed, watch } from 'vue';
import { useTheme } from 'vuetify';
import { APP_THEMES, type AppThemeName } from '@Core/UI/Vuetify';

export function useAppTheme() {
    const vuetifyTheme = useTheme();

    const currentTheme = computed(
        () => vuetifyTheme.global.name.value as AppThemeName,
    );

    function applyTheme(name: AppThemeName) {
        vuetifyTheme.global.name.value = name;

        if (typeof window !== 'undefined') {
            window.localStorage.setItem('app-theme', name);
            document.documentElement.dataset.smTheme = name;
            document.documentElement.style.colorScheme =
                APP_THEMES[name].dark ? 'dark' : 'light';
        }
    }

    watch(currentTheme, (name) => {
        if (typeof document !== 'undefined') {
            document.documentElement.dataset.smTheme = name;
            document.documentElement.style.colorScheme =
                APP_THEMES[name].dark ? 'dark' : 'light';
        }
    }, { immediate: true });

    return {
        currentTheme,
        themes: APP_THEMES,
        applyTheme,
    };
}
