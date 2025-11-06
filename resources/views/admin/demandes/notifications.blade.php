@extends('layouts.master2')
@section('content2')

@section('title', "Notifications - {$demande->titre}")
<div class="container mt-4">
    <h2 class="mb-4">
        Notifications – <span class="text-primary">{{ $demande->titre }}</span>
    </h2>

    <!-- Destinataires -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light fw-bold">
            Destinataires sélectionnés
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Entreprise</th>
                        <th>Type</th>
                        <th>Date sélection</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinataires as $user)
                        <tr>
                            <td>{{ $user->nom }} {{ $user->prenom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->entreprise->nom_entreprise ?? '-' }}</td>
                            <td>{{ $user->pivot->type }}</td>
                            <td>{{ $user->pivot->selected_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Aucun destinataire lié</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Journal d'envoi -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">
            Journal d’envoi des notifications
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Destinataire</th>
                        <th>Canal</th>
                        <th>Mailable</th>
                        <th>Statut</th>
                        <th>Erreur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->user->nom ?? '' }} {{ $log->user->prenom ?? '' }}<br>
                                <small class="text-muted">{{ $log->user->email }}</small>
                            </td>
                            <td>{{ strtoupper($log->channel) }}</td>
                            <td><code>{{ class_basename($log->mailable) }}</code></td>
                            <td>
                                @php
                                    $badge = match($log->status) {
                                        'queued' => 'secondary',
                                        'sent'   => 'success',
                                        'failed' => 'danger',
                                        default  => 'dark'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($log->status) }}</span>
                            </td>
                            <td>
                                @if($log->error)
                                    <small class="text-danger">{{ $log->error }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Aucun envoi enregistré</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.demandes.index') }}" class="btn btn-outline-secondary">
            ← Retour aux demandes
        </a>
    </div>
</div>
@endsection
