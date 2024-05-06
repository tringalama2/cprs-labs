import './bootstrap';
import * as Sentry from "@sentry/browser"
import Sortable from 'sortablejs/modular/sortable.complete.esm.js';

Sentry.init({
    dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});

window.Sortable = Sortable
