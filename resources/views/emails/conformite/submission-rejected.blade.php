@extends('emails.conformite.layout', [
    'headerTitle' => 'Déclaration à Corriger',
    'headerSubtitle' => 'Votre soumission nécessite des modifications'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Votre déclaration pour <strong>{{ $item->nom_item }}</strong> a été examinée par notre équipe de validation et nécessite des corrections avant d'être approuvée.
    </div>

    <div class="alert-box danger">
        <div style="text-align: center; padding: 10px 0;">
            <div style="font-size: 48px; margin-bottom: 10px;">❌</div>
            <strong style="font-size: 18px; color: #dc2626;">Corrections Nécessaires</strong>
        </div>
        <p style="margin-top: 15px; text-align: center;">
            Révisé par : <strong>{{ $reviewer ? $reviewer->nom . ' ' . $reviewer->prenom : 'L\'équipe de validation' }}</strong>
        </p>
    </div>

    @if($submission->reviewer_notes)
        <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <strong style="color: #dc2626; font-size: 16px;">📝 Commentaires du validateur</strong>
            <div style="margin-top: 15px; padding: 15px; background-color: #fff; border-radius: 4px; border: 1px solid #fecaca;">
                <p style="margin: 0; color: #991b1b; line-height: 1.6; white-space: pre-wrap;">{{ $submission->reviewer_notes }}</p>
            </div>
        </div>
    @else
        <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <strong style="color: #dc2626; font-size: 16px;">⚠️ Attention</strong>
            <p style="margin-top: 10px; color: #991b1b; margin-bottom: 0;">
                Votre soumission ne répond pas aux critères de validation. Veuillez la réviser et la soumettre à nouveau.
            </p>
        </div>
    @endif

    <table class="info-table">
        <tr>
            <td>Entreprise</td>
            <td><strong>{{ $entreprise->nom_entreprise }}</strong></td>
        </tr>
        <tr>
            <td>Item concerné</td>
            <td>{{ $item->nom_item }}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>{{ ucfirst($item->type) }}</td>
        </tr>
        @if($item->description)
        <tr>
            <td>Description</td>
            <td>{{ $item->description }}</td>
        </tr>
        @endif
        <tr>
            <td>Date de soumission</td>
            <td>{{ $submission->submitted_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <td>Date de révision</td>
            <td>{{ $submission->reviewed_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <td>Révisé par</td>
            <td>{{ $reviewer ? $reviewer->nom . ' ' . $reviewer->prenom : 'L\'équipe de validation' }}</td>
        </tr>
        <tr>
            <td>Statut</td>
            <td><strong style="color: #dc2626;">✗ REJETÉ</strong></td>
        </tr>
    </table>

    @if($submission->answers && $submission->answers->count() > 0)
        <div class="divider"></div>
        
        <div class="message">
            <strong>📋 Votre soumission initiale :</strong>
            <div style="background-color: #f9fafb; padding: 15px; margin-top: 10px; border-radius: 4px; border: 1px solid #e5e7eb;">
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

    <div class="message" style="background-color: #fff7ed; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0;">
        <strong style="color: #d97706;">🔄 Prochaines étapes</strong>
        <ol style="margin: 15px 0 0 0; padding-left: 20px; color: #78350f;">
            <li style="margin-bottom: 8px;">Lisez attentivement les commentaires du validateur</li>
            <li style="margin-bottom: 8px;">Apportez les corrections nécessaires à votre déclaration</li>
            <li style="margin-bottom: 8px;">Soumettez à nouveau votre déclaration corrigée</li>
            <li style="margin-bottom: 8px;">Une nouvelle période de 7 jours a été ouverte automatiquement</li>
        </ol>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/conformite/submit/' . $item->id . '?edit=' . $submission->id) }}" class="button" 
           style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            🔄 Modifier et Resoumettre
        </a>
    </div>

    <div class="message" style="font-size: 13px; color: #6b7280; text-align: center; margin-top: 25px;">
        <strong>💡 Besoin d'aide ?</strong>
        <p style="margin: 10px 0;">
            Si vous avez des questions concernant les corrections à apporter, n'hésitez pas à contacter l'équipe de validation à 
            <a href="mailto:{{ config('mail.from.address') }}" style="color: #0d6efd;">{{ config('mail.from.address') }}</a>
        </p>
    </div>

    <div class="message" style="background-color: #fef2f2; padding: 15px; margin-top: 25px; border-radius: 4px; font-size: 13px;">
        <strong style="color: #dc2626;">⚠️ Important :</strong>
        <ul style="margin: 10px 0; padding-left: 20px; color: #991b1b;">
            <li>Vous disposez de <strong>7 jours</strong> pour soumettre une nouvelle version corrigée</li>
            <li>Assurez-vous de bien répondre à tous les points mentionnés dans les commentaires</li>
            <li>Les documents fournis doivent être lisibles et conformes aux exigences</li>
            <li>En cas de doute, contactez-nous avant de resoumettre</li>
        </ul>
    </div>

    <div class="divider"></div>

    <div class="message" style="text-align: center; font-size: 14px; color: #6b7280;">
        <p style="margin: 0;">Nous restons à votre disposition pour vous accompagner dans votre démarche de conformité.</p>
    </div>
@endsection