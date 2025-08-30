import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Include CSRF token and cookies for auth endpoints
try {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  if (token) window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} catch {}
window.axios.defaults.withCredentials = true;

// Laravel Echo (Reverb) initialization
try {
  const { default: Echo } = await import('laravel-echo');
  const { default: Pusher } = await import('pusher-js');
  window.Pusher = Pusher;
  window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
    wsPort: Number(import.meta.env.VITE_REVERB_PORT ?? 80),
    wssPort: Number(import.meta.env.VITE_REVERB_PORT ?? 443),
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
      headers: typeof window.axios?.defaults?.headers?.common !== 'undefined' ? window.axios.defaults.headers.common : {},
      withCredentials: true,
    },
  });
} catch (e) {
  console.warn('Echo init skipped (dependencies not installed yet):', e?.message || e);
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

// Ensure Echo is initialized only once (we already did above)
