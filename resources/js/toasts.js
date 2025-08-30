(function(){
  if (window.__toastsInit) return; // guard
  window.__toastsInit = true;

  // Create container if not present
  function ensureContainer(){
    let el = document.getElementById('toast-container');
    if (!el) {
      el = document.createElement('div');
      el.id = 'toast-container';
      el.className = 'fixed top-4 right-4 z-50 space-y-2 pointer-events-none';
      document.body.appendChild(el);
    }
    return el;
  }

  function makeToastElement(message){
    const el = document.createElement('div');
    el.className = 'bg-gray-900 text-white px-4 py-2 rounded shadow-lg opacity-90 pointer-events-auto';
    el.textContent = message;
    return el;
  }

  function show(message){
    if (!message) return;
    const c = ensureContainer();
    const el = makeToastElement(message);
    c.appendChild(el);
    // fade out then remove
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 250ms'; }, 3000);
    setTimeout(() => { el.remove(); }, 3400);
  }

  function wireLivewire(){
    // Use only browser events to avoid duplicates
    window.addEventListener('toast:show', (e)=>{
      const d = e.detail ?? {}; const msg = typeof d === 'string' ? d : (d.message ?? '');
      show(msg);
    });
    window.addEventListener('notify', (e)=>{
      const d = e.detail ?? {}; const msg = typeof d === 'string' ? d : (d.message ?? '');
      show(msg);
    });
  }

  function attachEcho(userId, adminId){
    if (!window.Echo) return;
    try {
      if (userId) {
        console.debug('[toasts] Subscribing user channel', `App.Models.User.${userId}`);
        window.Echo.private(`App.Models.User.${userId}`).notification((n) => {
          console.debug('[toasts] User notification', n);
          show(n.message || n.type || 'New notification');
          try {
            if (window.Livewire && typeof Livewire.dispatch === 'function') {
              Livewire.dispatch('order-status-updated', { userId: n.user_id, orderId: n.order_id, status: n.status });
            }
          } catch {}
        });
      }
      if (adminId) {
        console.debug('[toasts] Subscribing admin channel', `App.Models.Admin.${adminId}`);
        window.Echo.private(`App.Models.Admin.${adminId}`).notification((n) => {
          console.debug('[toasts] Admin notification', n);
          show(n.message || n.type || 'New notification');
        });
      }
    } catch (e) { console.warn('[toasts] attachEcho error', e); }
  }

  function init(){ ensureContainer(); wireLivewire(); }

  window.toasts = window.toasts || { show, init, attachEcho };

  // Auto-init on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ()=> init());
  } else { init(); }
})();
