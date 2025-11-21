{{-- PERIODE MODIFIED --}}
@extends('emails.conformite.layout', [
    'headerTitle' => 'Modification de Période',
    'headerSubtitle' => 'Une période a été modifiée'
])

@section('content')
    <div class="greeting">
        Bonjour {{ $user->prenom }} {{ $user->nom }},
    </div>

    <div class="message">
        Une période de conformité pour <strong>{{ $item->nom_item }}</strong> a été modifiée.
    </div>

    <table class="info-table">
        <tr>
            <td>Date de début</td>
            <td><strong>{{ $dateDebut }}</strong></td>
        </tr>
        <tr>
            <td>Date de fin</td>
            <td><strong>{{ $dateFin }}</strong></td>
        </tr>
    </table>

    @if(!empty($changes))
        <div class="alert-box warning">
            <strong>Modifications apportées :</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                @foreach($changes as $field => $change)
                    <li>{{ $field }} : {{ $change }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="text-align: center;">
        <a href="https://nedcore.net/dashboard/90f2aa85-258b-4253-8872-58c586117b9e" class="button">
            📝 Voir la période
        </a>
    </div>
@endsection