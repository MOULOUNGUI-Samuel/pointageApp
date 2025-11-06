// resources/js/push.js
async function urlBase64ToUint8Array(base64) {
    const padding = '='.repeat((4 - (base64.length % 4)) % 4);
    const b64 = (base64 + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const raw = atob(b64);
    const output = new Uint8Array(raw.length);
    for (let i = 0; i < raw.length; ++i) output[i] = raw.charCodeAt(i);
    return output;
  }
  
  export async function enablePush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
      throw new Error('Push non supporté par ce navigateur.');
    }
  
    const reg = await navigator.serviceWorker.register('/sw.js', { scope: '/' });
  
    const perm = await Notification.requestPermission();
    if (perm !== 'granted') throw new Error('Permission refusée.');
  
    const vapid = import.meta.env.VITE_VAPID_PUBLIC_KEY;
    const sub = await reg.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: await urlBase64ToUint8Array(vapid),
    });
  
    await fetch('/push/subscribe', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
      body: JSON.stringify(sub),
      credentials: 'same-origin'
    });
  }
  
  export async function disablePush() {
    const reg = await navigator.serviceWorker.getRegistration();
    const sub = await reg?.pushManager.getSubscription();
    if (sub) {
      await fetch('/push/unsubscribe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ endpoint: sub.endpoint }),
        credentials: 'same-origin'
      });
      await sub.unsubscribe();
    }
  }
  