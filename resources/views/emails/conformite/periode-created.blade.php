@extends('emails.conformite.layout', [
    'headerTitle' => 'Nouvelle Période de Conformité',
    'headerSubtitle' => 'Une nouvelle période vient d\'être créée'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Une nouvelle période de conformité a été créée pour votre entreprise <strong>{{ $entreprise->nom_entreprise }}</strong>.
    </div>

    <div class="alert-box info">
        <strong>📅 Item concerné :</strong> {{ $item->nom_item }}
        <br>
        @if($item->description)
            <p style="margin-top: 10px; font-size: 14px;">{{ $item->description }}</p>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td>Date de début</td>
            <td><strong>{{ $dateDebut }}</strong></td>
        </tr>
        <tr>
            <td>Date de fin</td>
            <td><strong>{{ $dateFin }}</strong></td>
        </tr>
        <tr>
            <td>Durée</td>
            <td><strong>{{ $joursRestants }} jours</strong></td>
        </tr>
        <tr>
            <td>Type de document</td>
            <td>{{ ucfirst($item->type) }}</td>
        </tr>
    </table>

    <div class="message">
        Vous devez soumettre les informations requises avant le <strong>{{ $dateFin }}</strong>.
    </div>

    <div style="text-align: center;">
        <a href="https://nedcore.net/dashboard/90f2aa85-258b-4253-8872-58c586117b9e" class="button">
            📝 Soumettre maintenant
        </a>
    </div>

    <div class="message" style="font-size: 13px; color: #6b7280;">
        <strong>⚠️ Important :</strong> N'oubliez pas de soumettre votre déclaration avant la date limite pour rester en conformité.
    </div>
@endsection