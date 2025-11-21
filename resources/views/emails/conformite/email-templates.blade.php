{{-- PERIODE CANCELED --}}
@extends('emails.conformite.layout', [
    'headerTitle' => 'Annulation de Période',
    'headerSubtitle' => 'Une période a été annulée'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        La période de conformité pour <strong>{{ $item->nom_item }}</strong> a été annulée.
    </div>

    <div class="alert-box danger">
        <strong>❌ Période annulée</strong>
        @if($raison)
            <p style="margin-top: 10px;">Raison : {{ $raison }}</p>
        @endif
    </div>

    <div class="message">
        Vous n'avez plus besoin de soumettre de déclaration pour cette période.
    </div>
@endsection

{{-- SUBMISSION APPROVED --}}
@extends('emails.conformite.layout', [
    'headerTitle' => 'Déclaration Approuvée',
    'headerSubtitle' => 'Votre soumission a été validée'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Excellente nouvelle ! Votre déclaration pour <strong>{{ $item->nom_item }}</strong> a été approuvée.
    </div>

    <div class="alert-box success">
        <strong>✅ Déclaration validée</strong>
        <p style="margin-top: 10px;">Validée par : {{ $reviewerName }}</p>
        @if($submission->reviewer_notes)
            <p style="margin-top: 5px;">Commentaire : {{ $submission->reviewer_notes }}</p>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td>Date de soumission</td>
            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Date de validation</td>
            <td>{{ $submission->reviewed_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Statut</td>
            <td><strong style="color: #10b981;">Approuvé</strong></td>
        </tr>
    </table>

    <div class="message" style="font-size: 14px; color: #6b7280;">
        Un certificat de validation est joint à cet email.
    </div>
@endsection

{{-- SUBMISSION REJECTED --}}
@extends('emails.conformite.layout', [
    'headerTitle' => 'Déclaration à Corriger',
    'headerSubtitle' => 'Votre soumission nécessite des corrections'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Votre déclaration pour <strong>{{ $item->nom_item }}</strong> nécessite des corrections.
    </div>

    <div class="alert-box danger">
        <strong>❌ Corrections nécessaires</strong>
        <p style="margin-top: 10px;">Révisé par : {{ $reviewer ? $reviewer->nom . ' ' . $reviewer->prenom : 'L\'équipe de validation' }}</p>
    </div>

    @if($submission->reviewer_notes)
        <div style="background-color: #fff7ed; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
            <strong style="color: #d97706;">📝 Commentaires du validateur :</strong>
            <p style="margin-top: 10px; color: #78350f;">{{ $submission->reviewer_notes }}</p>
        </div>
    @endif

    <div class="message">
        Veuillez apporter les corrections nécessaires et soumettre à nouveau votre déclaration.
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/conformite/submit/' . $item->id . '?edit=' . $submission->id) }}" class="button">
            🔄 Modifier et resoumettre
        </a>
    </div>
@endsection

{{-- NEW SUBMISSION --}}
@extends('emails.conformite.layout', [
    'headerTitle' => 'Nouvelle Soumission',
    'headerSubtitle' => 'Une déclaration attend votre validation'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $admin->prenom }} {{ $admin->nom }},
    </div>

    <div class="message">
        Une nouvelle déclaration a été soumise par <strong>{{ $entreprise->nom_entreprise }}</strong>.
    </div>

    <div class="alert-box info">
        <strong>📩 Soumission en attente</strong>
        <p style="margin-top: 10px;">Soumise par : {{ $submitter->nom }} {{ $submitter->prenom }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td>Entreprise</td>
            <td><strong>{{ $entreprise->nom_entreprise }}</strong></td>
        </tr>
        <tr>
            <td>Item</td>
            <td>{{ $item->nom_item }}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>{{ ucfirst($item->type) }}</td>
        </tr>
        <tr>
            <td>Date de soumission</td>
            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div style="text-align: center;">
        <a href="{{ url('/conformite/review/' . $submission->id) }}" class="button">
            👁️ Examiner la soumission
        </a>
    </div>
@endsection

{{-- RAPPEL ECHEANCE --}}
@extends('emails.conformite.layout', [
    'headerTitle' => 'Rappel d\'Échéance',
    'headerSubtitle' => 'Une déclaration doit être soumise'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    @if($joursRestants === 0)
        <div class="alert-box danger">
            <strong style="font-size: 16px;">🚨 URGENT : Échéance dans 1 heure !</strong>
            <p style="margin-top: 10px;">La période pour {{ $item->nom_item }} expire aujourd'hui.</p>
        </div>
    @elseif($joursRestants === 1)
        <div class="alert-box" style="border-color: {{ $couleurUrgence }}; background-color: #fef2f2;">
            <strong style="font-size: 16px; color: {{ $couleurUrgence }};">⚠️ Échéance demain !</strong>
            <p style="margin-top: 10px;">Il vous reste 1 jour pour soumettre votre déclaration.</p>
        </div>
    @else
        <div class="alert-box warning">
            <strong>⏰ Rappel : {{ $joursRestants }} jours restants</strong>
            <p style="margin-top: 10px;">N'oubliez pas de soumettre votre déclaration.</p>
        </div>
    @endif

    <div class="message">
        La déclaration pour <strong>{{ $item->nom_item }}</strong> doit être soumise avant le <strong>{{ $periode->fin_periode->format('d/m/Y à H:i') }}</strong>.
    </div>

    <table class="info-table">
        <tr>
            <td>Item</td>
            <td>{{ $item->nom_item }}</td>
        </tr>
        <tr>
            <td>Échéance</td>
            <td><strong style="color: {{ $couleurUrgence }};">{{ $periode->fin_periode->format('d/m/Y H:i') }}</strong></td>
        </tr>
        <tr>
            <td>Temps restant</td>
            <td>
                @if($joursRestants === 0)
                    <strong style="color: #dc2626;">1 heure</strong>
                @else
                    <strong style="color: {{ $couleurUrgence }};">{{ $joursRestants }} jour(s)</strong>
                @endif
            </td>
        </tr>
    </table>

    <div style="text-align: center;">
        <a href="{{ url('/conformite/submit/' . $item->id) }}" class="button" style="background: {{ $couleurUrgence }};">
            ⚡ Soumettre maintenant
        </a>
    </div>

    <div class="message" style="font-size: 13px; color: #ef4444; font-weight: 500; margin-top: 20px;">
        ⚠️ Attention : Toute soumission tardive peut entraîner des pénalités.
    </div>
@endsection