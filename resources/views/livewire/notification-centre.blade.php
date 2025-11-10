<div>
    <!-- Bouton de notification avec badge -->
    <div class="position-relative">
        <button 
            wire:click="toggleModal" 
            class="btn btn-link position-relative p-2 text-dark"
            aria-label="Notifications"
            type="button"
        >
            <!-- Icône cloche -->
            <i class="ti ti-bell fs-20"></i>
            
            <!-- Badge compteur -->
            @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                <span class="visually-hidden">notifications non lues</span>
            </span>
            @endif
        </button>
    </div>

    <!-- Modal des notifications -->
    @if($showModal)
    <div 
        class="position-fixed top-0 end-0 bottom-0 start-0" 
        style="z-index: 1055;"
        wire:key="notification-modal"
    >
        <!-- Overlay -->
        <div 
            class="position-fixed top-0 end-0 bottom-0 start-0 bg-dark bg-opacity-50" 
            wire:click="toggleModal"
            style="backdrop-filter: blur(2px);"
        ></div>

        <!-- Panel -->
        <div class="position-fixed top-0 end-0 bottom-0 bg-white shadow-lg" 
             style="width: 100%; max-width: 480px; overflow-y: auto;">
            <div class="d-flex flex-column h-100">
                <!-- Header -->
                <div class="px-4 py-3 bg-light border-bottom sticky-top">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="ti ti-bell me-2"></i>Notifications
                            @if($unreadCount > 0)
                                <span class="badge bg-primary ms-2">{{ $unreadCount }}</span>
                            @endif
                        </h5>
                        <button 
                            wire:click="toggleModal" 
                            class="btn-close"
                            aria-label="Fermer"
                        ></button>
                    </div>

                    <!-- Filtres -->
                    <div class="btn-group btn-group-sm w-100" role="group">
                        <button 
                            wire:click="setFilter('all')"
                            class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            type="button"
                        >
                            Toutes
                        </button>
                        <button 
                            wire:click="setFilter('unread')"
                            class="btn {{ $filter === 'unread' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            type="button"
                        >
                            Non lues
                            @if($unreadCount > 0)
                                <span class="badge bg-white text-primary ms-1">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <button 
                            wire:click="setFilter('read')"
                            class="btn {{ $filter === 'read' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            type="button"
                        >
                            Lues
                        </button>
                    </div>

                    <!-- Bouton marquer toutes comme lues -->
                    @if($unreadCount > 0)
                    <div class="mt-2">
                        <button 
                            wire:click="marquerToutesCommeLues"
                            class="btn btn-sm btn-link text-decoration-none w-100"
                            type="button"
                        >
                            <i class="ti ti-checks me-1"></i>
                            Tout marquer comme lu
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Liste des notifications -->
                <div class="flex-fill overflow-auto">
                    @if($notifications && $notifications->count() > 0)
                        @foreach($notifications as $notification)
                        <div 
                            class="border-bottom {{ !$notification->lue ? 'bg-primary bg-opacity-10' : '' }}"
                            wire:key="notification-{{ $notification->id }}"
                        >
                            <div class="p-3 hover-item">
                                <div class="d-flex gap-3">
                                    <!-- Icône -->
                                    <div class="flex-shrink-0 fs-2">
                                        {!! $notification->icone !!}
                                    </div>

                                    <!-- Contenu -->
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <h6 class="mb-1 {{ !$notification->lue ? 'fw-bold' : '' }}">
                                                {{ $notification->titre }}
                                            </h6>
                                            
                                            @if(!$notification->lue)
                                            <span class="badge rounded-circle bg-primary p-1" 
                                                  style="width: 8px; height: 8px;">
                                                <span class="visually-hidden">Non lue</span>
                                            </span>
                                            @endif
                                        </div>

                                        <p class="mb-2 text-muted small">
                                            {{ $notification->message }}
                                        </p>

                                        <!-- Métadonnées -->
                                        @if($notification->metadata)
                                        <div class="mb-2">
                                            @if(isset($notification->metadata['jours_restants']))
                                                <span class="badge bg-warning-subtle text-warning border">
                                                    <i class="ti ti-clock me-1"></i>
                                                    {{ $notification->metadata['jours_restants'] }} jour(s) restant(s)
                                                </span>
                                            @endif
                                        </div>
                                        @endif

                                        <!-- Temps -->
                                        <p class="mb-2 text-muted" style="font-size: 0.75rem;">
                                            <i class="ti ti-clock-hour-4 me-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>

                                        <!-- Actions -->
                                        <div class="d-flex gap-2">
                                            @if($notification->lien_action)
                                            <a 
                                                href="{{ $notification->lien_action }}" 
                                                class="btn btn-sm btn-outline-primary"
                                                wire:click="marquerCommeLue('{{ $notification->id }}')"
                                            >
                                                <i class="ti ti-eye me-1"></i>Voir
                                            </a>
                                            @endif

                                            @if(!$notification->lue)
                                            <button 
                                                wire:click="marquerCommeLue('{{ $notification->id }}')"
                                                class="btn btn-sm btn-outline-secondary"
                                                type="button"
                                            >
                                                <i class="ti ti-check me-1"></i>Marquer comme lu
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center p-5 text-center h-100">
                            <i class="ti ti-inbox fs-1 text-muted mb-3" style="font-size: 4rem;"></i>
                            <p class="text-muted">
                                @if($filter === 'unread')
                                    Aucune notification non lue
                                @else
                                    Aucune notification
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    <style>
    .hover-item {
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .hover-item:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    </style>
    
    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // Écouter l'événement de mise à jour du compteur
            Livewire.on('unreadCountUpdated', (event) => {
                console.log('Notifications non lues:', event.count);
            });
        });
    </script>
    @endpush
</div>
