// resources/js/app.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './notifications';
// --- Pusher / Echo (temps réel in-app)
Pusher.logToConsole = true;          // logs utiles en dev
window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: (import.meta.env.VITE_PUSHER_FORCE_TLS ?? 'true') === 'true',
  enabledTransports: ['ws', 'wss'],
});

console.log('Echo ready');

// --- Toast Bootstrap 5
function showToast({ title = 'Notification', body = '', url = null }) {
  const area = document.getElementById('toast-area');
  if (!area) return;

  if (!window.bootstrap?.Toast) {
    console.warn('Bootstrap Toast indisponible : charge bootstrap.bundle.min.js');
    return;
  }

  const id = 't' + Date.now();
  const html = `
  <div id="${id}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
    <div class="toast-header">
      <strong class="me-auto">${title}</strong>
      <small class="text-muted">maintenant</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      ${body}
      ${url ? `<div class="mt-2 pt-2 border-top"><a href="${url}" class="btn btn-sm btn-primary">Ouvrir</a></div>` : ''}
    </div>
  </div>`;
  area.insertAdjacentHTML('beforeend', html);
  const el = document.getElementById(id);
  const toast = new bootstrap.Toast(el);
  toast.show();
  el.addEventListener('hidden.bs.toast', () => el.remove());
}

// --- Badge dynamique
function bumpNotifBadge() {
  const b = document.getElementById('notifBadge');
  if (!b) return;
  const n = parseInt(b.textContent || '0', 10) + 1;
  b.textContent = String(n);
  b.classList.remove('d-none');
}

// --- Metas utiles
const uid = document.querySelector('meta[name="user-id"]')?.content || null;
const entrepriseId = document.querySelector('meta[name="entreprise-id"]')?.content || null;

// Canal privé user (Notifications Laravel via ->via('broadcast'))
if (uid) {
  window.Echo.private(`App.Models.User.${uid}`)
    .notification((n) => {
      showToast(n);
      bumpNotifBadge();
    });
}

// Canal public entreprise.{id} (pour événements métier)
if (entrepriseId) {
  window.Echo.channel(`entreprise.${entrepriseId}`)
    .listen('.service.created', (e) => {
      console.log('Nouveau service:', e.nom);
      // showToast({ title: 'Nouveau service', body: `« ${e.nom} » a été créé` });
      // bumpNotifBadge();
    });
}

// Logs de connexion Pusher
const pusherConn = window.Echo?.connector?.pusher?.connection;
if (pusherConn) {
  pusherConn.bind('connected', () => console.log('✅ Pusher connected'));
  pusherConn.bind('error', (err) => console.error('❌ Pusher error', err));
}

// ---- Beams (Push OS) ----
// Bouton côté vue : <button class="btn btn-primary btn-sm" onclick="enableBeams()">Activer les notifications</button>
async function enableBeams() {
  const swUrl = document.querySelector('meta[name="sw-beams"]')?.content || '/service-worker.js';
  const reg = await navigator.serviceWorker.register(swUrl, { scope: '/' });

  const Beams = window.PusherPushNotifications;
  const instanceId = import.meta.env.VITE_BEAMS_INSTANCE_ID;
  const beamsClient = new Beams.Client({ instanceId });

  // démarre Beams avec le service worker
  await beamsClient.start({ serviceWorkerRegistration: reg });

  // ----------- 🔐 Utilisateurs authentifiés -----------
  const uid = document.querySelector('meta[name="user-id"]')?.content;
  if (uid) {
    const tokenProvider = new Beams.TokenProvider({ url: '/beams/token' });
    await beamsClient.setUserId(uid, tokenProvider);
    console.log('Beams user set:', uid);
  }

  // (tu peux garder/ajouter des interests en complément si tu veux)
  const entrepriseId = document.querySelector('meta[name="entreprise-id"]')?.content;
  const interests = [];
  if (entrepriseId) interests.push(`entreprise-${entrepriseId}`);
  if (interests.length) await beamsClient.addDeviceInterests(interests);

  console.log('Beams ready');
}


// exposer au DOM
window.enableBeams = enableBeams;
