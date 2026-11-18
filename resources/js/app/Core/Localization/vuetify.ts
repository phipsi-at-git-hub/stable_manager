import { de, en } from 'vuetify/locale';
import type { AppLocale } from './i18n';

const vuetifyMessages = {
    'en-US': en,
    'de-DE': de,
};

export function getVuetifyLocale(locale: AppLocale) {
    return {
        locale: locale,
        fallback: 'en-US',
        messages: vuetifyMessages,
    };
}
