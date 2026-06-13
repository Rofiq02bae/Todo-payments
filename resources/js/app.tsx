import { createInertiaApp } from '@inertiajs/react';
import type { ResolvedComponent } from '@inertiajs/react';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob<{ default: ResolvedComponent }>('./pages/**/*.tsx', { eager: true });

        return pages[`./pages/${name}.tsx`];
    },
    progress: {
        color: '#4B5563',
    },
});
