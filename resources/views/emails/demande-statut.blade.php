<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mise à jour du statut – {{ $demande->titre }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Styles "Bootstrap-like" inlined pour compatibilité email -->
    <style>
        body { background:#f8f9fa; margin:0; padding:0; font-family:Arial,Helvetica,sans-serif; color:#212529; }
        .container { width:100%; max-width:650px; margin:20px auto; }
        .card { background:#fff; border:1px solid #dee2e6; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .card-header { background:#05426bfb; color:#fff; padding:16px 20px; font-size:18px; font-weight:700; }
        .card-body { padding:24px; }
        .muted { color:#6c757d; }
        .table { width:100%; border-collapse:collapse; margin:16px 0 8px; }
        .table td { padding:8px 0; border-top:1px solid #dee2e6; vertical-align:top; }
        .table td:first-child { width:180px; color:#6c757d; }
        .well { margin:16px 0; padding:16px; background:#f8f9fa; border:1px solid #dee2e6; border-radius:6px; }
        .badge { display:inline-block; padding:.35em .65em; font-size:.85em; font-weight:600; color:#fff; border-radius:.25rem; line-height:1; }
        .badge.en_attente { background:#6c757d; } /* secondary */
        .badge.en_cours   { background:#05426bcd; } /* info */
        .badge.traitee    { background:#198754; } /* success */
        .badge.annulee    { background:#212529; } /* dark */
        .btn { display:inline-block; text-decoration:none; padding:10px 16px; border-radius:6px; font-weight:600; }
        .btn-primary { background:#05426b; color:#fff; }
        .footer { background:#f1f3f5; color:#6c757d; padding:12px 20px; text-align:center; font-size:12px; }
        img { border:0; display:block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">

            <!-- Header avec logo -->
            <div class="card-header">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="font-weight:700;">Statut de la demande mis à jour</td>
                        
                    </tr>
                </table>
            </div>

            <!-- Corps -->
            <div class="card-body">
                <p>Bonjour,</p>
                <p>
                    La demande <strong>"{{ $demande->titre }}"</strong> a changé de statut&nbsp;:
                    <span class="badge {{ $demande->statut }}">
                        {{ ucfirst(str_replace('_',' ', $demande->statut)) }}
                    </span>
                </p>

                <table class="table" role="presentation" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>Entreprise</td>
                        <td>{{ $demande->entreprise->nom_entreprise ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Demandeur</td>
                        <td>
                            {{ $demande->user->nom ?? 'Utilisateur' }} {{ $demande->user->prenom ?? '' }}
                            @if(!empty($demande->user?->email))
                                <br><span class="muted">{{ $demande->user->email }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Date souhaitée</td>
                        <td>{{ $demande->date_souhaite?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Statut actuel</td>
                        <td>
                            <span class="badge {{ $demande->statut }}">
                                {{ ucfirst(str_replace('_',' ', $demande->statut)) }}
                            </span>
                        </td>
                    </tr>
                    @isset($demande->deadline_label)
                        <tr>
                            <td>Échéance</td>
                            <td>{{ $demande->deadline_label }}</td>
                        </tr>
                    @endisset
                </table>

                <div class="well">
                    <strong>Description :</strong><br>
                    <div style="white-space:pre-line; line-height:1.5;">
                        {{ $demande->description }}
                    </div>
                </div>

                @if(!empty($demande->piece_jointe_path))
                    <p><strong>Pièce jointe :</strong> {{ basename($demande->piece_jointe_path) }}</p>
                @endif

                {{-- Bouton vers l’app (optionnel) : passe une variable $url à la vue pour créer le lien --}}
                @isset($url)
                    <p style="margin-top:20px;">
                        <a href="{{ $url }}" class="btn btn-primary">Voir la demande</a>
                    </p>
                @endisset

                <p class="muted" style="margin-top:24px;">
                    Merci de prendre connaissance de cette mise à jour.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                © {{ date('Y') }} Nedcore – Notification automatique, merci de ne pas répondre.
            </div>

        </div>
    </div>
</body>
</html>
