import { createVuetify } from "vuetify";

export function createStableManagerVuetify() {
    return createVuetify({
        theme: {
            defaultTheme: 'stableManagerLight', 
            themes: {
                stableManagerLight: {
                    dark: false, 
                }
            }
        }
    });
}
