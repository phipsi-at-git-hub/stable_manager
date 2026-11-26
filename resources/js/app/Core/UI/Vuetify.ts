import { createVuetify } from 'vuetify';

export type AppThemeName =
    | 'meadow-light'
    | 'meadow-dark'
    | 'pulse-light'
    | 'pulse-dark'
    | 'vice-light'
    | 'vice-dark';

export const APP_THEMES: Record<AppThemeName, {
    label: string;
    family: string;
    dark: boolean;
}> = {
    'meadow-light': { label: 'Meadow · Light', family: 'Meadow', dark: false },
    'meadow-dark': { label: 'Meadow · Dark', family: 'Meadow', dark: true },
    'pulse-light': { label: 'Pulse · Light', family: 'Pulse', dark: false },
    'pulse-dark': { label: 'Pulse · Dark', family: 'Pulse', dark: true },
    'vice-light': { label: 'Vice · Light', family: 'Vice', dark: false },
    'vice-dark': { label: 'Vice · Dark', family: 'Vice', dark: true },
};

function getInitialTheme(): AppThemeName {
    if (typeof window === 'undefined') {
        return 'meadow-light';
    }

    const stored = window.localStorage.getItem('app-theme');

    return stored && Object.hasOwn(APP_THEMES, stored)
        ? stored as AppThemeName
        : 'meadow-light';
}

export function createStableManagerVuetify() {
    const defaultTheme = getInitialTheme();

    return createVuetify({
        theme: {
            defaultTheme,
            themes: Object.fromEntries(
                Object.entries(APP_THEMES).map(([name, theme]) => [
                    name,
                    { dark: theme.dark },
                ]),
            ),
        },

        defaults: {
            VCard: {
                rounded: 'lg',
            },
            VTextField: {
                variant: 'outlined',
                density: 'comfortable',
            },
            VSelect: {
                variant: 'outlined',
                density: 'comfortable',
            },
        },
    });
}
