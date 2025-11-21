<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificat de Validation - {{ $item->nom_item }}</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #10b981;
            padding: 40px;
            text-align: center;
            margin: -40px -40px 40px -40px;
        }
        .header h1 {
            font-size: 32px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        .header .checkmark {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .header p {
            font-size: 16px;
            margin: 0;
            opacity: 0.95;
        }
        .certificate-box {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 3px solid #10b981;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            border-radius: 8px;
        }
        .certificate-box h2 {
            color: #059669;
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        .certificate-box p {
            margin: 10px 0;
            font-size: 14px;
            color: #065f46;
        }
        .info-section {
            margin: 30px 0;
        }
        .info-section h3 {
            color: #1f2937;
            border-bottom: 2px solid #10b981;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #6b7280;
        }
        .signature-section {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }
        .signature-box {
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-box p {
            margin: 8px 0;
            line-height: 1.6;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin: 30px 0 10px 0;
        }
        .seal {
            position: absolute;
            top: 200px;
            right: 60px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-size: 48px;
            font-weight: bold;
            opacity: 0.15;
            transform: rotate(-15deg);
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 2px solid #10b981;
            clear: both;
        }
        .footer p {
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            background: #d1fae5;
            color: #059669;
            border: 2px solid #10b981;
        }
        .notes-box {
            background: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .notes-box h4 {
            margin: 0 0 10px 0;
            color: #059669;
        }
        .notes-box p {
            margin: 0;
            line-height: 1.6;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- Filigrane VALIDÉ --}}
        <div class="seal">✓</div>

        <div class="header">
            <div class="checkmark">✓</div>
            <h1>CERTIFICAT DE VALIDATION</h1>
            <p>Déclaration de Conformité Approuvée</p>
        </div>

        <div class="certificate-box">
            <h2>Déclaration Validée avec Succès</h2>
            <p style="font-size: 16px; margin: 20px 0;">
                La présente atteste que la déclaration soumise par<br>
                <strong style="font-size: 18px;">{{ $entreprise->nom_entreprise }}</strong><br>
                a été examinée et approuvée conformément aux exigences en vigueur.
            </p>
            <div style="margin-top: 30px;">
                <span class="badge">CONFORME</span>
            </div>
        </div>

        <div class="info-section">
            <h3>Informations de l'Entreprise</h3>
            <table>
                <tr>
                    <td>Nom de l'entreprise</td>
                    <td><strong>{{ $entreprise->nom_entreprise }}</strong></td>
                </tr>
                @if($entreprise->email)
                <tr>
                    <td>Email</td>
                    <td>{{ $entreprise->email }}</td>
                </tr>
                @endif
                <tr>
                    <td>Item concerné</td>
                    <td>{{ $item->nom_item }}</td>
                </tr>
                <tr>
                    <td>Type de déclaration</td>
                    <td>{{ ucfirst($item->type) }}</td>
                </tr>
                @if($item->description)
                <tr>
                    <td>Description</td>
                    <td>{{ $item->description }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="info-section">
            <h3>Détails de la Validation</h3>
            <table>
                <tr>
                    <td>Date de soumission</td>
                    <td>{{ $submission->submitted_at->format('d/m/Y à H:i') }}</td>
                </tr>
                <tr>
                    <td>Date de validation</td>
                    <td><strong>{{ $submission->reviewed_at->format('d/m/Y à H:i') }}</strong></td>
                </tr>
                <tr>
                    <td>Validé par</td>
                    <td>
                        @if($reviewer)
                            {{ $reviewer->nom }} {{ $reviewer->prenom }}
                            @if($reviewer->email_professionnel)
                                <br><small style="color: #6b7280;">{{ $reviewer->email_professionnel }}</small>
                            @endif
                        @else
                            Équipe de validation
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Statut de la déclaration</td>
                    <td><strong style="color: #10b981; font-size: 16px;">✓ APPROUVÉ</strong></td>
                </tr>
                <tr>
                    <td>Référence du certificat</td>
                    <td><code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px;">{{ $submission->id }}</code></td>
                </tr>
            </table>
        </div>

        @if($submission->reviewer_notes)
            <div class="notes-box">
                <h4>💬 Commentaires du Validateur</h4>
                <p>{{ $submission->reviewer_notes }}</p>
            </div>
        @endif

        @if($submission->answers && $submission->answers->count() > 0)
            <div class="info-section">
                <h3>Résumé de la Soumission</h3>
                <div style="background: #f9fafb; padding: 20px; border-radius: 4px; border: 1px solid #e5e7eb;">
                    @foreach($submission->answers as $answer)
                        @if($answer->kind === 'texte' && $answer->value_text)
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #6b7280;">Texte fourni :</strong>
                                <p style="margin: 8px 0 0 0; line-height: 1.6;">{{ $answer->value_text }}</p>
                            </div>
                        @elseif($answer->kind === 'documents' && $answer->file_path)
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #6b7280;">Document :</strong>
                                <p style="margin: 8px 0 0 0;">📎 {{ basename($answer->file_path) }}</p>
                            </div>
                        @elseif($answer->kind === 'liste' && $answer->value_json)
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #6b7280;">Sélection :</strong>
                                <p style="margin: 8px 0 0 0;">
                                    {{ implode(', ', data_get($answer->value_json, 'labels', [])) }}
                                </p>
                            </div>
                        @elseif($answer->kind === 'checkbox' && $answer->value_json)
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #6b7280;">Choix :</strong>
                                <p style="margin: 8px 0 0 0;">
                                    {{ data_get($answer->value_json, 'label', '') }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="signature-section">
            <div class="signature-box">
                <p style="font-weight: bold; font-size: 14px;">
                    @if($reviewer)
                        {{ $reviewer->nom }} {{ $reviewer->prenom }}
                    @else
                        Équipe de Validation
                    @endif
                </p>
                <div class="signature-line"></div>
                <p style="font-size: 12px; color: #6b7280;">Signature électronique</p>
                <p style="font-size: 13px; margin-top: 8px;">
                    {{ $submission->reviewed_at->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }} - Certificat Officiel de Validation</strong></p>
            <p>Ce document certifie que la déclaration a été examinée et approuvée selon les procédures en vigueur.</p>
            <p style="margin-top: 10px;">
                <strong>Référence :</strong> {{ $submission->id }}<br>
                <strong>Date d'émission :</strong> {{ now()->format('d/m/Y à H:i') }}
            </p>
            <p style="margin-top: 10px; font-size: 10px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.<br>
                Ce document est authentique et a été généré automatiquement par le système.
            </p>
        </div>
    </div>
</body>
</html>