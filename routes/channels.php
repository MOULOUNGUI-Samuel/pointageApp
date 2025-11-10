<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('entreprise.{entrepriseId}', function ($user, $entrepriseId) {
    return (string) $user->entreprise_id === (string) $entrepriseId;
});

// Canal privé pour un utilisateur spécifique
Broadcast::channel('user.{userId}', function (User $user, string $userId) {
    return $user->id === $userId;
});

// Canal privé pour une entreprise (tous les users de l'entreprise)
Broadcast::channel('entreprise.{entrepriseId}', function (User $user, string $entrepriseId) {
    return $user->entreprise_id === $entrepriseId;
});

// Canal privé pour les admins (rôle ValideAudit)
Broadcast::channel('admins.conformite', function (User $user) {
    return $user->role && $user->role->nom === 'ValideAudit';
});

// Canal pour le Kanban en temps réel (optionnel)
Broadcast::channel('kanban.conformite', function (User $user) {
    return $user->role && in_array($user->role->nom, ['ValideAudit', 'SuperAdmin']);
});
