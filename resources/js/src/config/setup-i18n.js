import VueI18n from "vue-i18n";

export function setupAndGetI18n(Vue, isProduction) {
    Vue.use(VueI18n);

    const i18n = new VueI18n({
        locale: 'fr',
        fallbackLocale: 'fr',
        fallbackRoot: false,
        silentTranslationWarn: true,

        missing(locale, key, vm) {
            return key;
        }
    });

    i18n.setLocaleMessage('en', require('../../src/i18n/en.json'));
    i18n.setLocaleMessage('fr', require('../../src/i18n/fr.json'));
    return i18n;
}
