<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Historique du contrat</h4>
        </div>

        <div class="card-body">
            @if($histories->isEmpty())
                <div class="text-center py-4">
                    <i class="ti ti-history text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Aucun historique disponible</p>
                </div>
            @else
                <div class="timeline">
                    @foreach($histories as $history)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <span class="badge {{ $history->action_badge }}">
                                    @if($history->action === 'created')
                                        <i class="ti ti-plus"></i>
                                    @elseif($history->action === 'updated')
                                        <i class="ti ti-edit"></i>
                                    @elseif($history->action === 'renewed')
                                        <i class="ti ti-refresh"></i>
                                    @elseif($history->action === 'suspended')
                                        <i class="ti ti-alert-triangle"></i>
                                    @elseif($history->action === 'terminated')
                                        <i class="ti ti-x"></i>
                                    @elseif($history->action === 'reactivated')
                                        <i class="ti ti-check"></i>
                                    @else
                                        <i class="ti ti-file"></i>
                                    @endif
                                </span>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $history->action_label }}</h6>
                                        <small class="text-muted">
                                            <i class="ti ti-user me-1"></i>
                                            {{ $history->modifiedBy->prenom ?? '' }} {{ $history->modifiedBy->nom ?? '' }}
                                            <i class="ti ti-calendar ms-2 me-1"></i>
                                            {{ $history->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>

                                @if($history->comment)
                                    <p class="mb-2"><strong>Commentaire:</strong> {{ $history->comment }}</p>
                                @endif

                                @if($history->changes && count($history->changes) > 0)
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Champ</th>
                                                    <th>Avant</th>
                                                    <th>Après</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($history->changes as $field => $change)
                                                    @if(is_array($change) && isset($change['before'], $change['after']))
                                                        <tr>
                                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                                                            <td>
                                                                @if(is_null($change['before']))
                                                                    <span class="text-muted">-</span>
                                                                @else
                                                                    {{ is_array($change['before']) ? json_encode($change['before']) : $change['before'] }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(is_null($change['after']))
                                                                    <span class="text-muted">-</span>
                                                                @else
                                                                    <strong class="text-primary">
                                                                        {{ is_array($change['after']) ? json_encode($change['after']) : $change['after'] }}
                                                                    </strong>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 50px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-left: 2px solid #e9ecef;
        }

        .timeline-item:last-child {
            border-left: none;
        }

        .timeline-marker {
            position: absolute;
            left: -28px;
            top: 0;
        }

        .timeline-marker .badge {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
        }

        .timeline-content {
            padding-left: 20px;
            padding-top: 5px;
        }
    </style>
</div>
