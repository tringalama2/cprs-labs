import './bootstrap';
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import * as Sentry from "@sentry/browser";
import {createApp} from 'vue'
import App from './App.vue'

createApp(App).mount("#app")

Sentry.init({
    dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
Alpine.plugin(focus)
window.Alpine = Alpine
Alpine.start()

