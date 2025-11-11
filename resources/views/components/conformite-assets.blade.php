{{--
    Composant Blade pour inclure les dépendances nécessaires au système de conformité
    À inclure dans votre layout principal
--}}

@once
    @push('styles')
        {{-- Tabler Icons --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
        
        {{-- SweetAlert2 --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        
        {{-- Styles personnalisés --}}
        <style>
            /* Animations personnalisées */
            .fade-in {
                animation: fadeIn 0.3s ease-in;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Amélioration des cartes */
            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }

            /* Badges personnalisés */
            .badge {
                font-weight: 500;
                padding: 0.35em 0.65em;
            }

            /* Boutons avec loading state */
            .btn[wire\\:loading\\.attr="disabled"] {
                position: relative;
            }

            /* Custom close button pour les modales */
            .btn-close-white {
                filter: brightness(0) invert(1);
            }

            /* Amélioration des alertes */
            .alert {
                border: none;
                border-left: 4px solid;
            }

            .alert-success {
                border-left-color: #28a745;
            }

            .alert-danger {
                border-left-color: #dc3545;
            }

            .alert-warning {
                border-left-color: #ffc107;
            }

            .alert-info {
                border-left-color: #17a2b8;
            }

            /* Scrollbar personnalisée */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            /* Amélioration des modales */
            .modal-content {
                border: none;
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            }

            /* Loading state global */
            [wire\\:loading] {
                opacity: 0.6;
                pointer-events: none;
            }

            /* Status badges */
            .badge.bg-success-subtle {
                background-color: rgba(40, 167, 69, 0.1) !important;
            }

            .badge.bg-danger-subtle {
                background-color: rgba(220, 53, 69, 0.1) !important;
            }

            .badge.bg-warning-subtle {
                background-color: rgba(255, 193, 7, 0.1) !important;
            }

            .badge.bg-info-subtle {
                background-color: rgba(23, 162, 184, 0.1) !important;
            }

            .badge.bg-primary-subtle {
                background-color: rgba(13, 110, 253, 0.1) !important;
            }

            .badge.bg-secondary-subtle {
                background-color: rgba(108, 117, 125, 0.1) !important;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- Script d'initialisation --}}
        <script>
            // Configuration globale Livewire
            document.addEventListener('livewire:init', () => {
                // Afficher un spinner pendant les requêtes
                Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
                    // Vous pouvez ajouter un spinner global ici si nécessaire
                });

                // Rafraîchir après certaines actions
                Livewire.on('refresh-board', () => {
                    Livewire.dispatch('$refresh');
                });
            });

            // Helper pour fermer toutes les modales
            window.closeAllModals = function() {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                });
            };

            // Helper pour ouvrir une modale spécifique
            window.openModal = function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                }
            };
        </script>
    @endpush
@endonce