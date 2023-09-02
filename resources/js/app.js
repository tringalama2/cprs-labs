import './bootstrap';
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import * as Sentry from "@sentry/browser"
import Sortable from 'sortablejs/modular/sortable.complete.esm.js';

Sentry.init({
    dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
Alpine.plugin(focus)
window.Alpine = Alpine
Alpine.start()
window.Sortable = Sortable
