import { createI18n } from 'vue-i18n';

export type AppLocale = 'en-US' | 'de-DE';

export type TranslationTree = {
    [key: string]: string | TranslationTree;
};

export type TranslationMessages = Record<AppLocale, TranslationTree>;

export interface LocalizationPayload {
    locale: AppLocale;
    fallbackLocale: AppLocale;
    messages: TranslationMessages;
}

export function createAppI18n(localization: LocalizationPayload) {
    return createI18n<TranslationTree, AppLocale, false>({
        legacy: false,
        globalInjection: true,
        locale: localization.locale,
        fallbackLocale: localization.fallbackLocale,
        messages: localization.messages,
        missingWarn: import.meta.env.DEV,
        fallbackWarn: import.meta.env.DEV,
    });
}
