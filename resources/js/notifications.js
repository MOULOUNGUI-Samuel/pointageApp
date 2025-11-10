import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configuration de Laravel Echo avec Pusher
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    }
});

/**
 * Écouter les notifications pour l'utilisateur connecté
 */
export function listenToUserNotifications(userId) {
    if (!userId) return;

    // Écouter les notifications personnelles
    window.Echo.private(`user.${userId}`)
        .listen('.notification.created', (e) => {
            console.log('Nouvelle notification reçue:', e);
            
            // Dispatch un événement pour Livewire
            window.Livewire.dispatch('notificationReceived', e);
            
            // Afficher une notification browser (optionnel)
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(e.titre, {
                    body: e.message,
                    icon: '/images/logo.png',
                    tag: e.id
                });
            }
            
            // Jouer un son (optionnel)
            playNotificationSound();
        });
}

/**
 * Écouter les notifications pour l'entreprise
 */
export function listenToEntrepriseNotifications(entrepriseId) {
    if (!entrepriseId) return;

    window.Echo.private(`entreprise.${entrepriseId}`)
        .listen('.notification.created', (e) => {
            console.log('Notification entreprise reçue:', e);
            window.Livewire.dispatch('notificationReceived', e);
            playNotificationSound();
        });
}

/**
 * Écouter les notifications pour les admins
 */
export function listenToAdminNotifications() {
    window.Echo.private('admins.conformite')
        .listen('.notification.created', (e) => {
            console.log('Notification admin reçue:', e);
            window.Livewire.dispatch('notificationReceived', e);
            playNotificationSound();
        });
}

/**
 * Écouter les mises à jour du Kanban en temps réel
 */
export function listenToKanbanUpdates() {
    window.Echo.private('kanban.conformite')
        .listen('.soumission.updated', (e) => {
            console.log('Kanban mise à jour:', e);
            window.Livewire.dispatch('kanbanUpdated', e);
        })
        .listen('.soumission.created', (e) => {
            console.log('Nouvelle soumission:', e);
            window.Livewire.dispatch('soumissionCreated', e);
        });
}

/**
 * Jouer un son de notification
 */
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.volume = 0.3;
    audio.play().catch(e => console.log('Son de notification désactivé'));
}

/**
 * Demander la permission pour les notifications browser
 */
export function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Notifications browser activées');
            }
        });
    }
}

/**
 * Initialiser tous les listeners de notifications
 */
export function initializeNotifications(user) {
    if (!user) return;
    
    // Écouter les notifications personnelles
    if (user.id) {
        listenToUserNotifications(user.id);
    }
    
    // Écouter les notifications de l'entreprise
    if (user.entreprise_id) {
        listenToEntrepriseNotifications(user.entreprise_id);
    }
    
    // Si c'est un admin, écouter les notifications admin
    if (user.role?.nom === 'ValideAudit' || user.super_admin) {
        listenToAdminNotifications();
        listenToKanbanUpdates();
    }
    
    // Demander la permission pour les notifications browser
    requestNotificationPermission();
}

// Initialisation automatique au chargement
document.addEventListener('DOMContentLoaded', () => {
    // Récupérer l'utilisateur depuis les meta tags ou une variable globale
    const userElement = document.querySelector('meta[name="user-data"]');
    if (userElement) {
        try {
            const user = JSON.parse(userElement.getAttribute('content'));
            initializeNotifications(user);
        } catch (e) {
            console.error('Erreur lors de l\'initialisation des notifications:', e);
        }
    }
});

export default {
    listenToUserNotifications,
    listenToEntrepriseNotifications,
    listenToAdminNotifications,
    listenToKanbanUpdates,
    requestNotificationPermission,
    initializeNotifications
};

/**
 * Système de notifications pour l'application
 * Utilise SweetAlert2 pour des notifications élégantes
 */

// Fonction principale pour afficher une notification
window.notify = function(type, message, title = null) {
    const config = {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    const icons = {
        success: {
            icon: 'success',
            iconColor: '#28a745',
            title: title || 'Succès !'
        },
        error: {
            icon: 'error',
            iconColor: '#dc3545',
            title: title || 'Erreur !'
        },
        warning: {
            icon: 'warning',
            iconColor: '#ffc107',
            title: title || 'Attention !'
        },
        info: {
            icon: 'info',
            iconColor: '#17a2b8',
            title: title || 'Information'
        }
    };

    const iconConfig = icons[type] || icons.info;

    Swal.fire({
        ...config,
        ...iconConfig,
        text: message
    });
};

// Fonction pour une confirmation
window.confirmAction = function(message, title = 'Êtes-vous sûr ?', confirmText = 'Oui', cancelText = 'Annuler') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true
    });
};

// Écouter les événements Livewire pour les notifications
document.addEventListener('livewire:init', () => {
    Livewire.on('notify', (params) => {
        const data = Array.isArray(params) ? params[0] : params;
        window.notify(data.type || 'info', data.message, data.title);
    });

    Livewire.on('confirm', async (params) => {
        const data = Array.isArray(params) ? params[0] : params;
        const result = await window.confirmAction(
            data.message,
            data.title,
            data.confirmText,
            data.cancelText
        );
        
        if (result.isConfirmed && data.callback) {
            Livewire.dispatch(data.callback, data.params || {});
        }
    });
});

// Alternative simple sans SweetAlert2 (fallback)
if (typeof Swal === 'undefined') {
    console.warn('SweetAlert2 not loaded, using basic notifications');
    
    window.notify = function(type, message, title = null) {
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };

        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 p-3';
        notification.style.zIndex = '9999';
        notification.style.marginTop = '1rem';
        
        notification.innerHTML = `
            <div class="toast show" role="alert" style="min-width: 300px;">
                <div class="toast-header" style="background-color: ${colors[type]}; color: white;">
                    <strong class="me-auto">${title || type.toUpperCase()}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 4000);
    };

    window.confirmAction = function(message, title) {
        return Promise.resolve({ isConfirmed: window.confirm(title + '\n\n' + message) });
    };
}

// Helper pour les messages flash de session
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fermeture des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});