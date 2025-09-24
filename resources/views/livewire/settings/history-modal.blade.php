{{-- resources/views/livewire/settings/history-modal.blade.php --}}
<div wire:ignore.self class="modal fade" id="modalHistory-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title text-white">
            <i class="ti ti-history me-2"></i>Historique — {{ $item->nom_item }}
          </h5>
          <button type="button" class="btn-close btn-close-dark bg-light" data-bs-dismiss="modal"></button>
        </div>
  
        <div class="modal-body">
          @if($submissions->isEmpty())
            <div class="alert alert-light text-muted mb-0">
              <i class="ti ti-info-circle me-1"></i>Aucune déclaration encore enregistrée.
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                    <tr>
                      <th>Période</th>
                    <th>Soumis le</th>
                    <th>Par</th>
                    <th>Statut</th>
                    <th>Validateur</th>
                    <th>Notes</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($submissions as $s)
                    <tr>
                        <td>
                            @if($s->periode)
                              {{ $s->periode->debut_periode?->format('d/m/Y') }}
                              — {{ $s->periode->fin_periode?->format('d/m/Y') }}
                            @else
                              <span class="text-muted">—</span>
                            @endif
                        </td>
                      <td>{{ $s->submitted_at?->format('d/m/Y H:i') }}</td>
                      <td>{{ $s->submitter?->nom }} {{ $s->submitter?->prenom }}</td>
                      <td>
                        @if($s->status === 'approuvé')
                          <span class="badge bg-success"><i class="ti ti-check me-1"></i>Approuvé</span>
                        @elseif($s->status === 'rejeté')
                          <span class="badge bg-danger"><i class="ti ti-x me-1"></i>Rejeté</span>
                        @else
                          <span class="badge bg-warning"><i class="ti ti-hourglass me-1"></i>Soumis</span>
                        @endif
                      </td>
                      <td>{{ $s->reviewer?->nom }} {{ $s->reviewer?->prenom }}</td>
                      <td class="small text-muted">{{ $s->reviewer_notes }}</td>
                     
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  