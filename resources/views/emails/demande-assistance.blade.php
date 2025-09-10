<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouvelle demande d’assistance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap inline (car les clients mails bloquent les liens externes) -->
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, Helvetica, sans-serif;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            background: #05436b;
            color: #fff;
            padding: 16px;
            font-size: 18px;
            font-weight: bold;
        }

        .card-body {
            padding: 24px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table td {
            padding: 8px;
            border-top: 1px solid #dee2e6;
        }

        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            font-weight: 600;
            border-radius: 0.25rem;
            color: #fff;
        }

        .badge.en_attente {
            background: #6c757d;
        }

        .badge.en_cours {
            background: #05426bcd;
        }

        .badge.traitee {
            background: #198754;
        }

        .badge.annulee {
            background: #212529;
        }
    </style>
</head>

<body>
    <div style="max-width:650px;margin:20px auto;">
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <table width="100%">
                    <tr>
                        <td>Nouvelle demande d’assistance</td>
                        <td align="right">
                            @if ($entreprise_code === 'EZER')
                                <img src="https://nedcore.net/storage/logos/1754469978_LOGOS%20EZER%20IMMO%20-%20VALID%C3%89-05(1).png"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'YOD')
                                <img src="https://nedcore.net/storage/logos/1753783253_logo%20(3).png" alt="Logo"
                                    style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'COMKETING')
                                <img src="https://nedcore.net/storage/logos/1753112562_t%C3%A9l%C3%A9chargement.png"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'BFEV')
                                <img src="https://nedcore.net/storage/logos/1753637765_BFEV_logo_principal.jpg"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'EGCC')
                                <img src="https://nedcore.net/storage/logos/1753112626_t%C3%A9l%C3%A9chargement%20(1).png"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'NEH')
                                <img src="https://nedcore.net/storage/logos/1753112491_t%C3%A9l%C3%A9chargement.jpg"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'ING')
                                <img src="https://nedcore.net/storage/logos/1753112709_logo-ingenium-assu.png"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @elseif ($entreprise_code === 'YODI')
                                <img src="https://nedcore.net/storage/logos/1753112533_yod_logo_avec_bg1%20(1).jpg"
                                    alt="Logo" style="height:50px;border-radius:8px;background-color:#f8f9fa;padding:5px">
                            @else
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Body -->
            <div class="card-body">
                <p>Bonjour,</p>
                <p>
                    Une nouvelle demande d’assistance a été créée par
                    <strong>{{ $demande->user->nom ?? 'Utilisateur' }} {{ $demande->user->prenom ?? '' }}</strong>.
                </p>

                <table class="table">
                    <tr>
                        <td><strong>Titre</strong></td>
                        <td>{{ $demande->titre }}</td>
                    </tr>
                    <tr>
                        <td><strong>Entreprise</strong></td>
                        <td>{{ $demande->entreprise->nom_entreprise ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date souhaitée</strong></td>
                        <td>{{ $demande->date_souhaite?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Statut</strong></td>
                        <td>
                            <span class="badge {{ $demande->statut }}">
                                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div style="margin:20px 0;padding:16px;background:#f8f9fa;border:1px solid #dee2e6;border-radius:6px;">
                    <strong>Description :</strong><br>
                    {{ $demande->description }}
                </div>

                @if (!empty($demande->piece_jointe_path))
                    <p><strong>Pièce jointe :</strong> {{ basename($demande->piece_jointe_path) }}</p>
                @endif

                <p style="margin-top:20px;">Merci de traiter cette demande dans les meilleurs délais.</p>
            </div>

            <!-- Footer -->
            <div style="background:#f1f3f5;color:#6c757d;padding:12px;text-align:center;font-size:12px;">
                © {{ date('Y') }} Nedcore – Notification automatique, ne pas répondre.
            </div>
        </div>
    </div>
</body>

</html>
