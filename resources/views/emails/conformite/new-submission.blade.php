@extends('emails.conformite.layout', [
    'headerTitle' => 'Nouvelle Soumission',
    'headerSubtitle' => 'Une déclaration attend votre validation'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $admin->prenom }} {{ $admin->nom }},
    </div>

    <div class="message">
        Une nouvelle déclaration a été soumise par <strong>{{ $entreprise->nom_entreprise }}</strong> et nécessite votre validation.
    </div>

    <div class="alert-box info">
        <strong>📩 Nouvelle Soumission</strong>
        <p style="margin-top: 10px;">
            <strong>Soumise par :</strong> {{ $submitter->nom }} {{ $submitter->prenom }}<br>
            <strong>Email :</strong> {{ $submitter->email_professionnel }}
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
            <td>Statut</td>
            <td><strong style="color: #f59e0b;">En attente de validation</strong></td>
        </tr>
    </table>

    <div class="message" style="background-color: #fff7ed; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
        <strong style="color: #d97706;">⚠️ Action requise</strong>
        <p style="margin-top: 10px; color: #78350f; margin-bottom: 0;">
            Cette soumission nécessite votre validation dans les plus brefs délais pour permettre à l'entreprise de maintenir sa conformité.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="https://nedcore.net/dashboard/90f2aa85-258b-4253-8872-58c586117b9e" class="button">
            👁️ Examiner la soumission
        </a>
    </div>

    <div class="divider"></div>

    <div class="message" style="font-size: 13px; color: #6b7280;">
        <strong>💡 Rappel :</strong> Vous pouvez approuver ou rejeter cette soumission directement depuis l'interface de validation. En cas de rejet, n'oubliez pas de fournir des commentaires détaillés pour aider l'entreprise à corriger.
    </div>

    @if($submission->answers && $submission->answers->count() > 0)
        <div class="message" style="margin-top: 20px;">
            <strong>📋 Aperçu des réponses :</strong>
            <div style="background-color: #f9fafb; padding: 15px; margin-top: 10px; border-radius: 4px;">
                @php
                    $hasDocuments = false;
                @endphp
                
                @foreach($submission->answers as $answer)
                    @if($answer->kind === 'texte' && $answer->value_text)
                        <p style="margin: 5px 0;">
                            <strong>Texte :</strong> {{ \Illuminate\Support\Str::limit($answer->value_text, 100) }}
                        </p>
                    @elseif($answer->kind === 'documents' && $answer->file_path)
                        @php
                            $hasDocuments = true;
                            $fileExtension = strtoupper(pathinfo($answer->file_path, PATHINFO_EXTENSION));
                            $fileSize = '';
                            
                            // Tenter de récupérer la taille du fichier
                            if (Storage::disk('public')->exists($answer->file_path)) {
                                $fileSizeBytes = Storage::disk('public')->size($answer->file_path);
                                $fileSize = ' (' . number_format($fileSizeBytes / 1024, 2) . ' Ko)';
                            }
                        @endphp
                        <div style="margin: 10px 0; padding: 10px; background-color: #fff; border-left: 3px solid #0d6efd; border-radius: 4px;">
                            <p style="margin: 0;">
                                <strong style="color: #0d6efd;">📎 Document attaché :</strong>
                                <br>
                                <span style="color: #1f2937; font-weight: 600;">{{ basename($answer->file_path) }}</span>
                                <span style="color: #6b7280; font-size: 12px;">{{ $fileSize }}</span>
                                <br>
                                <span style="display: inline-block; margin-top: 5px; padding: 2px 8px; background-color: #e0e7ff; color: #3730a3; border-radius: 3px; font-size: 11px; font-weight: 600;">
                                    {{ $fileExtension }}
                                </span>
                            </p>
                        </div>
                    @elseif($answer->kind === 'liste' && $answer->value_json)
                        <p style="margin: 5px 0;">
                            <strong>Sélection :</strong> 
                            {{ implode(', ', data_get($answer->value_json, 'labels', [])) }}
                        </p>
                    @elseif($answer->kind === 'checkbox' && $answer->value_json)
                        <p style="margin: 5px 0;">
                            <strong>Choix :</strong> 
                            {{ data_get($answer->value_json, 'label', '') }}
                        </p>
                    @endif
                @endforeach
            </div>
            
            @if($hasDocuments)
                <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px; margin-top: 15px; border-radius: 4px;">
                    <p style="margin: 0; color: #1e40af; font-size: 13px;">
                        <strong>📥 Documents joints :</strong> Les documents soumis sont directement attachés à cet email pour faciliter votre examen. Vous pouvez les télécharger et les consulter immédiatement.
                    </p>
                </div>
            @endif
        </div>
    @endif
@endsection