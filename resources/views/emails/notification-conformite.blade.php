<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
        }

        .icone {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .titre {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }

        .message {
            font-size: 16px;
            color: #555;
            margin: 20px 0;
            line-height: 1.8;
        }

        .metadata {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .metadata-item {
            margin: 8px 0;
            font-size: 14px;
        }

        .metadata-label {
            font-weight: bold;
            color: #495057;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            margin: 20px 0;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button-urgent {
            background-color: #dc3545;
        }

        .button-urgent:hover {
            background-color: #c82333;
        }

        .button-success {
            background-color: #28a745;
        }

        .button-success:hover {
            background-color: #218838;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }

        .salutation {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="icone">{{ $icone }}</div>
            <div class="titre">{{ $titre }}</div>
        </div>

        <div class="salutation">
            Bonjour {{ $user->prenom }} {{ $user->nom }},
        </div>

        <div class="message">
            {{ $message }}
        </div>

        @if ($metadata && count($metadata) > 0)
            <div class="metadata">
                @if (isset($metadata['date_fin']))
                    <div class="metadata-item">
                        <span class="metadata-label">📅 Date limite :</span>
                        {{ \Carbon\Carbon::parse($metadata['date_fin'])->format('d/m/Y') }}
                    </div>
                @endif

                @if (isset($metadata['jours_restants']))
                    <div class="metadata-item">
                        <span class="metadata-label">⏱️ Jours restants :</span>
                        {{ $metadata['jours_restants'] }} jour(s)
                    </div>
                @endif

                @if (isset($metadata['commentaire']))
                    <div class="metadata-item">
                        <span class="metadata-label">💬 Commentaire :</span>
                        {{ $metadata['commentaire'] }}
                    </div>
                @endif

                @if (isset($metadata['validator_name']))
                    <div class="metadata-item">
                        <span class="metadata-label">👤 Validé par :</span>
                        {{ $metadata['validator_name'] }}
                    </div>
                @endif

                @if (isset($metadata['entreprise_nom']))
                    <div class="metadata-item">
                        <span class="metadata-label">🏢 Entreprise :</span>
                        {{ $metadata['entreprise_nom'] }}
                    </div>
                @endif
            </div>
        @endif

        @if ($lienAction)
            <div style="text-align: center;">
                @php
                    $buttonClass = 'button';
                    if ($notification->priorite === 'urgente' || $notification->type === 'refus') {
                        $buttonClass .= ' button-urgent';
                    } elseif ($notification->type === 'validation') {
                        $buttonClass .= ' button-success';
                    }
                @endphp
                <a href="{{ $lienAction }}" class="{{ $buttonClass }}">
                    @if ($notification->type === 'nouvelle_periode' || $notification->type === 'rappel_echeance')
                        Compléter le document
                    @elseif($notification->type === 'validation')
                        Voir les détails
                    @elseif($notification->type === 'refus')
                        Corriger le document
                    @elseif($notification->type === 'nouvelle_soumission' || $notification->type === 'resoumission')
                        Voir la soumission
                    @else
                        Voir plus
                    @endif
                </a>
            </div>
        @endif

        <div class="footer">
            <p>
                Ceci est un email automatique, merci de ne pas y répondre.<br>
                © {{ date('Y') }} {{ config('app.name') }} - Tous droits réservés
            </p>
        </div>
    </div>
</body>

</html>
