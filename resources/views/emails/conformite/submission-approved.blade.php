@extends('emails.conformite.layout', [
    'headerTitle' => 'Déclaration Approuvée',
    'headerSubtitle' => 'Votre soumission a été validée avec succès'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Excellente nouvelle ! Votre déclaration pour <strong>{{ $item->nom_item }}</strong> a été approuvée par notre équipe de validation.
    </div>

    <div class="alert-box success">
        <div style="text-align: center; padding: 10px 0;">
            <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
            <strong style="font-size: 18px; color: #059669;">Déclaration Validée</strong>
        </div>
        <p style="margin-top: 15px; text-align: center;">
            Validée par : <strong>{{ $reviewerName }}</strong>
        </p>
        @if($submission->reviewer_notes)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #d1fae5;">
                <strong>💬 Commentaire du validateur :</strong>
                <p style="margin-top: 8px; color: #065f46;">{{ $submission->reviewer_notes }}</p>
            </div>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td>Entreprise</td>
            <td><strong>{{ $entreprise->nom_entreprise }}</strong></td>
        </tr>
        <tr>
            <td>Item validé</td>
            <td>{{ $item->nom_item }}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>{{ ucfirst($item->type) }}</td>
        </tr>
        <tr>
            <td>Date de soumission</td>
            <td>{{ $submission->submitted_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <td>Date de validation</td>
            <td>{{ $submission->reviewed_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <td>Validé par</td>
            <td>{{ $reviewerName }}</td>
        </tr>
        <tr>
            <td>Statut</td>
            <td><strong style="color: #10b981;">✓ APPROUVÉ</strong></td>
        </tr>
    </table>

    <div class="message" style="background-color: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 25px 0;">
        <strong style="color: #059669;">🎉 Félicitations !</strong>
        <p style="margin-top: 10px; color: #065f46; margin-bottom: 0;">
            Votre déclaration est maintenant validée et enregistrée dans notre système. Vous êtes en conformité pour cet item.
        </p>
    </div>

    @if($submission->answers && $submission->answers->count() > 0)
        <div class="divider"></div>
        
        <div class="message">
            <strong>📋 Détails de votre soumission :</strong>
            <div style="background-color: #f9fafb; padding: 15px; margin-top: 10px; border-radius: 4px;">
                @foreach($submission->answers as $answer)
                    @if($answer->kind === 'texte' && $answer->value_text)
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #6b7280;">Texte fourni :</strong>
                            <p style="margin: 5px 0; color: #1f2937;">{{ $answer->value_text }}</p>
                        </div>
                    @elseif($answer->kind === 'documents' && $answer->file_path)
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #6b7280;">Document :</strong>
                            <p style="margin: 5px 0; color: #1f2937;">
                                📎 {{ basename($answer->file_path) }}
                            </p>
                        </div>
                    @elseif($answer->kind === 'liste' && $answer->value_json)
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #6b7280;">Sélection :</strong>
                            <p style="margin: 5px 0; color: #1f2937;">
                                {{ implode(', ', data_get($answer->value_json, 'labels', [])) }}
                            </p>
                        </div>
                    @elseif($answer->kind === 'checkbox' && $answer->value_json)
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #6b7280;">Choix :</strong>
                            <p style="margin: 5px 0; color: #1f2937;">
                                {{ data_get($answer->value_json, 'label', '') }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="divider"></div>

    <div class="message" style="font-size: 14px; color: #6b7280; text-align: center;">
        <p><strong>📄 Certificat de validation</strong></p>
        <p>Un certificat de validation au format PDF est joint à cet email. Conservez-le précieusement pour vos archives.</p>
    </div>

    <div style="text-align: center; margin-top: 15px;">
        <a href="https://nedcore.net/dashboard/90f2aa85-258b-4253-8872-58c586117b9e" class="button">
            📊 Voir le tableau de bord
        </a>
    </div>

    <div class="message" style="margin-top: 15px; font-size: 13px; color: #6b7280;">
        <strong>💡 Prochaines étapes :</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>Votre déclaration est archivée et accessible dans l'historique</li>
            <li>La période de conformité pour cet item a été clôturée</li>
            <li>Vous serez notifié lorsqu'une nouvelle période sera ouverte</li>
        </ul>
    </div>
@endsection