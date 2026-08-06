{{-- <div id="offline-banner" style="display:none; position:fixed; bottom:0; left:0; width:100%; z-index:9999; background:#dc3545; color:white; text-align:center; padding:10px; font-weight:bold;">
    ⚠️ Oups ! Vous êtes hors ligne. Vérifiez votre connexion Internet.
</div>

<div id="online-banner" style="display:none; position:fixed; bottom:0; left:0; width:100%; z-index:9999; background:#28a745; color:white; text-align:center; padding:10px; font-weight:bold;">
    ✅ Connexion rétablie !
</div>

<script>
    function showOffline() {
        document.getElementById('offline-banner').style.display = 'block';
        document.getElementById('online-banner').style.display = 'none';
    }
    function showOnline() {
        document.getElementById('offline-banner').style.display = 'none';
        const online = document.getElementById('online-banner');
        online.style.display = 'block';

        setTimeout(() => {
            online.style.display = 'none';
        }, 3000);
    }
    window.addEventListener('offline', showOffline);
    window.addEventListener('online', showOnline);
</script> --}}

<div id="network-status" 
     style="position:fixed; bottom:20px; right:20px; z-index:9999; min-width:250px; display:none; border-radius:8px; padding:12px 16px; color:white; font-weight:500; box-shadow:0 4px 12px rgba(0,0,0,0.2); transition: all 0.3s ease;">
</div>

<script>
    const el = document.getElementById('network-status');

    function showMessage(message, color) {
        el.innerText = message;
        el.style.background = color;
        el.style.display = 'block';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
    }

    function hideMessage() {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            el.style.display = 'none';
        }, 300);
    }

    window.addEventListener('offline', () => {
        showMessage('⚠️ Vous êtes hors ligne. Vérifiez votre connexion Internet.', '#dc3545');
    });

    window.addEventListener('online', () => {
        showMessage('✅ Connexion rétablie', '#28a745');
        setTimeout(hideMessage, 3000);
    });
</script>