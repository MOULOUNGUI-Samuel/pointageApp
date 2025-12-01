@props(['item'])

@if(app()->environment('local') && isset($item->debugConformiteStatus))
    @php
        $debug = $item->debugConformiteStatus;
        $colorClass = match($debug['color']) {
            'rouge' => 'danger',
            'vert' => 'success',
            'jaune' => 'warning',
            'gris' => 'secondary',
            default => 'info',
        };
    @endphp

    <div class="mt-2 p-2 bg-{{ $colorClass }} bg-opacity-10 border border-{{ $colorClass }} border-dashed rounded-2"
         style="font-size: 0.75rem;">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="ti ti-bug"></i>
            <strong>DEBUG MODE</strong>
        </div>
        <div class="text-muted">
            <div><strong>Status:</strong> {{ $debug['status'] }}</div>
            <div><strong>Label:</strong> {{ $debug['label'] }}</div>
            <div><strong>Couleur:</strong> {{ $debug['color'] }}</div>
            <div><strong>Raison:</strong> {{ $debug['reason'] }}</div>
            <div><strong>Période active:</strong> {{ $item->hasActivePeriode ? 'Oui' : 'Non' }}</div>
            <div><strong>Dernière soumission:</strong>
                @if($item->lastSubmission)
                    {{ $item->lastSubmission->status }} ({{ $item->lastSubmission->submitted_at->format('d/m/Y') }})
                @else
                    Aucune
                @endif
            </div>
        </div>
    </div>
@endif
