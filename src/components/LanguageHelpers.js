export default class {
    static getLanguages() {
        return [
            {
                iso: 'de-DE',
                name: 'German'
            },
            {
                iso: 'en-US',
                name: 'English'
            },
            {
                iso: 'es-ES',
                name: 'Spanish'
            },
            {
                iso: 'fr-FR',
                name: 'French'
            },
            {
                iso: 'it-IT',
                name: 'Italian'
            },
            {
                iso: 'ja-JP',
                name: 'Japanese'
            },
            {
                iso: 'pl-PL',
                name: 'Polish'
            },
            {
                iso: 'pt-BR',
                name: 'Brazilian Portuguese'
            },
            {
                iso: 'ru-RU',
                name: 'Russian'
            },
            {
                iso: 'zh-CN',
                name: 'Simplified Chinese'
            },
            {
                iso: 'zh-TW',
                name: 'Traditional Chinese'
            }
        ];
    }

    static getCountryFlag(language) {
        switch (language) {
            case 'de-DE':
                return 'de';
            case 'en-US':
                return 'us';
            case 'es-ES':
                return 'es';
            case 'fr-FR':
                return 'fr';
            case 'it-IT':
                return 'it';
            case 'ja-JP':
                return 'jp';
            case 'pl-PL':
                return 'pl';
            case 'pt-BR':
                return 'br';
            case 'ru-RU':
                return 'ru';
            case 'zh-CN':
                return 'cn';
            case 'zh-TW':
                return 'tw';
            default:
                console.error('Failed to find flag for locale: ' + language);
                return 'us';
        }
    }

    static getCountryFlagForLocale(i18n) {
        let locale = i18n.locale;
        if (locale === undefined) {
            locale = i18n.fallbackLocale;
            i18n.locale = locale;
            localStorage.locale = i18n.locale;
        }

        return this.getCountryFlag(locale);
    }
}