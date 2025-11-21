<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Période de Conformité - {{ $item->nom_item }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .page {
            padding: 40px;
        }
        .header {
            background:#05436b;
            color: white;
            padding: 30px;
            text-align: center;
            margin: -40px -40px 30px -40px;
        }
        .header h1 {
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        .header p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f3f4f6;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        .info-box h2 {
            color: #667eea;
            margin: 0 0 15px 0;
            font-size: 20px;
        }
        .info-box p {
            margin: 8px 0;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #6b7280;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #d1fae5;
            color: #059669;
        }
        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
        .section {
            margin: 30px 0;
        }
        .section h3 {
            color: #1f2937;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>📅 Période de Conformité</h1>
            <p>{{ config('app.name') }} - Système de Gestion de Conformité</p>
        </div>

        <div class="info-box">
            <h2>{{ $item->nom_item }}</h2>
            <p><strong>Entreprise :</strong> {{ $entreprise->nom_entreprise }}</p>
            @if($item->description)
                <p><strong>Description :</strong> {{ $item->description }}</p>
            @endif
        </div>

        <div class="section">
            <h3>Informations de la Période</h3>
            <table>
                <tr>
                    <td>Date de début</td>
                    <td>{{ $periode->debut_periode->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Date de fin</td>
                    <td>{{ $periode->fin_periode->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Durée</td>
                    <td>{{ $periode->debut_periode->diffInDays($periode->fin_periode) }} jours</td>
                </tr>
                <tr>
                    <td>Statut</td>
                    <td>
                        @if($periode->statut == '1')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Type de document</td>
                    <td>{{ ucfirst($item->type) }}</td>
                </tr>
            </table>
        </div>

        @if($item->type === 'file')
            <div class="section">
                <h3>Documents Requis</h3>
                <p style="margin: 15px 0; line-height: 1.6;">
                    <strong>Formats acceptés :</strong> PDF, JPG, JPEG, PNG, DOC, DOCX<br>
                    <strong>Taille maximale :</strong> 10 Mo par fichier<br>
                    <strong>Nombre de documents :</strong> Au moins 1 document requis
                </p>
            </div>
        @elseif($item->type === 'texte')
            <div class="section">
                <h3>Information Requise</h3>
                <p style="margin: 15px 0; line-height: 1.6;">
                    Vous devez fournir une réponse textuelle détaillée pour cet item.
                </p>
            </div>
        @elseif($item->type === 'liste' || $item->type === 'checkbox')
            <div class="section">
                <h3>Sélection Requise</h3>
                <p style="margin: 15px 0; line-height: 1.6;">
                    Vous devez sélectionner une ou plusieurs options parmi celles proposées dans le formulaire.
                </p>
            </div>
        @endif

        <div class="section">
            <h3>Instructions Importantes</h3>
            <ol style="line-height: 1.8; padding-left: 20px;">
                <li>Assurez-vous de soumettre votre déclaration avant la date de fin de période</li>
                <li>Tous les documents doivent être lisibles et conformes aux exigences</li>
                <li>En cas de rejet, vous aurez 7 jours pour apporter les corrections nécessaires</li>
                <li>Une fois approuvée, votre déclaration sera archivée dans le système</li>
            </ol>
        </div>

        <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; margin: 30px 0; border-radius: 4px;">
            <p style="margin: 0; color: #1e40af; font-weight: bold;">
                ⚠️ Important : Ce document confirme l'ouverture d'une période de conformité. 
                Veuillez soumettre votre déclaration via la plateforme avant la date limite indiquée.
            </p>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>Référence : {{ $periode->id }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>