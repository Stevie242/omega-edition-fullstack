import { onMounted, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import type { CurrencyCode } from '@/lib/currency';

export type Appearance = 'light' | 'dark' | 'system';

export function updateTheme(value: Appearance) {
    if (typeof window === 'undefined') {
        return;
    }

    if (value === 'system') {
        const mediaQueryList = window.matchMedia(
            '(prefers-color-scheme: dark)',
        );
        const systemTheme = mediaQueryList.matches ? 'dark' : 'light';

        document.documentElement.classList.toggle(
            'dark',
            systemTheme === 'dark',
        );
    } else {
        document.documentElement.classList.toggle('dark', value === 'dark');
    }
}

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const mediaQuery = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.matchMedia('(prefers-color-scheme: dark)');
};

const handleSystemThemeChange = () => {
    const currentAppearance = getStoredAppearance();

    updateTheme(currentAppearance || 'system');
};

const appearance = ref<Appearance>('system');
const currency = ref<CurrencyCode>('XAF');

const getStoredAppearance = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('appearance') as Appearance | null;
};

export function resolveAppearance(preferenceTheme?: Appearance | null) {
    // Order: server preference -> localStorage -> system
    return preferenceTheme || getStoredAppearance() || 'system';
}

export function initializeTheme(preferenceTheme?: Appearance | null) {
    if (typeof window === 'undefined') {
        return;
    }

    const initialAppearance = resolveAppearance(preferenceTheme);
    updateTheme(initialAppearance);

    // Set up system theme change listener...
    mediaQuery()?.addEventListener('change', handleSystemThemeChange);
}

export function useAppearance(
    initialPreference?: { theme?: Appearance | null; currency?: CurrencyCode | null } | null,
) {
    const page = usePage();
    onMounted(() => {
        const serverTheme = (page.props.preference as any)?.theme as Appearance | undefined;
        const serverCurrency = (page.props.preference as any)?.currency as CurrencyCode | undefined;
        const savedAppearance = resolveAppearance(initialPreference?.theme ?? serverTheme ?? null);
        appearance.value = savedAppearance;
        currency.value = initialPreference?.currency ?? serverCurrency ?? currency.value;
        updateTheme(savedAppearance);
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;

        localStorage.setItem('appearance', value);
        setCookie('appearance', value);

        updateTheme(value);
        syncPreference({ theme: value, currency: currency.value });
    }

    function updateCurrency(value: CurrencyCode) {
        currency.value = value;
        syncPreference({ theme: appearance.value, currency: value });
    }

    function syncPreference(payload: { theme: Appearance; currency: CurrencyCode }) {
        const user = (page.props.auth as any)?.user;
        if (!user) return;

        router.put(
            '/settings/preferences',
            {
                theme: payload.theme,
                currency: payload.currency,
                data: null,
            },
            {
                preserveScroll: true,
                preserveState: true,
            },
        );
    }

    return {
        appearance,
        currency,
        updateAppearance,
        updateCurrency,
    };
}
