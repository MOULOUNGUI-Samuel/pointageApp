<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Période de Conformité</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; }
        .header { background: #667eea; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .info-box { background: #f3f4f6; padding: 15px; margin: 20px 0; border-left: 4px solid #667eea; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        table td:first-child { font-weight: bold; width: 40%; color: #6b7280; }
        .footer { text-align: center; font-size: 12px; color: #9ca3af; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Période de Conformité</h1>
        <p>{{ config('app.name') }}</p>
    </div>
    
    <div class="content">
        <div class="info-box">
            <h2 style="margin: 0 0 10px 0;">{{ $item->nom_item }}</h2>
            <p style="margin: 0;">Entreprise : {{ $entreprise->nom_entreprise }}</p>
        </div>
        
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
                <td>Statut</td>
                <td>{{ $periode->statut == '1' ? 'Active' : 'Inactive' }}</td>
            </tr>
            <tr>
                <td>Type de document</td>
                <td>{{ ucfirst($item->type) }}</td>
            </tr>
        </table>
        
        @if($item->description)
            <h3>Description</h3>
            <p>{{ $item->description }}</p>
        @endif
        
        <div class="footer">
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>

{{-- VALIDATION PDF --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificat de Validation</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; }
        .header { background: #10b981; color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 28px; margin: 0 0 10px 0; }
        .content { padding: 40px; }
        .success-box { background: #d1fae5; border: 2px solid #10b981; padding: 20px; margin: 20px 0; text-align: center; border-radius: 8px; }
        .success-box h2 { color: #059669; margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        table td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        table td:first-child { font-weight: bold; width: 40%; color: #6b7280; }
        .signature { margin-top: 50px; text-align: right; }
        .signature p { margin: 5px 0; }
        .footer { text-align: center; font-size: 11px; color: #9ca3af; margin-top: 60px; padding-top: 20px; border-top: 2px solid #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <h1>✓ CERTIFICAT DE VALIDATION</h1>
        <p>Déclaration de Conformité Approuvée</p>
    </div>
    
    <div class="content">
        <div class="success-box">
            <h2>Déclaration Validée avec Succès</h2>
            <p>Cette déclaration a été examinée et approuvée conformément aux exigences.</p>
        </div>
        
        <h3>Informations de l'Entreprise</h3>
        <table>
            <tr>
                <td>Nom de l'entreprise</td>
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
        </table>
        
        <h3>Détails de la Validation</h3>
        <table>
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
                <td>{{ $reviewer ? $reviewer->nom . ' ' . $reviewer->prenom : 'Équipe de validation' }}</td>
            </tr>
            <tr>
                <td>Statut</td>
                <td><strong style="color: #10b981;">APPROUVÉ</strong></td>
            </tr>
        </table>
        
        @if($submission->reviewer_notes)
            <h3>Commentaires</h3>
            <p style="background: #f9fafb; padding: 15px; border-left: 4px solid #10b981;">
                {{ $submission->reviewer_notes }}
            </p>
        @endif
        
        <div class="signature">
            <p><strong>{{ $reviewer ? $reviewer->nom . ' ' . $reviewer->prenom : 'Équipe de validation' }}</strong></p>
            <p>{{ $submission->reviewed_at->format('d/m/Y') }}</p>
        </div>
        
        <div class="footer">
            <p><strong>Document Officiel de Validation</strong></p>
            <p>Référence: {{ $submission->id }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Ce document certifie l'approbation de la déclaration.</p>
        </div>
    </div>
</body>
</html>