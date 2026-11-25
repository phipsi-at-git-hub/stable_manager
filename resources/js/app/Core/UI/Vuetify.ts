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

const themes = {
    'meadow-light': {
        dark: false,
        colors: {
            primary: '#496B50',
            secondary: '#A18A68',
            accent: '#78947B',
            background: '#F4F6F1',
            surface: '#FFFFFF',
            'surface-variant': '#E9EEE7',
            success: '#43845C',
            warning: '#C18A36',
            error: '#C45C55',
            info: '#56869A',
        },
    },
    'meadow-dark': {
        dark: true,
        colors: {
            primary: '#9DBCA0',
            secondary: '#C4AE8D',
            accent: '#83A88A',
            background: '#171E19',
            surface: '#202A23',
            'surface-variant': '#2A382E',
            success: '#83C99A',
            warning: '#E2B66D',
            error: '#E58B83',
            info: '#83B9CC',
        },
    },
    'pulse-light': {
        dark: false,
        colors: {
            primary: '#315BEA',
            secondary: '#7A43D6',
            accent: '#00AFA5',
            background: '#F5F7FC',
            surface: '#FFFFFF',
            'surface-variant': '#EDF1FA',
            success: '#16845F',
            warning: '#C27B16',
            error: '#D8465C',
            info: '#168AC1',
        },
    },
    'pulse-dark': {
        dark: true,
        colors: {
            primary: '#91A9FF',
            secondary: '#C3A0FF',
            accent: '#62D8D0',
            background: '#151927',
            surface: '#20263A',
            'surface-variant': '#2B3450',
            success: '#68CDA7',
            warning: '#F0BD70',
            error: '#F18B9A',
            info: '#79C8EE',
        },
    },
    'vice-light': {
        dark: false,
        colors: {
            primary: '#E64F91',
            secondary: '#12AEBB',
            accent: '#F2A34A',
            background: '#FFF9F7',
            surface: '#FFFFFF',
            'surface-variant': '#FCEAF1',
            success: '#238A72',
            warning: '#D8892E',
            error: '#D94B5B',
            info: '#159BAE',
        },
    },
    'vice-dark': {
        dark: true,
        colors: {
            primary: '#FF80B5',
            secondary: '#62DCE4',
            accent: '#FFC078',
            background: '#1D1724',
            surface: '#2A2032',
            'surface-variant': '#3A2A43',
            success: '#6BD0AE',
            warning: '#F4BD73',
            error: '#F18A99',
            info: '#70D5E2',
        },
    },
};

function getInitialTheme(): AppThemeName {
    if (typeof window === 'undefined') return 'meadow-light';

    const stored = window.localStorage.getItem('app-theme') as AppThemeName | null;
    return stored && stored in themes ? stored : 'meadow-light';
}

export function createStableManagerVuetify() {
    const defaultTheme = getInitialTheme();

    return createVuetify({
        theme: {
            defaultTheme,
            themes,
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
