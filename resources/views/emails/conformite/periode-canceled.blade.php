@extends('emails.conformite.layout', [
    'headerTitle' => 'Période Clôturée',
    'headerSubtitle' => 'Une période de conformité a été fermée',
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Nous vous informons que la période de conformité pour <strong>{{ $item->nom_item }}</strong> a été clôturée par
        l'administration.
    </div>

    <div class="alert-box warning">
        <div style="text-align: center; padding: 10px 0;">
            <div style="font-size: 48px; margin-bottom: 10px;">🔒</div>
            <strong style="font-size: 18px; color: #d97706;">Période Clôturée</strong>
        </div>
        <p style="margin-top: 15px; text-align: center; color: #78350f;">
            Cette période a été fermée le {{ $periode->updated_at->format('d/m/Y à H:i') }}
        </p>
    </div>

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
        @if ($item->description)
            <tr>
                <td>Description</td>
                <td>{{ $item->description }}</td>
            </tr>
        @endif
        <tr>
            <td>Période initialement prévue</td>
            <td>
                Du {{ $periode->debut_periode->format('d/m/Y') }}
                au {{ $periode->fin_periode->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td>Date de clôture</td>
            <td>{{ $periode->updated_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <td>Statut</td>
            <td><strong style="color: #d97706;">🔒 CLÔTURÉE</strong></td>
        </tr>
    </table>

    @if (isset($reason) && $reason)
        <div
            style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <strong style="color: #d97706; font-size: 16px;">📝 Motif de la clôture</strong>
            <div
                style="margin-top: 15px; padding: 15px; background-color: #fff; border-radius: 4px; border: 1px solid #fde68a;">
                <p style="margin: 0; color: #92400e; line-height: 1.6; white-space: pre-wrap;">{{ $reason }}</p>
            </div>
        </div>
    @endif

    <div class="message" style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0;">
        <strong style="color: #d97706;">⚠️ Conséquences</strong>
        <ul style="margin: 15px 0 0 0; padding-left: 20px; color: #78350f;">
            <li style="margin-bottom: 8px;">Il n'est <strong>plus possible</strong> de soumettre de déclaration pour cette
                période</li>
            <li style="margin-bottom: 8px;">Les soumissions en attente de validation ont été <strong>annulées</strong></li>
            <li style="margin-bottom: 8px;">Cette période n'apparaîtra plus comme active dans votre tableau de bord</li>
            <li style="margin-bottom: 8px;">Une nouvelle période pourra être ouverte ultérieurement</li>
        </ul>
    </div>

    @if (isset($hasSubmission) && $hasSubmission)
        <div class="divider"></div>

        <div class="message">
            <strong>📊 État de votre soumission</strong>

            @if (isset($submissionStatus))
                @if ($submissionStatus === 'approuvé')
                    <div
                        style="background-color: #d1fae5; padding: 15px; margin-top: 15px; border-radius: 4px; border: 1px solid #10b981;">
                        <p style="margin: 0; color: #065f46;">
                            <strong>✅ Bonne nouvelle :</strong> Votre déclaration avait déjà été approuvée avant la clôture
                            de la période. Votre conformité est donc validée.
                        </p>
                    </div>
                @elseif($submissionStatus === 'soumis')
                    <div
                        style="background-color: #fee2e2; padding: 15px; margin-top: 15px; border-radius: 4px; border: 1px solid #ef4444;">
                        <p style="margin: 0; color: #991b1b;">
                            <strong>⚠️ Attention :</strong> Votre déclaration était en attente de validation. Suite à la
                            clôture de la période, elle a été automatiquement annulée.
                        </p>
                    </div>
                @elseif($submissionStatus === 'rejeté')
                    <div
                        style="background-color: #fef3c7; padding: 15px; margin-top: 15px; border-radius: 4px; border: 1px solid #f59e0b;">
                        <p style="margin: 0; color: #78350f;">
                            <strong>ℹ️ Information :</strong> Votre déclaration avait été rejetée. La clôture de la période
                            signifie que vous ne pourrez plus la resoumettre pour cette période.
                        </p>
                    </div>
                @endif
            @else
                <div
                    style="background-color: #f3f4f6; padding: 15px; margin-top: 15px; border-radius: 4px; border: 1px solid #d1d5db;">
                    <p style="margin: 0; color: #4b5563;">
                        <strong>ℹ️ Information :</strong> Vous n'aviez pas soumis de déclaration pour cette période avant sa
                        clôture.
                    </p>
                </div>
            @endif
        </div>
    @endif

    <div class="divider"></div>

    <div class="message" style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; margin: 25px 0;">
        <strong style="color: #1e40af;">🔄 Prochaines étapes</strong>
        <ol style="margin: 15px 0 0 0; padding-left: 20px; color: #1e3a8a;">
            <li style="margin-bottom: 8px;">Consultez votre tableau de bord pour voir les autres périodes actives</li>
            <li style="margin-bottom: 8px;">Vous serez notifié par email lors de l'ouverture d'une nouvelle période</li>
            <li style="margin-bottom: 8px;">En cas de questions, contactez l'équipe d'administration</li>
        </ol>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="https://nedcore.net/dashboard/90f2aa85-258b-4253-8872-58c586117b9e" class="button">
            📊 Voir le Tableau de Bord
        </a>
    </div>

    <div class="message" style="font-size: 13px; color: #6b7280; text-align: center; margin-top: 25px;">
        <strong>💡 Besoin d'informations ?</strong>
        <p style="margin: 10px 0;">
            Pour toute question concernant cette clôture, n'hésitez pas à contacter l'équipe d'administration à
            <a href="mailto:{{ config('mail.from.address') }}"
                style="color: #0d6efd;">{{ config('mail.from.address') }}</a>
        </p>
    </div>

    <div class="message"
        style="background-color: #f9fafb; padding: 15px; margin-top: 25px; border-radius: 4px; font-size: 13px; text-align: center;">
        <p style="margin: 0; color: #6b7280;">
            Cette notification a été envoyée automatiquement suite à la clôture de la période.<br>
            Référence : {{ $periode->id }}
        </p>
    </div>
@endsection
