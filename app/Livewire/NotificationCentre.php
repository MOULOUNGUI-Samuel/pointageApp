<?php

namespace App\Livewire;

use App\Models\NotificationConformite;
use App\Services\NotificationConformiteService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationCentre extends Component
{
    public $showModal = false;
    public $notifications;
    public $unreadCount = 0;
    public $filter = 'all'; // all, unread, read

    /**
     * Méthode pour générer dynamiquement les listeners avec les vraies valeurs
     * Cette méthode remplace la propriété $listeners pour éviter l'erreur de placeholder
     */
    protected function getListeners()
    {
        $user = Auth::user();
        $listeners = [];

        if ($user) {
            // Écouter les notifications personnelles
            $listeners["echo-private:user.{$user->id},notification.created"] = 'onNotificationReceived';

            // Écouter les notifications de l'entreprise
            if ($user->entreprise_id) {
                $listeners["echo-private:entreprise.{$user->entreprise_id},notification.created"] = 'onNotificationReceived';
            }

            // Écouter les notifications admin si applicable
            if ($user->role && in_array($user->role->nom, ['ValideAudit', 'SuperAdmin'])) {
                $listeners["echo-private:admins.conformite,notification.created"] = 'onNotificationReceived';
            }
        }

        return $listeners;
    }

    public function mount()
    {
        $this->loadNotifications();
        $this->updateUnreadCount();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        $query = NotificationConformite::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        if ($user->entreprise_id) {
                            $q->where('entreprise_id', $user->entreprise_id)
                                ->whereNull('user_id');
                        }
                    });
            })
            ->orderBy('created_at', 'desc');

        if ($this->filter === 'unread') {
            $query->where('lue', false);
        } elseif ($this->filter === 'read') {
            $query->where('lue', true);
        }

        $this->notifications = $query->take(20)->get();
    }

    public function updateUnreadCount()
    {
        $user = Auth::user();

        $this->unreadCount = NotificationConformite::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere(function ($q) use ($user) {
                    if ($user->entreprise_id) {
                        $q->where('entreprise_id', $user->entreprise_id)
                            ->whereNull('user_id');
                    }
                });
        })
            ->where('lue', false)
            ->count();

        // Dispatch event pour mettre à jour le badge
        $this->dispatch('unreadCountUpdated', count: $this->unreadCount);
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;

        if ($this->showModal) {
            $this->loadNotifications();
        }
    }

    public function marquerCommeLue($notificationId)
    {
        $notification = NotificationConformite::find($notificationId);

        if ($notification && $this->canAccessNotification($notification)) {
            $notification->update(['lue' => true]);
            $this->loadNotifications();
            $this->updateUnreadCount();
        }
    }

    public function marquerToutesCommeLues()
    {
        $user = Auth::user();

        NotificationConformite::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere(function ($q) use ($user) {
                    if ($user->entreprise_id) {
                        $q->where('entreprise_id', $user->entreprise_id)
                            ->whereNull('user_id');
                    }
                });
        })
            ->where('lue', false)
            ->update(['lue' => true]);

        $this->loadNotifications();
        $this->updateUnreadCount();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Toutes les notifications ont été marquées comme lues'
        ]);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadNotifications();
    }

    public function onNotificationReceived($event)
    {
        // Recharger les notifications et le compteur
        $this->loadNotifications();
        $this->updateUnreadCount();

        // Afficher une notification toast
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => $event['titre'] ?? 'Nouvelle notification',
            'title' => 'Notification'
        ]);
    }

    protected function canAccessNotification(NotificationConformite $notification): bool
    {
        $user = Auth::user();

        return $notification->user_id === $user->id
            || ($notification->entreprise_id === $user->entreprise_id && !$notification->user_id);
    }

    public function render()
    {
        return view('livewire.notification-centre');
    }
}
