@php
  $notifyUserId = auth('web')->check() ? auth('web')->id() : null;
  $notifyAdminId = auth('admin')->check() ? auth('admin')->id() : null;
@endphp
<script>
  (function(){
    const tryAttach = () => {
      if (window.toasts && window.toasts.attachEcho) {
        window.toasts.attachEcho({{ $notifyUserId ? (int) $notifyUserId : 'null' }}, {{ $notifyAdminId ? (int) $notifyAdminId : 'null' }});
        return true;
      }
      return false;
    };
    if (!tryAttach()) {
      const iv = setInterval(()=>{ if (tryAttach()) clearInterval(iv); }, 300);
      setTimeout(()=> clearInterval(iv), 5000);
    }
  })();
  </script>
