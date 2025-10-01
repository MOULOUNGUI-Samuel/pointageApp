@extends('layouts.master2')
@section('content2')
    <style>
        :root {
            --primary-color: #05436b;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --dark-bg: #1f2937;
            --dark-card: #374151;
            --dark-text: #f9fafb;
        }

        .dark .border-gray {
            border-color: #4b5563 !important;
        }

        .dark .text-gray-600 {
            color: #434343 !important;
        }

        .dark .text-primary {
            color: var(--dark-text) !important;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: var(--primary-color);
        }

        .tab-active {
            background: var(--primary-color);
            color: white;
        }

        .variable-gain {
            background: linear-gradient(45deg, #dcfce7, #bbf7d0);
            border-left: 4px solid var(--success-color);
        }

        .variable-deduction {
            background: linear-gradient(45deg, #fef2f2, #fecaca);
            border-left: 4px solid var(--error-color);
        }

        .employee-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .employee-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
        }

        .employee-card.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }

        /* Le modal Bootstrap est à z-index: 1055, on passe au-dessus */
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 99999;
            padding: .75rem 1rem;
            border-radius: .5rem;
            color: #fff;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
            animation: slideIn 0.3s ease-out;
        }

        .notification.bg-green-500 {
            background: #16a34a
        }

        .notification.bg-red-500 {
            background: #ef4444
        }

        .notification.bg-blue-500 {
            background: #3b82f6
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .chart-container {
            height: 400px;
            width: 100%;
        }

        @media print {
            .no-print {
                display: none !important;
            }


        }

        /* Scrollbar plus épaisse et stylée (Chrome/Edge/Safari) */
        .payrollScroll::-webkit-scrollbar {
            height: 14px;
            /* ÉPAISSEUR de la barre horizontale */
        }

        .payrollScroll::-webkit-scrollbar-track {
            background: #e5e7eb;
            /* gris-200 */
        }

        .payrollScroll::-webkit-scrollbar-thumb {
            background: #9ca3af;
            /* gris-400 */
            border-radius: 9999px;
            border: 3px solid #e5e7eb;
            /* crée un effet "pilule" */
        }

        .payrollScroll:hover::-webkit-scrollbar-thumb {
            background: #6b7280;
            /* gris-500 au survol */
        }

        /* Firefox : largeur par défaut (plus visible que 'thin') + couleurs */
        .payrollScroll {
            scrollbar-width: auto;
            scrollbar-color: #9ca3af #e5e7eb;
            /* thumb / track */
        }
    </style>
    <!-- Header -->
    <header class="bg-primary rounded shadow">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-calculator text-white text-2xl"></i>
                    <h1 class="text-2xl font-bold text-white">Système de Gestion de Paie Complet</h1>
                </div>

                <div class="flex items-center space-x-4 no-print">
                    <button onclick="saveData()"
                        class="bg-dark text-white px-4 py-2 rounded-lg hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-save mr-2"></i>Sauvegarder
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid mx-auto px-6 py-6 space-y-6">
        <!-- Navigation Tabs -->
        <div class="bg-white rounded-xl shadow-lg p-6 no-print">
            <div class="flex flex-wrap gap-2 mb-6">
                <button onclick="switchTab('periode')" id="tab-periode"
                    class="px-4 py-2 rounded-lg font-medium transition-colors tab-active">
                    <i class="fas fa-calendar mr-2"></i>Période
                </button>
                <button onclick="switchTab('employes')" id="tab-employes"
                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-gray-100">
                    <i class="fas fa-users mr-2"></i>Employés
                </button>
                <button onclick="switchTab('variables')" id="tab-variables"
                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-gray-100">
                    <i class="fas fa-list mr-2"></i>Variables
                </button>
                <button onclick="switchTab('saisie-globale')" id="tab-saisie-globale"
                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-gray-100">
                    <i class="fas fa-table mr-2"></i>Saisie Globale
                </button>
                <button onclick="switchTab('saisie-detaillee')" id="tab-saisie-detaillee"
                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-gray-100">
                    <i class="fas fa-user-edit mr-2"></i>Saisie Détaillée
                </button>
                <button onclick="switchTab('synthese')" id="tab-synthese"
                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-gray-100">
                    <i class="fas fa-chart-bar mr-2"></i>Synthèse
                </button>
                <div>
                    <select id="replaceTicket" class="form-select shadow">
                        <option value="">Choix du ticket</option>
                        {{-- tu peux pré-remplir côté Blade si tu veux --}}
                    </select>
                </div>
                @if (session('success'))
                    <div class="alert alert-success rounded-pill alert-dismissible fade show">
                        <strong class="me-5"><i class="fas fa-check me-2"></i> {{ session('success') }}</strong>
                        <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"><i
                                class="fas fa-xmark"></i></button>
                    </div>
                @endif

            </div>
        </div>

        <!-- Tab: Période de Paie -->
        <div id="content-periode" class="tab-content animate-fade-in">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-primary mb-6 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 "></i>Configuration de la Période de Paie
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                        <input type="date" id="periodStart"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                        <input type="date" id="periodEnd"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ticket généré</label>
                        <input type="text" id="periodTicket" class="w-full px-4 py-3 border rounded-lg input-focus"
                            readonly placeholder="Ticket (auto)">
                    </div>

                </div>
                <script>
                    (function() {
                        const startEl = document.getElementById('periodStart');
                        const endEl = document.getElementById('periodEnd');
                        const ticketEl = document.getElementById('periodTicket');

                        function toJJMMAA(iso) {
                            if (!iso) return '';
                            const [y, m, d] = iso.split('-');
                            return `${d}${m}${y.slice(2)}`;
                        }

                        function previewTicket() {
                            const s = startEl?.value,
                                e = endEl?.value;
                            if (ticketEl) ticketEl.value = (s && e) ? `Tk-${toJJMMAA(s)}-${toJJMMAA(e)}` : '';
                        }

                        startEl?.addEventListener('change', previewTicket);
                        endEl?.addEventListener('change', previewTicket);
                        previewTicket(); // init au chargement
                    })();
                </script>
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-2">Instructions</h3>
                    <p class="text-blue-700 text-sm">Configurez la période de paie avant de commencer la saisie des données.
                        Cette période sera utilisée pour tous les calculs et exports.</p>
                </div>
            </div>
        </div>

        <!-- Tab: Gestion des Employés -->
        <div id="content-employes" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-primary flex items-center">
                            <i class="fas fa-users mr-2 "></i>Gestion des Employés
                        </h2>
                        <div class="flex gap-2 no-print">
                            <button onclick="showAddEmployeeModal()"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Ajouter Employé
                            </button>
                            <button onclick="importEmployees()"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-file-excel mr-2"></i>Importer en pdf
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 no-print">
                        <input type="text" id="searchEmployee" placeholder="Rechercher un employé..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                    </div>
                </div>
                <div class="payrollScroll" style="max-height: 450px; overflow-y: auto;">
                    <table id="example2" class="w-full table table-striped table-bordered">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Matricule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom & Prénom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Poste</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Salaire de Base</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider no-print">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeesTableBody" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
                <script>
                    (function() {
                        const input = document.getElementById('searchEmployee');
                        const tbody = document.getElementById('employeesTableBody');

                        // Normalise le texte: minuscules + suppression des accents
                        const norm = (s) =>
                            (s || '')
                            .toString()
                            .toLowerCase()
                            .normalize('NFD')
                            .replace(/[\u0300-\u036f]/g, '')
                            .trim();

                        input.addEventListener('input', function() {
                            const q = norm(this.value);
                            const rows = tbody.querySelectorAll('tr');

                            rows.forEach((tr) => {
                                const text = norm(tr.innerText); // filtre sur tout le contenu de la ligne
                                tr.style.display = !q || text.includes(q) ? '' : 'none';
                            });
                        });
                    })();
                </script>

            </div>
        </div>

        <!-- Tab: Variables de Paie -->
        <div id="content-variables" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-primary flex items-center">
                            <i class="fas fa-list mr-2 "></i>Variables de Paie
                        </h2>
                        <div class="flex gap-2 no-print">
                            {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#floatingLabelsModal"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                                <i class="fas fa-plus mr-2"></i>Ajouter Variable
                            </button> --}}
                            <div class="">
                                <button class="btn btn-primary" onclick="openCreateVariable()"><i
                                        class="fas fa-plus mr-2"></i> Nouvelle variable</button>
                            </div>
                            <button data-bs-toggle="modal" data-bs-target="#floatingLabelsModalCategori"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                                <i class="fas fa-plus mr-2"></i>Ajouter une categorie
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="variablesContainer">
                    </div>
                    @livewire('variables-manager')
                </div>
            </div>
        </div>
        @include('components.yodirh._categorie')
        {{-- @include('components.yodirh._variable', $categories) --}}
        <!-- Tab: Saisie Globale -->
        <div id="content-saisie-globale" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <div class="flex">
                            <h2 class="text-xl font-semibold text-primary flex items-center">
                                <i class="fas fa-table mr-2 "></i>Saisie Globale des Variables
                            </h2>
                            <!-- Champ de recherche -->
                            <div class="mt-4 mb-2 no-print">
                                <input type="text" id="searchPayroll"
                                    placeholder="Rechercher (nom, prénom, montants, etc.)…"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus shadow" style="width: 500px; margin-left: 20px;">
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 no-print">
                            <button onclick="calculateAll()"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-calculator mr-2"></i>Calculer Tout
                            </button>
                            <button onclick="resetAllInputs()"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                            {{-- <a href="{{ route('ficheDePaieDemo') }}"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Fiche de paie
                            </a> --}}

                            {{-- <a href="{{ route('payrollTablePdf', ['ticket' => 'Tk-180825-310825']) }}"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-file-excel mr-2"></i>Importer en pdf
                             </a> --}}

                        </div>

                    </div>
                </div>



                <div class="payrollScroll overflow-x-auto" style="max-height: 450px; overflow-y: auto;">
                    <table class="w-full" id="payrollTable">
                        <thead class="bg-gray-50" id="payrollTableHead"></thead>
                        <tbody id="payrollTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>

                <script>
                    (function() {
                        const input = document.getElementById('searchPayroll');
                        const tbody = document.getElementById('payrollTableBody');

                        // Normalise le texte: minuscules + suppression des accents
                        const norm = (s) =>
                            (s || '')
                            .toString()
                            .toLowerCase()
                            .normalize('NFD')
                            .replace(/[\u0300-\u036f]/g, '')
                            .trim();

                        // Récupère le texte d'une ligne : contenu + valeurs des champs
                        const rowText = (tr) => {
                            let txt = tr.textContent || '';
                            tr.querySelectorAll('input, select, textarea').forEach(el => {
                                const val = (el.type === 'checkbox') ?
                                    (el.checked ? '1 true oui checked' : '0 false non') :
                                    (el.value || el.textContent || '');
                                txt += ' ' + val;
                            });
                            return norm(txt);
                        };

                        // Debounce léger pour la saisie
                        let t = null;
                        input.addEventListener('input', function() {
                            clearTimeout(t);
                            t = setTimeout(() => {
                                const q = norm(this.value);
                                const rows = tbody.querySelectorAll('tr');

                                rows.forEach((tr) => {
                                    const text = rowText(tr);
                                    tr.style.display = !q || text.includes(q) ? '' : 'none';
                                });
                            }, 80);
                        });
                    })();
                </script>

            </div>
        </div>

        <!-- Tab: Saisie Détaillée -->
        <div id="content-saisie-detaillee" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-primary mb-6 flex items-center">
                    <i class="fas fa-user-edit mr-2 "></i>Saisie Détaillée par Employé
                </h2>

                <!-- Sélection d'employé -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Sélectionner un Employé</h3>
                    <div id="employeeSelector" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    </div>
                </div>

                <!-- Détail employé sélectionné -->
                <div id="employeeDetail" class="hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-primary">
                                <i class="fas fa-user  mr-2"></i>
                                <span id="selectedEmployeeName"></span>
                            </h3>
                            <div class="flex space-x-3 no-print">
                                <button id="prevEmployeeBtn"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button id="nextEmployeeBtn"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-600">Matricule:</span>
                                <span id="selectedEmployeeId" class="ml-2 text-primary"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Salaire de Base:</span>
                                <span id="selectedEmployeeSalary" class="ml-2 text-primary font-semibold"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Service:</span>
                                <span id="selectedEmployeeService" class="ml-2 text-primary"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire des variables -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Gains -->
                        <div class="bg-white border border-gray rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-green-700 mb-4 flex items-center">
                                <i class="fas fa-plus-circle mr-2"></i>Gains et Primes
                            </h4>
                            <div id="gainsForm" class="space-y-4">
                            </div>
                            <div class="mt-4 pt-4 border-t border-green-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-700">Total Gains:</span>
                                    <span id="totalGains" class="text-xl font-bold text-green-600">0 F CFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Retenues -->
                        <div class="bg-white border border-gray rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-red-700 mb-4 flex items-center">
                                <i class="fas fa-minus-circle mr-2"></i>Retenues et Déductions
                            </h4>
                            <div id="deductionsForm" class="space-y-4">
                            </div>
                            <div class="mt-4 pt-4 border-t border-red-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-700">Total Retenues:</span>
                                    <span id="totalDeductions" class="text-xl font-bold text-red-600">0 F CFA</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Récapitulatif employé -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 mt-6">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4">
                            <i class="fas fa-calculator mr-2"></i>Récapitulatif Employé
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-sm text-gray-600">Salaire de Base</div>
                                <div id="recapBaseSalary" class="text-lg font-bold text-primary">0 F CFA</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600">+ Gains</div>
                                <div id="recapGains" class="text-lg font-bold text-green-600">0 F CFA</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600">- Retenues</div>
                                <div id="recapDeductions" class="text-lg font-bold text-red-600">0 F CFA</div>
                            </div>
                            <div class="text-center bg-white rounded-lg p-3 border-2 border-blue-300">
                                <div class="text-sm text-gray-600">Salaire Net</div>
                                <div id="recapNetSalary" class="text-xl font-bold text-blue-800">0 F CFA</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Synthèse -->
        <div id="content-synthese" class="tab-content hidden">
            <div class="space-y-6">
                <!-- Indicateurs principaux -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-primary mb-6 flex items-center">
                        <i class="fas fa-chart-bar mr-2 "></i>Synthèse de la Paie
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-r bg-primary rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">Masse Salariale de Base</p>
                                    <p class="text-2xl font-bold" id="summaryBaseSalary">0 F CFA</p>
                                </div>
                                <i class="fas fa-money-bill-wave text-3xl text-blue-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r bg-success rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm">Total Gains Variables</p>
                                    <p class="text-2xl font-bold" id="summaryVariableGains">0 F CFA</p>
                                </div>
                                <i class="fas fa-arrow-up text-3xl text-green-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r bg-danger rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-100 text-sm">Total Retenues</p>
                                    <p class="text-2xl font-bold" id="summaryTotalDeductions">0 F CFA</p>
                                </div>
                                <i class="fas fa-arrow-down text-3xl text-red-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r bg-dark rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm">Salaire Net Total</p>
                                    <p class="text-2xl font-bold" id="summaryNetSalary">0 F CFA</p>
                                </div>
                                <i class="fas fa-wallet text-3xl text-purple-200"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4">Répartition de la Masse Salariale</h3>
                        <div class="chart-container">
                            <canvas id="salaryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Détail par employé -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="d-flex items-center justify-between">
                        <div class="d-flex">
                            <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                                <i class="fas fa-users mr-2 "></i>Détail par Employé
                            </h3>
                            <div class="mb-2 no-print">
                                <input type="text" id="searchEmployeeDetails"
                                    placeholder="Rechercher (employé, poste, montants, etc.)…"
                                    class="px-4 py-2 border border-gray-300 rounded-lg input-focus shadow"
                                    style="width: 500px; margin-left: 20px;">
                            </div>
                        </div>
                        <a id="btnImportExcel"
                            data-href-template="{{ route('detailParEmployerTablePdf', ['ticket' => '___TICKET___']) }}"
                            href="#" target="_blank"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors opacity-50 pointer-events-none"
                            title="Renseignez un ticket">
                            <i class="fas fa-file-excel mr-2"></i>Importer en pdf
                        </a>

                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const input = document.getElementById('periodTicket');
                            const btn = document.getElementById('btnImportExcel');
                            if (!input || !btn) return;

                            const tpl = btn.dataset.hrefTemplate; // ex: ".../detailParEmployerTablePdf/Tk-___TICKET___"
                            const disabledClasses = ['opacity-50', 'pointer-events-none'];

                            function enableBtn(url) {
                                btn.href = url;
                                btn.classList.remove(...disabledClasses);
                                btn.removeAttribute('title');
                            }

                            function disableBtn() {
                                btn.href = '#';
                                btn.classList.add(...disabledClasses);
                                btn.setAttribute('title', 'Renseignez un ticket');
                            }

                            function computeUrl(ticket) {
                                return tpl.replace('___TICKET___', encodeURIComponent(ticket));
                            }

                            function updateHref() {
                                const t = (input.value || '').trim();
                                if (t) enableBtn(computeUrl(t));
                                else disableBtn();
                            }

                            // 1) Init immédiate
                            updateHref();

                            // 2) Écoutes “classiques”
                            ['input', 'change', 'paste', 'keyup'].forEach(evt =>
                                input.addEventListener(evt, updateHref)
                            );

                            // 3) Hooks Livewire (si présent)
                            document.addEventListener('livewire:load', () => {
                                if (window.Livewire && Livewire.hook) {
                                    Livewire.hook('message.processed', () => {
                                        // Livewire vient de patcher le DOM → resynchroniser
                                        updateHref();
                                    });
                                }
                            });

                            // 4) Polling léger pour les MAJ silencieuses (propriété value modifiée sans event)
                            let last = input.value;
                            setInterval(() => {
                                if (input.value !== last) {
                                    last = input.value;
                                    updateHref();
                                }
                            }, 300);

                            // 5) Garde-fou au clic
                            btn.addEventListener('click', (e) => {
                                if (!input.value || !input.value.trim()) {
                                    e.preventDefault();
                                    alert("Veuillez d'abord renseigner le ticket.");
                                }
                            });
                        });
                    </script>


                    <div class="payrollScroll" style="max-height: 450px; overflow-y: auto;">
                        <!-- Champ de recherche pour ce tableau -->


                        <div class="table-responsive">
                            <table id="example2" class="w-full table table-striped table-bordered">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left  text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Employé</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Salaire Base</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gains Variables</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Retenues</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Salaire Net</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="employeeDetailsBody" class="bg-white divide-y divide-gray-200">
                                    <!-- lignes <tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const input = document.getElementById('searchEmployeeDetails');
                            const tbody = document.getElementById('employeeDetailsBody');

                            // Normalisation: minuscules + suppression des accents
                            const norm = (s) =>
                                (s || '')
                                .toString()
                                .toLowerCase()
                                .normalize('NFD')
                                .replace(/[\u0300-\u036f]/g, '')
                                .trim();

                            // (Optionnel) petit debounce pour éviter de trop filer lors d'une saisie rapide
                            let t = null;
                            input.addEventListener('input', function() {
                                clearTimeout(t);
                                t = setTimeout(() => {
                                    const q = norm(this.value);
                                    const rows = tbody.querySelectorAll('tr');

                                    rows.forEach((tr) => {
                                        // Si tu préfères cibler seulement la 1re colonne (Employé) :
                                        // const cellText = tr.querySelector('td')?.innerText || '';
                                        // const text = norm(cellText);

                                        // Sinon: filtrer sur TOUTE la ligne
                                        const text = norm(tr.innerText);

                                        tr.style.display = !q || text.includes(q) ? '' : 'none';
                                    });
                                }, 80);
                            });
                        })();
                    </script>

                </div>

                <!-- Répartition par service -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <i class="fas fa-building mr-2 "></i>Répartition par Service
                    </h3>
                    <div id="serviceBreakdown" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Modal: Ajouter Employé -->
    <div id="addEmployeeModal" class="fixed inset-0 hidden z-50 no-print" style="background-color: rgba(0, 0, 0, 0.393)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-gray">
                    <h3 class="text-lg font-semibold text-primary flex items-center">
                        <i class="fas fa-user-plus mr-2 "></i>Modifier le salaire de base
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="mb-3">
                        <label class="form-label">Salaire de base</label>
                        <input type="text" id="newEmployeeBaseSalary" class="form-control" min="0"
                            placeholder="Salaire" required>
                        <input type="hidden" class="form-control" id="employeeIdToUpdate" value="">
                        <!-- Champ caché pour l'ID -->
                    </div>
                </div>
                <div class="p-6 border-t border-gray flex justify-end space-x-3">
                    {{-- <button onclick="closeAddEmployeeModal()"
                        class="px-4 py-2 text-gray-600 hover:text-primary transition-colors">Annuler</button> --}}
                    <button onclick="updateEmployeeBaseSalary()"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        (function() {
            const replaceEl = document.getElementById('replaceTicket');
            const startEl = document.getElementById('periodStart');
            const endEl = document.getElementById('periodEnd');
            const ticketEl = document.getElementById('periodTicket');

            // Injecte les variables backend manquantes dans payrollVariables
            function ensurePayrollVariables(varsFromBackend) {
                if (!Array.isArray(varsFromBackend)) return;

                const names = new Set(payrollVariables.map(v => v.name));
                varsFromBackend.forEach(v => {
                    if (!names.has(v.name)) {
                        payrollVariables.push({
                            name: v.name,
                            type: v.type === 'deduction' ? 'deduction' : 'gain',
                            category: v.category || '—'
                        });
                        names.add(v.name);
                    }
                });
            }

            // Convertit la map employeeData reçue en s’assurant que les employés existent
            function applyEmployeeData(map) {
                // reset toutes les saisies courantes
                window.employeeData = {};

                if (!map || typeof map !== 'object') return;

                // Construire un index de recherche employé par clé (matricule d’abord, sinon id)
                const empIndex = {};
                (employees || []).forEach(e => {
                    if (e.id) empIndex[e.id] = e; // ici e.id = matricule OU id selon ton mapping
                });

                // Injecte chaque valeur si l’employé est connu
                Object.keys(map).forEach(empKey => {
                    if (!empIndex[empKey]) return; // ignore les employés non chargés côté front
                    window.employeeData[empKey] = {};
                    const vars = map[empKey] || {};
                    Object.keys(vars).forEach(varName => {
                        const val = parseFloat(vars[varName]) || 0;
                        if (val > 0) { // on n’enregistre pas les 0 (considérés supprimés)
                            window.employeeData[empKey][varName] = val;
                        }
                    });
                });
            }

            async function fetchTicketData(ticket) {
                const url = `{{ url('/payroll/period') }}/${encodeURIComponent(ticket)}/data`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();
                if (!res.ok) {
                    throw new Error(json.message || 'Impossible de charger les données du ticket.');
                }
                return json.data;
            }

            async function onReplaceTicketChange(e) {
                const ticket = e.target.value;
                if (!ticket) return; // rien sélectionné

                try {
                    // 1) Récupère les montants et la période
                    const data = await fetchTicketData(ticket);

                    // 2) Mise à jour de l’UI période
                    if (startEl) startEl.value = data?.period?.start || '';
                    if (endEl) endEl.value = data?.period?.end || '';
                    if (ticketEl) ticketEl.value = data?.ticket || ticket;

                    // 3) Variables (ajoute celles manquantes pour que le tableau ait bien les colonnes)
                    ensurePayrollVariables(data?.variables || []);

                    // 4) Montants -> employeeData
                    applyEmployeeData(data?.employeeData || {});

                    // 5) Re-render des vues impactées
                    if (typeof renderPayrollTable === 'function') renderPayrollTable(); // Saisie globale
                    if (typeof renderEmployeeSelector === 'function') renderEmployeeSelector(); // Saisie détaillée
                    if (typeof calculateSynthesis === 'function') calculateSynthesis(); // Synthèse
                } catch (err) {
                    console.error(err);
                    showNotification(err.message || 'Erreur lors du chargement du ticket', 'error');
                }
            }

            replaceEl?.addEventListener('change', onReplaceTicketChange);
        })();
    </script>

    <script>
        // IDs des champs à mapper depuis les clés de validation Laravel
        const FIELD_MAP = {
            'period.start': 'periodStart',
            'period.end': 'periodEnd',
            'replace_ticket': 'replaceTicket',
        };

        function clearFieldErrors() {
            Object.values(FIELD_MAP).forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.remove('is-invalid');
                // si un bloc .invalid-feedback juste après existe, on le vide/masque
                const box = el.parentElement?.querySelector('.invalid-feedback');
                if (box) {
                    box.textContent = '';
                    box.classList.add('d-none');
                }
            });

            const globalBox = document.getElementById('globalFormErrors');
            if (globalBox) {
                globalBox.innerHTML = '';
                globalBox.classList.add('d-none');
            }
        }

        function showFieldError(inputId, message) {
            const el = document.getElementById(inputId);
            if (!el) return;

            el.classList.add('is-invalid');

            // on cherche un sibling .invalid-feedback sinon on le crée
            let box = el.parentElement?.querySelector('.invalid-feedback');
            if (!box) {
                box = document.createElement('div');
                box.className = 'invalid-feedback';
                el.parentElement.appendChild(box);
            }
            box.textContent = message;
            box.classList.remove('d-none');
        }

        function handleValidationErrors(payload) {
            clearFieldErrors();

            const errors = payload?.errors || {};
            const first = payload?.first_error || payload?.message;

            // Affiche toast globale si dispo
            if (typeof showNotification === 'function' && (first || payload?.message)) {
                showNotification(first || 'Données invalides', 'error');
            }

            // Affiche inline pour les champs connus
            Object.keys(errors).forEach(key => {
                const inputId = FIELD_MAP[key];
                if (inputId) {
                    showFieldError(inputId, errors[key][0] || 'Champ invalide');
                }
            });

            // S'il reste des erreurs non mappées (ex: employeeData), on les met dans un bloc global
            const others = Object.entries(errors).filter(([k]) => !FIELD_MAP[k]);
            if (others.length) {
                let html = '<ul class="mb-0 ps-3">';
                others.forEach(([, msgs]) => {
                    (msgs || []).forEach(m => {
                        html += `<li>${m}</li>`;
                    });
                });
                html += '</ul>';

                let globalBox = document.getElementById('globalFormErrors');
                if (!globalBox) {
                    globalBox = document.createElement('div');
                    globalBox.id = 'globalFormErrors';
                    globalBox.className = 'alert alert-danger mt-3';
                    // Tu peux choisir où l’insérer :
                    // Ici on l’ajoute après le conteneur de la période s’il existe
                    const periodContainer = document.getElementById('content-periode') || document.body;
                    periodContainer.appendChild(globalBox);
                }
                globalBox.innerHTML = html;
                globalBox.classList.remove('d-none');
            }
        }
    </script>

    <script>
        // Format YYYY-MM-DD -> JJ/MM/AAAA
        function fmtFR(iso) {
            if (!iso) return '';
            const [y, m, d] = iso.split('-');
            return `${d}/${m}/${y}`;
        }

        // Charge et remplit la liste des tickets remplaçables
        async function loadReplaceableTickets(options = {
            excludeCurrent: true
        }) {
            const sel = document.getElementById('replaceTicket');
            if (!sel) return;

            // optionnel: exclure le ticket actuellement affiché dans l'UI
            const current = document.getElementById('periodTicket')?.value || null;

            try {
                const res = await fetch("{{ route('payroll.tickets.replaceable') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                let json = {};
                try {
                    json = await res.json();
                } catch (_) {}

                if (!res.ok) {
                    (window.showNotification || console.error)(
                        json.message || 'Impossible de charger les tickets', 'error'
                    );
                    return;
                }

                const rows = Array.isArray(json.data) ? json.data : [];
                const opts = ['<option value="">Choix du ticket</option>']
                    .concat(rows
                        .filter(t => !options.excludeCurrent || t.ticket !== current)
                        .map(t => {
                            const sd = fmtFR(t.date_debut);
                            const ed = fmtFR(t.date_fin);
                            return `<option value="${t.ticket}">${t.ticket}</option>`;
                        })
                    );

                sel.innerHTML = opts.join('');

            } catch (e) {
                console.error(e);
                (window.showNotification || console.error)(
                    'Erreur réseau lors du chargement des tickets', 'error'
                );
            }
        }

        // Charger à l’ouverture de la page
        document.addEventListener('DOMContentLoaded', () => {
            loadReplaceableTickets();
        });
    </script>
    <script>
        (function() {
            const modalInput = document.getElementById('newEmployeeBaseSalary');
            const hiddenId = document.getElementById('employeeIdToUpdate');

            // Ouvre la modale pour un employé et pré-remplit
            window.editEmployee = function(empId) {
                const rowInput = document.querySelector(`input.input-montant-base[data-employee-id="${empId}"]`);
                document.getElementById('newEmployeeBaseSalary').value = '';
                // document.getElementById('newEmployeeBaseSalary').value = rowInput ? rowInput.value : '';
                document.getElementById('employeeIdToUpdate').value = empId;
                document.getElementById('addEmployeeModal').classList.remove('hidden');
            };

            // Miroite en “live” ce qui est saisi dans la modale vers la ligne de l’employé
            function mirrorBaseSalary() {
                const id = (hiddenId?.value || '').trim();
                if (!id) return;

                const raw = (modalInput?.value || '').trim();
                const num = parseFloat(raw.replace(/\s/g, '').replace(',', '.'));

                // met à jour l'input de la ligne
                const rowInput = document.querySelector(`input.input-montant-base[data-employee-id="${id}"]`);
                if (rowInput) rowInput.value = isNaN(num) ? '' : num;

                // met à jour le store JS si disponible
                if (Array.isArray(window.employees)) {
                    const idx = employees.findIndex(e => String(e.id) === String(id) || String(e.matricule) === String(
                        id));
                    if (idx !== -1) employees[idx].baseSalary = isNaN(num) ? 0 : num;
                }
            }

            modalInput?.addEventListener('input', mirrorBaseSalary);
            // dispo si tu veux déclencher manuellement:
            window.updateEmployeeBaseSalaryField = mirrorBaseSalary;
        })();
    </script>

    <script>
        // --- Données venant de Laravel (users + relations) ---
        const utilisateurs = @json($utilisateurs);

        // Petite util pour "flatter" un objet (valeurs scalaires + relations)
        function deepIncludes(obj, q) {
            if (!q) return true;
            const queue = [obj];
            while (queue.length) {
                const cur = queue.pop();
                for (const k in cur) {
                    const v = cur[k];
                    if (v == null) continue;
                    if (typeof v === 'object') {
                        queue.push(v);
                    } else {
                        if (String(v).toLowerCase().includes(q)) return true;
                    }
                }
            }
            return false;
        }

        // --- Filtrage (optionnel : si query est vide, on garde tout) ---
        const q = (typeof query === 'string' ? query : '').trim().toLowerCase();
        const resultsUtilisateur = q ?
            utilisateurs.filter(u => deepIncludes(u, q)) :
            utilisateurs;

        // --- Transformation en "employees" dynamiques ---
        const employees = resultsUtilisateur.map(u => ({
            id: u.id, // choisis l’un des deux
            matricule: u.matricule, // choisis l’un des deux
            firstName: u.prenom ?? '—',
            lastName: u.nom ?? '—',
            position: u.fonction // champ direct si présent
                ??
                u.categorie_professionnelle?.nom_categorie_professionnelle // depuis la relation
                ??
                '—',
            department: u.service?.nom_service ?? '—', // vérifie le nom du champ de ta table service
            role: u.role?.nom ?? '—',
            baseSalary: u.salairebase ? Number(u.salairebase) : null, // "350000" -> 350000
            status: u.type_contrat ?? '—', // ou derive de date_fin_contrat si besoin
            actif: u.statu_user === 1 // bool utile côté UI
        }));




        // Depuis ton contrôleur : $variables = Variable::with('categorie')->get();
        //                         $categories = Categorie::where('statut',1)->orderBy('nom_categorie')->get();

        const rawVariables = @json($variables);
        const rawCategories = @json($categories);

        // Variables pour l’UI (note: on garde l’index pour deleteVariable(index))
        let payrollVariables = (rawVariables || []).map(v => ({
            id: v.id,
            name: v.nom_variable,
            statutVariable: v.statutVariable,
            variableImposable: v.variableImposable,
            variableImposable: v.variableImposable,
            tauxVariable: v.tauxVariable,
            tauxVariableEntreprise: v.tauxVariableEntreprise,
            type: v.type, // 'gain' | 'deduction'
            categoryId: v.categorie ? v.categorie.id : null,
            categoryName: v.categorie ? v.categorie.nom_categorie : '(Sans catégorie)'
        }));

        // (optionnel) tri par catégorie puis par nom pour un affichage propre
        payrollVariables.sort((a, b) => {
            const cA = (a.category || '').localeCompare(b.category || '');
            return cA !== 0 ? cA : (a.name || '').localeCompare(b.name || '');
        });

        // Si ta grille s’affiche au chargement :
        if (typeof renderVariablesGrid === 'function') {
            renderVariablesGrid();
        }

        let currentTab = 'periode';
        let currentEmployeeIndex = 0;
        let employeeData = {};
        let salaryChart = null;

        // --- UTILITAIRES ---
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount) + ' F CFA';
        }

        function generateEmployeeId() {
            const maxId = Math.max(...employees.map(emp => parseInt(emp.id.slice(3))));
            return `EMP${String(maxId + 1).padStart(3, '0')}`;
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className =
                `notification ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'} text-white`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // --- GESTION DES ONGLETS ---
        function switchTab(tabName) {
            // Masquer tous les contenus
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Désactiver tous les onglets
            document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                tab.classList.remove('tab-active');
                tab.classList.add('bg-gray-100');
            });

            // Activer l'onglet sélectionné
            document.getElementById(`tab-${tabName}`).classList.add('tab-active');
            document.getElementById(`tab-${tabName}`).classList.remove('bg-gray-100');

            // Afficher le contenu correspondant
            document.getElementById(`content-${tabName}`).classList.remove('hidden');

            currentTab = tabName;

            // Actions spécifiques selon l'onglet
            if (tabName === 'employes') {
                renderEmployeesTable();
            } else if (tabName === 'variables') {
                renderVariablesGrid();
            } else if (tabName === 'saisie-globale') {
                renderPayrollTable();
            } else if (tabName === 'saisie-detaillee') {
                renderEmployeeSelector();
            } else if (tabName === 'synthese') {
                calculateSynthesis();
            }
        }

        // --- GESTION DES EMPLOYÉS ---
        function renderEmployeesTable() {
            const tbody = document.getElementById('employeesTableBody');
            const searchTerm = document.getElementById('searchEmployee')?.value.toLowerCase() || '';

            const filteredEmployees = employees.filter(emp =>
                emp.firstName.toLowerCase().includes(searchTerm) ||
                emp.lastName.toLowerCase().includes(searchTerm) ||
                emp.position.toLowerCase().includes(searchTerm) ||
                emp.department.toLowerCase().includes(searchTerm)
            );

            tbody.innerHTML = filteredEmployees.map(emp =>

                `
            
                 <tr class="hover:bg-gray-50" data-employee-id="${emp.id}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${emp.matricule}</td>
                   <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            ${ (emp.lastName + ' ' + emp.firstName).length > 20 
                                ? (emp.lastName + ' ' + emp.firstName).substring(0, 20) + "..." 
                                : (emp.lastName + ' ' + emp.firstName) }
                        </div>
                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${ emp.position.length > 20
                            ? emp.position.substring(0, 20) + "..." 
                            : emp.position }
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${ emp.department.length > 20 
                            ? emp.department.substring(0, 20) + "..." 
                            : emp.department }
                    </td>
                    <td class="py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                         <input
                            type="text"
                            class="form-control input-montant-base shadow-none"
                            data-employee-id="${emp.id}"
                            value="${formatCurrency(emp.baseSalary) ?? 0}" readonly style="border:none">
                                            <input
                            type="text"
                            class="form-control input-montant-base hidden"
                            id="montantCacher">
                        </td>
                    <td class=" py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(emp.status)}">
                            ${emp.status}
                        </span>
                    </td>
                    <td class=" py-4 whitespace-nowrap text-left text-sm font-medium no-print">
                        <button onclick="editEmployee('${emp.id}')" class="btn btn-primary hover:text-blue-900 mr-3">
                            <i class="fas fa-edit me-2"></i> Salaire de base
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function getStatusBadgeClass(status) {
            switch (status) {
                case 'CDI':
                    return 'bg-green-100 text-green-800';
                case 'CDD':
                    return 'bg-yellow-100 text-yellow-800';
                case 'Stage':
                    return 'bg-blue-100 text-blue-800';
                case 'Consultant':
                    return 'bg-purple-100 text-purple-800';
                default:
                    return 'bg-gray-100 text-primary';
            }
        }


        function showAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.remove('hidden');
        }

        // function closeAddEmployeeModal() {
        //     document.getElementById('addEmployeeModal').classList.add('hidden');
        //     const department = document.getElementById('employeeIdToUpdate').value;
        //     document.getElementById('newEmployeeBaseSalary').value = '';
        // }

        async function updateEmployeeBaseSalary() {
            const idEl = document.getElementById('employeeIdToUpdate');
            const salEl = document.getElementById('newEmployeeBaseSalary');


            const employeeId = (idEl?.value || '').trim(); // id ou matricule
            const salaryText = (salEl?.value || '').replace(/\s/g, '');
            const baseSalary = parseFloat(salaryText.replace(',', '.'));

            if (!employeeId || isNaN(baseSalary) || baseSalary < 0) {
                showNotification('Veuillez entrer un salaire de base valide', 'error', 9000);
                return;
            }

            try {
                const res = await fetch("{{ route('employees.updateBaseSalary') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: JSON.stringify({
                        employee_id: employeeId,
                        base_salary: baseSalary
                    })
                });

                let data = {};
                try {
                    data = await res.json();
                } catch (_) {}

                if (!res.ok) {
                    if (res.status === 419) {
                        showNotification('Session expirée. Rechargez la page et réessayez.', 'error', 9000);
                        return;
                    }
                    if (res.status === 422 && data?.errors) {
                        showNotification(data.message || 'Données invalides.', 'error', 9000);
                        return;
                    }
                    showNotification(data.message || 'Erreur lors de la mise à jour du salaire de base', 'error', 9000);
                    return;
                }

                // -------- MAJ locale du store + du champ de la ligne --------
                const newVal = Number(data?.data?.base_salary ?? baseSalary);

                // 1) store employees (par id OU matricule)
                let domId = null;
                if (Array.isArray(window.employees)) {
                    const idx = employees.findIndex(e =>
                        String(e.id) === String(employeeId) || String(e.matricule) === String(employeeId)
                    );
                    if (idx !== -1) {
                        employees[idx].baseSalary = newVal;
                        domId = employees[idx].id; // id utilisé dans data-employee-id du tableau
                    }
                }
                // Si pas trouvé ci-dessus, tente id renvoyé par le backend
                if (!domId) domId = data?.data?.id ?? employeeId;

                // 2) input de la ligne
                const rowInput = document.querySelector(`input.input-montant-base[data-employee-id="${domId}"]`);
                if (rowInput) rowInput.value = formatCurrency(newVal);


                // 3) recalcule le net affiché pour cette ligne + la synthèse globale
                if (typeof updateEmployeeNetInTable === 'function') updateEmployeeNetInTable(domId);
                if (typeof calculateSynthesis === 'function') calculateSynthesis();

                // -------- UI/Reset --------
                document.getElementById('addEmployeeModal')?.classList.add('hidden');
                salEl.value = '';
                idEl.value = '';

                showNotification(data.message || 'Salaire de base mis à jour', 'success', 7000);

            } catch (error) {
                console.error(error);
                showNotification('Erreur lors de la mise à jour du salaire de base (réseau)', 'error', 9000);
            }
        }




        function editEmployee(employeeId) {
            const employee = employees.find(emp => emp.id === employeeId);
            if (!employee) return;
            document.getElementById('newEmployeeBaseSalary').value = employee.baseSalary;
            document.getElementById('employeeIdToUpdate').value = employee.id;

            // deleteEmployee(employeeId);
            showAddEmployeeModal();
        }

        function importEmployees() {
            showNotification('Fonctionnalité d\'import Excel en cours de développement', 'info');
        }

        function renderVariablesGrid() {
            const container = document.getElementById('variablesContainer');

            const groupsMap = new Map();
            (rawCategories || []).forEach(cat => { // Safe check. If rawCategories is undefined, use [].
                groupsMap.set(cat.id, {
                    label: cat.nom_categorie,
                    items: [],
                    catId: cat.id
                });
            });

            const UNCAT_KEY = '__uncat__';

            (payrollVariables || []).forEach((variable, index) => {
                const item = {
                    ...variable,
                    index
                };
                if (item.categoryId && groupsMap.has(item.categoryId)) { // Use item.categoryId here.
                    groupsMap.get(item.categoryId).items.push(item);
                } else {
                    if (!groupsMap.has(UNCAT_KEY)) {
                        groupsMap.set(UNCAT_KEY, {
                            label: '(Sans catégorie)',
                            items: [],
                            catId: null
                        });
                    }
                    groupsMap.get(UNCAT_KEY).items.push(item);
                }
            });

            const orderedGroups = [
                ...[...groupsMap.entries()].filter(([k]) => k !== UNCAT_KEY),
                ...[...groupsMap.entries()].filter(([k]) => k === UNCAT_KEY),
            ];

            container.innerHTML = orderedGroups.map(([key, group]) => `
            <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-primary mb-3 flex items-center">
                <i class="fas fa-folder mr-2"></i>${group.label}
            </h4>

            ${
                group.items.length
                ? `<div class="space-y-2">
                                                                                        ${group.items.map(v => `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border ${getVariableClass(v.type)}">
                        <div class="items-center gap-2">
                            <i class="fas ${getVariableIcon(v.type)} ${getVariableIconColor(v.type)}"></i>
                            <span class="text-sm font-medium">${
                                v.name?.length > 40 // Add ? to avoid error on v.name
                        ? v.name.substring(0, 40) + "..."
                        : v.name}</span>
                        <br>
                            ${
                                v.statutVariable
                                ? `<span class="badge bg-info text-dark">Taux salarial:  ${v.tauxVariable ?? 0}%</span>`
                                : ''
                            }
                            ${
                                v.statutVariable
                                ? `<span class="badge bg-info text-dark">Taux patronal:  ${v.tauxVariableEntreprise ?? 0}%</span>`
                                : ''
                            }
                            ${v.variableImposable ? `<span class="badge bg-warning text-dark">Imposable</span>` : ``}
                        </div>
                        <div class="flex items-center gap-2 no-print">
                        <button
class="text-primary hover:text-primary-emphasis btn btn-sm btn-light"
onclick="Livewire.dispatch('open-edit', { id: '${v.id}' })"
title="Modifier">
<i class="fas fa-pen"></i>
</button>

<button
class="text-danger hover:text-danger-emphasis btn btn-sm btn-light"
onclick="confirmDeleteVariable('${v.id}')"
title="Supprimer">
<i class="fas fa-times"></i>
</button>

                        </div>
                    </div>
                    `).join('')}
                                                                                    </div>`
                : `
                                                                                    <div class="p-3 bg-white rounded-lg border border-dashed text-sm text-gray-500 flex items-center justify-between">
                                                                                        <span>Aucune variable dans cette catégorie</span>
                                                                                    </div>`
            }
            </div>
        `).join('');
        }

        function confirmDeleteVariable(id) {
            if (confirm('Supprimer cette variable ?')) {
                // Le composant VariablesManager écoute #[On('delete-variable')]
                Livewire.dispatch('delete-variable', {
                    id
                });
            }
        }

        // Pré-sélectionner la catégorie dans le modal d’ajout
        function openAddVariableWithCategory(catId) {
            const select = document.getElementById('newVariableCategory');
            if (select && catId) select.value = catId;
            if (typeof showAddVariableModal === 'function') showAddVariableModal();
        }

        function getVariableClass(type) {
            switch (type) {
                case 'gain':
                    return 'variable-gain';
                case 'deduction':
                    return 'variable-deduction';
                default:
                    return '';
            }
        }

        function getVariableIcon(type) {
            switch (type) {
                case 'gain':
                    return 'fa-plus-circle';
                case 'deduction':
                    return 'fa-minus-circle';
                default:
                    return 'fa-circle';
            }
        }

        function getVariableIconColor(type) {
            switch (type) {
                case 'gain':
                    return 'text-green-600';
                case 'deduction':
                    return 'text-red-600';
                default:
                    return 'text-gray-600';
            }
        }


        function closefloatingLabelsModal() {
            document.getElementById('floatingLabelsModal').classList.add('hidden');
            document.getElementById('newVariableName').value = '';
            document.getElementById('newVariableType').value = 'gain';
            document.getElementById('newVariableCategory').value = 'Primes';
        }

        // Ouvrir la modale depuis "paie"
        function openCreateVariable(catId = null) {
            Livewire.dispatch('open-create', {
                categorieId: catId
            });
        }

        function openEditVariableById(id) {
            Livewire.dispatch('open-edit', {
                id
            });
        }

        // Normalisation Livewire -> objet utilisé par ta grille
        function normalizeFromLW(obj) {
            return {
                id: obj.id,
                name: obj.name,
                statutVariable: !!obj.statutVariable,
                variableImposable: !!obj.variableImposable,
                tauxVariable: obj.tauxVariable,
                tauxVariableEntreprise: obj.tauxVariableEntreprise,
                type: obj.type,
                categoryId: obj.categorie?.id || null,
                categoryName: obj.categorie?.nom_categorie || '(Sans catégorie)',
                numeroVariable: obj.numeroVariable ?? null,
            };
        }

        // Upsert dans payrollVariables puis re-render
        function upsertVariable(v) {
            const idx = payrollVariables.findIndex(x => x.id === v.id);
            if (idx >= 0) payrollVariables[idx] = v;
            else payrollVariables.push(v);
            renderVariablesGrid();
        }

        // Supprimer de payrollVariables puis re-render
        function removeVariable(id) {
            const idx = payrollVariables.findIndex(x => x.id === id);
            if (idx >= 0) payrollVariables.splice(idx, 1);
            renderVariablesGrid();
        }

        // Écoute des events envoyés par le composant
        window.addEventListener('variable-upserted', (e) => {
            const v = normalizeFromLW(e.detail);
            upsertVariable(v);
        });
        window.addEventListener('variable-deleted', (e) => {
            removeVariable(e.detail?.id);
        });

        // --- SAISIE GLOBALE ---
        function renderPayrollTable() {
            console.log('renderPayrollTable called', payrollVariables);
            const thead = document.getElementById('payrollTableHead');
            const tbody = document.getElementById('payrollTableBody');

            // En-tête
            let headerHTML = `
                               <tr>
                               <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Employé</th>
                               <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Salaire de Base</th>
                           `;

            payrollVariables.forEach(variable => {
                const badge = variable.statutVariable ?
                    ` <span class="badge bg-info text-dark ms-1" title="Variable de cotisation">%</span>` :
                    '';
                headerHTML += `
                               <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider ${getVariableIconColor(variable.type)}"
                                   title="${variable.categoryName}">
                                   ${
                                       variable.name?.length > 30  // Use optional chaining
                                       ? variable.name.substring(0, 30) + "..."
                                       : variable.name
                                   }${badge}
                               </th>
                               `;
            });

            headerHTML += `
                               <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net à Payer</th>
                               </tr>
                           `;
            thead.innerHTML = headerHTML;

            // Corps
            tbody.innerHTML = employees.map(employee => {
                let rowHTML = `
                               <tr class="hover:bg-gray-50" data-employee-id="${employee.id}">
                                   <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10">
                                   ${employee.lastName} ${employee.firstName}
                                   </td>
                                   <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-medium">
                                   ${formatCurrency(employee.baseSalary)}
                                   </td>
                               `;

                payrollVariables.forEach(variable => {
                    const currentValue = (employeeData[employee.id] && employeeData[employee.id][variable
                        .name
                    ]) || 0;

                    if (variable.statutVariable) {
                        // 🔒 Verrouillé : affiche juste le pourcentage, pas de calcul par employé
                        const pct = (variable.tauxVariable ?? 0);
                        const pct1 = (variable.tauxVariableEntreprise ?? 0);
                        rowHTML += `
                               <td class="px-4 py-4 whitespace-nowrap text-center">
                                   <div>
                                       <label class="form-label">Tx salariale</label>
                                       
                                       <input type="text"
                                           class="w-24 px-2 py-1 text-sm text-center border border-gray-300 rounded bg-light"
                                           value="${pct} %"
                                           title="Variable de cotisation (verrouillée)"
                                           readonly disabled
                                           data-locked="1"
                                           data-variable-id="${variable.id}">
                                   </div>
                                   <div>
                                            <label class="form-label">Tx patronale</label>
                                       <input type="text"
                                       class="w-24 px-2 py-1 text-sm text-center border border-gray-300 rounded bg-light"
                                       value="${pct1} %"
                                       title="Variable de cotisation (verrouillée)"
                                       readonly disabled
                                       data-locked="1"
                                       data-variable-id="${variable.id}">
                                   </div>
                               </td>
                               `;
                    } else {
                        // ✍️ Libre : champ éditable (gains/retenues classiques)
                        rowHTML += `
                               <td class="px-4 py-4 whitespace-nowrap">
                                   <input type="number"
                                       class="w-24 px-2 py-1 text-sm text-right border border-gray-300 rounded input-focus"
                                       placeholder="0"
                                       min="0"
                                       step="1000"
                                       value="${currentValue}"
                                       data-employee-id="${employee.id}"
                                       data-variable-name="${variable.name}"
                                       onchange="handleGlobalInput(this)">
                               </td>
                               `;
                    }
                });

                const netSalary = calculateEmployeeNet(employee.id);
                rowHTML += `
                               <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-bold" data-net-salary="${employee.id}">
                               ${formatCurrency(netSalary)}
                               </td>
                           </tr>
                           `;
                return rowHTML;
            }).join('');
        }



        function handleGlobalInput(input) {
            const employeeId = input.getAttribute('data-employee-id');
            const variableName = input.getAttribute('data-variable-name');
            const value = parseFloat(input.value) || 0;

            if (!employeeData[employeeId]) {
                employeeData[employeeId] = {};
            }
            employeeData[employeeId][variableName] = value;

            updateEmployeeNetInTable(employeeId);
        }

        function calculateEmployeeNet(employeeId) {
            const employee = employees.find(emp => emp.id === employeeId);
            if (!employee) return 0;

            let totalGains = 0;
            let totalDeductions = 0;

            if (employeeData[employeeId]) {
                payrollVariables.forEach(variable => {
                    const value = employeeData[employeeId][variable.name] || 0;
                    if (variable.type === 'gain') {
                        totalGains += value;
                    } else {
                        totalDeductions += value;
                    }
                });
            }

            return employee.baseSalary + totalGains - totalDeductions;
        }

        function updateEmployeeNetInTable(employeeId) {
            const netSalary = calculateEmployeeNet(employeeId);
            const netSalaryCell = document.querySelector(`[data-net-salary="${employeeId}"]`);
            if (netSalaryCell) {
                netSalaryCell.textContent = formatCurrency(netSalary);
                netSalaryCell.style.color = netSalary >= 0 ? '#2563eb' : '#ef4444';
            }
        }

        function calculateAll() {
            employees.forEach(employee => {
                updateEmployeeNetInTable(employee.id);
            });
            showNotification('Calculs effectués pour tous les employés', 'success');
        }

        function resetAllInputs() {
            if (confirm('Êtes-vous sûr de vouloir effacer toutes les saisies ?')) {
                employeeData = {};
                renderPayrollTable();
                showNotification('Toutes les saisies ont été effacées', 'success');
            }
        }

        // --- SAISIE DÉTAILLÉE ---
        function renderEmployeeSelector() {
            const selector = document.getElementById('employeeSelector');
            selector.innerHTML = '';

            employees.forEach((emp, index) => {
                const card = document.createElement('div');
                card.className = `employee-card p-4 rounded-lg cursor-pointer transition-all ${
                index === currentEmployeeIndex ? 'selected' : ''
            }`;
                card.onclick = () => selectEmployee(index);

                card.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user "></i>
                        </div>
                        <div>
                            <div class="font-semibold text-primary">${emp.lastName} ${emp.firstName}</div>
                            <div class="text-sm text-gray-600">${emp.matricule} • ${emp.department}</div>
                            <div class="text-sm font-medium ">${formatCurrency(emp.baseSalary)}</div>
                        </div>
                    </div>
                `;

                selector.appendChild(card);
            });

            if (employees.length > 0) {
                renderEmployeeDetail();
            }
        }

        function selectEmployee(index) {
            currentEmployeeIndex = index;
            renderEmployeeSelector();
            renderEmployeeDetail();
        }

        function renderEmployeeDetail() {
            const emp = employees[currentEmployeeIndex];
            document.getElementById('employeeDetail').classList.remove('hidden');

            document.getElementById('selectedEmployeeName').textContent = `${emp.lastName} ${emp.firstName}`;
            document.getElementById('selectedEmployeeId').textContent = emp.matricule;
            document.getElementById('selectedEmployeeSalary').textContent = formatCurrency(emp.baseSalary);
            document.getElementById('selectedEmployeeService').textContent = emp.department;

            renderVariableForms();
            updateEmployeeRecap();
        }

        function renderVariableForms() {
            const emp = employees[currentEmployeeIndex];
            const gainsForm = document.getElementById('gainsForm');
            const deductionsForm = document.getElementById('deductionsForm');

            gainsForm.innerHTML = '';
            deductionsForm.innerHTML = '';

            payrollVariables.forEach(variable => {
                const value = (employeeData[emp.id] && employeeData[emp.id][variable.name]) || 0;
                let fieldHTML = '';

                if (variable.statutVariable) {
                    // 🔒 Verrouillé : afficher seulement les taux
                    const pctSal = (variable.tauxVariable ?? 0);
                    const pctPat = (variable.tauxVariableEntreprise ?? 0);

                    fieldHTML = `
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">${variable.name}</label>

        <div class="grid grid-cols-1 gap-3">
          <div>
            <label class="block text-xs text-gray-500 mb-1">Tx salariale</label>
            <input type="text"
                   class="detail-variable-input w-full px-4 py-3 border border-gray-300 rounded-lg text-right text-lg bg-gray-100"
                   value="${pctSal} %"
                   title="Variable de cotisation (verrouillée)"
                   readonly disabled
                   data-locked="1"
                   data-variable-id="${variable.id}">
          </div>

          <div>
            <label class="block text-xs text-gray-500 mb-1">Tx patronale</label>
            <input type="text"
                   class="detail-variable-input w-full px-4 py-3 border border-gray-300 rounded-lg text-right text-lg bg-gray-100"
                   value="${pctPat} %"
                   title="Variable de cotisation (verrouillée)"
                   readonly disabled
                   data-locked="1"
                   data-variable-id="${variable.id}">
          </div>
        </div>
      </div>
    `;
                } else {
                    // ✍️ Libre : champ éditable (gains/retenues classiques)
                    fieldHTML = `
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">${variable.name}</label>
        <div class="relative">
          <input type="number"
                 class="detail-variable-input w-full px-4 py-3 border border-gray-300 rounded-lg text-right text-lg input-focus"
                 placeholder="0"
                 min="0"
                 step="1000"
                 value="${value}"
                 data-employee-id="${emp.id}"
                 data-variable-name="${variable.name}"
                 oninput="handleDetailInput(this)">
        </div>
      </div>
    `;
                }

                if (variable.type === 'gain') {
                    gainsForm.insertAdjacentHTML('beforeend', fieldHTML);
                } else {
                    deductionsForm.insertAdjacentHTML('beforeend', fieldHTML);
                }
            });

        }
        const replaceTicketSelect = document.getElementById('replaceTicket');

        replaceTicketSelect.addEventListener('change', function() {
            const selectedTicket = this.value;
            if (selectedTicket) {
                loadTicketData(selectedTicket);
            } else {
                // Si aucun ticket n'est sélectionné, réinitialiser les données
                employeeData = {};
                renderPayrollTable();
            }

        });
        async function loadTicketData(ticket) {
            try {
                const res = await fetch("{{ route('payroll.period.data', ['ticket' => '__TICKET__']) }}".replace(
                    '__TICKET__', ticket), {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    const data = await res.json();
                    showNotification(data.message || 'Erreur lors du chargement des données du ticket', 'error');
                    employeeData = {}; // Réinitialiser en cas d'erreur
                    renderPayrollTable();
                    return;
                }

                const data = await res.json();

                // Mettre à jour les dates de période
                document.getElementById('periodStart').value = data.data.period.start;
                document.getElementById('periodEnd').value = data.data.period.end;

                // Mettre à jour employeeData avec les données du ticket
                employeeData = data.data.employeeData;

                // Ajouter les nouvelles variables du ticket à payrollVariables si elles n'existent pas déjà
                data.data.variables.forEach(newVar => {
                    const alreadyExists = payrollVariables.some(existingVar => existingVar.name === newVar
                        .name);
                    if (!alreadyExists) {
                        payrollVariables.push({
                            id: newVar.id || null, // Important si l'ID est utilisé
                            name: newVar.name,
                            type: newVar.type,
                            category: newVar.category
                        });
                    }
                });

                // (optionnel) tri par catégorie puis par nom pour un affichage propre
                payrollVariables.sort((a, b) => {
                    const cA = (a.category || '').localeCompare(b.category || '');
                    return cA !== 0 ? cA : (a.name || '').localeCompare(b.name || '');
                });

                // Recharger le tableau
                renderPayrollTable();

                showNotification('Données du ticket chargées avec succès', 'success');

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors du chargement des données du ticket (réseau)', 'error');
                employeeData = {}; // Réinitialiser en cas d'erreur
                renderPayrollTable();
            }
        }

        function handleDetailInput(input) {
            const emp = employees[currentEmployeeIndex];
            const variableName = input.getAttribute('data-variable-name');
            const value = parseFloat(input.value) || 0;

            if (!employeeData[emp.id]) {
                employeeData[emp.id] = {};
            }
            employeeData[emp.id][variableName] = value;

            updateEmployeeRecap();
        }

        function updateEmployeeRecap() {
            const emp = employees[currentEmployeeIndex];
            let totalGains = 0;
            let totalDeductions = 0;

            if (employeeData[emp.id]) {
                payrollVariables.forEach(variable => {
                    const value = employeeData[emp.id][variable.name] || 0;
                    if (variable.type === 'gain') {
                        totalGains += value;
                    } else {
                        totalDeductions += value;
                    }
                });
            }

            const netSalary = emp.baseSalary + totalGains - totalDeductions;

            document.getElementById('totalGains').textContent = formatCurrency(totalGains);
            document.getElementById('totalDeductions').textContent = formatCurrency(totalDeductions);
            document.getElementById('recapBaseSalary').textContent = formatCurrency(emp.baseSalary);
            document.getElementById('recapGains').textContent = formatCurrency(totalGains);
            document.getElementById('recapDeductions').textContent = formatCurrency(totalDeductions);
            document.getElementById('recapNetSalary').textContent = formatCurrency(netSalary);
        }

        function navigateEmployee(direction) {
            if (direction === 'prev' && currentEmployeeIndex > 0) {
                currentEmployeeIndex--;
            } else if (direction === 'next' && currentEmployeeIndex < employees.length - 1) {
                currentEmployeeIndex++;
            }

            renderEmployeeSelector();
            renderEmployeeDetail();
        }


        // ex: "/pdf/fichePaie/__USER__/__PERIODE__"
        const PAYSLIP_URL_TEMPLATE = @json(route('ficheDePaieDemo', ['userId' => '__USER__', 'tiketPeriode' => '__PERIODE__']));

        const periodTicketInput = document.getElementById('periodTicket');

        function buildPayslipUrl(userId, tiketPeriode) {
            return PAYSLIP_URL_TEMPLATE
                .replace('__USER__', encodeURIComponent(userId))
                .replace('__PERIODE__', encodeURIComponent(tiketPeriode));
        }

        function openPayslip(userId) {
            const ticket = (periodTicketInput?.value || '').trim();
            if (!ticket) {
                alert("Veuillez d'abord renseigner un ticket/période.");
                return;
            }
            window.open(buildPayslipUrl(userId, ticket), '_blank', 'noopener');
        }
        // --- SYNTHÈSE ---
        function calculateSynthesis() {
            let totalBaseSalary = 0;
            let totalVariableGains = 0;
            let totalDeductions = 0;
            const employeeDetails = [];
            const serviceData = {};

            employees.forEach(employee => {
                totalBaseSalary += employee.baseSalary;

                let employeeGains = 0;
                let employeeDeductions = 0;

                if (employeeData[employee.id]) {
                    payrollVariables.forEach(variable => {
                        const value = employeeData[employee.id][variable.name] || 0;
                        if (variable.type === 'gain') {
                            employeeGains += value;
                            totalVariableGains += value;
                        } else {
                            employeeDeductions += value;
                            totalDeductions += value;
                        }
                    });
                }

                const netSalary = employee.baseSalary + employeeGains - employeeDeductions;

                employeeDetails.push({
                    employee,
                    gains: employeeGains,
                    deductions: employeeDeductions,
                    netSalary
                });

                // Données par service
                if (!serviceData[employee.department]) {
                    serviceData[employee.department] = {
                        count: 0,
                        totalSalary: 0
                    };
                }
                serviceData[employee.department].count++;
                serviceData[employee.department].totalSalary += netSalary;
            });

            const totalNetSalary = totalBaseSalary + totalVariableGains - totalDeductions;

            // Mise à jour des indicateurs
            document.getElementById('summaryBaseSalary').textContent = formatCurrency(totalBaseSalary);
            document.getElementById('summaryVariableGains').textContent = formatCurrency(totalVariableGains);
            document.getElementById('summaryTotalDeductions').textContent = formatCurrency(totalDeductions);
            document.getElementById('summaryNetSalary').textContent = formatCurrency(totalNetSalary);

            // Mise à jour du tableau détaillé
            const tbody = document.getElementById('employeeDetailsBody');
            const ticket = (periodTicketInput?.value || '').trim();
            const canOpen = !!ticket;

            tbody.innerHTML = employeeDetails.map(detail => {
                const btnHtml = canOpen ?
                    `<button type="button"
               class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-primary"
               onclick="openPayslip('${detail.employee.id}')">
         Fiche de paie
       </button>` :
                    `<button type="button"
               class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md bg-gray-300 text-gray-600 cursor-not-allowed"
               title="Renseignez d'abord le ticket/période"
               disabled>
         Fiche de paie
       </button>`;

                return `
    <tr class="hover:bg-gray-50">
      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
        ${detail.employee.lastName} ${detail.employee.firstName}
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
        ${formatCurrency(detail.employee.baseSalary)}
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">
        ${formatCurrency(detail.gains)}
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
        ${formatCurrency(detail.deductions)}
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">
        ${formatCurrency(detail.netSalary)}
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
        ${btnHtml}
      </td>
    </tr>
  `;
            }).join('');


            // Répartition par service
            renderServiceBreakdown(serviceData);

            // Graphique
            renderSalaryChart(employeeDetails);
        }

        function renderServiceBreakdown(serviceData) {
            const breakdown = document.getElementById('serviceBreakdown');
            breakdown.innerHTML = Object.entries(serviceData).map(([service, data]) => `
                <div class="bg-white border border-gray rounded-lg p-4 text-center">
                    <div class="text-lg font-semibold text-primary mb-2">${service}</div>
                    <div class="text-2xl font-bold  mb-1">${formatCurrency(data.totalSalary)}</div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-users mr-1"></i>${data.count} employé${data.count > 1 ? 's' : ''}
                    </div>
                </div>
            `).join('');
        }

        function renderSalaryChart(employeeDetails) {
            const ctx = document.getElementById('salaryChart').getContext('2d');

            if (salaryChart) {
                salaryChart.destroy();
            }

            const labels = employeeDetails.map(detail => `${detail.employee.firstName} ${detail.employee.lastName}`);
            const baseSalaries = employeeDetails.map(detail => detail.employee.baseSalary);
            const gains = employeeDetails.map(detail => detail.gains);
            const deductions = employeeDetails.map(detail => detail.deductions);

            salaryChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Salaire de Base',
                            data: baseSalaries,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Gains Variables',
                            data: gains,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Retenues',
                            data: deductions.map(d => -d),
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' F CFA';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Répartition des Salaires par Employé'
                        }
                    }
                }
            });
        }

        // --- FONCTIONS UTILITAIRES ---
        function toggleTheme() {
            document.body.classList.toggle('dark');
            const icon = document.getElementById('themeIcon');
            icon.classList.toggle('fa-moon');
            icon.classList.toggle('fa-sun');
        }

        async function saveData() {
            const startEl = document.getElementById('periodStart');
            const endEl = document.getElementById('periodEnd');
            const ticketEl = document.getElementById('periodTicket');
            const replaceEl = document.getElementById('replaceTicket'); // peut ne pas exister

            const payload = {
                period: {
                    start: startEl?.value || '',
                    end: endEl?.value || ''
                },
                employeeData, // doit exister dans ton scope
                replace_ticket: replaceEl?.value || null
            };

            try {
                const res = await fetch("{{ route('payroll.saveByTicket') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(payload)
                });

                let data = {};
                try {
                    data = await res.json();
                } catch (_) {}

                // --- Erreurs ---
                if (!res.ok) {
                    if (res.status === 419) {
                        showNotification('Session expirée. Rafraîchis la page et réessaie.', 'error');
                        return;
                    }
                    if (res.status === 422) {
                        if (typeof handleValidationErrors === 'function') {
                            handleValidationErrors(data); // utilisera data.errors / data.first_error
                        } else {
                            showNotification(data.first_error || data.message || 'Données invalides', 'error');
                        }
                    } else {
                        showNotification(data.message || 'Erreur lors de la sauvegarde', 'error');
                    }
                    return;
                }

                // --- Succès ---
                if (typeof clearFieldErrors === 'function') clearFieldErrors();

                const mode = data?.data?.mode;
                const successMsg =
                    data?.message ??
                    (mode === 'renamed_and_replaced' ?
                        'Le ticket a été renommé et les données ont été remplacées.' :
                        mode === 'replaced' ?
                        'Ticket existant : données remplacées.' :
                        mode === 'created' ?
                        'Ticket créé : période enregistrée et lignes insérées.' :
                        'Données sauvegardées avec succès');

                showNotification(successMsg, 'success');

                // MAJ de l’input "Ticket généré"
                if (data?.data?.ticket && ticketEl) {
                    ticketEl.value = data.data.ticket;
                }

                // Rafraîchir la liste des tickets remplaçables (si présent)
                if (typeof loadReplaceableTickets === 'function') {
                    loadReplaceableTickets(); // éventuellement { excludeCurrent: true/false }
                }

                // Optionnel : reset la sélection "ticket à remplacer"
                if (replaceEl) replaceEl.value = '';

            } catch (error) {
                console.error('Erreur lors de la sauvegarde AJAX:', error);
                showNotification('Erreur réseau lors de la sauvegarde', 'error');
            }
        }


        function loadData() {
            const savedData = localStorage.getItem('payrollData');
            if (savedData) {
                const data = JSON.parse(savedData);
                employees = data.employees || employees;
                payrollVariables = data.payrollVariables || payrollVariables;
                employeeData = data.employeeData || {};

                if (data.period) {
                    document.getElementById('periodStart').value = data.period.start || '';
                    document.getElementById('periodEnd').value = data.period.end || '';
                }

                showNotification('Données chargées avec succès', 'success');
            }
        }

        // // --- INITIALISATION ---
        // document.addEventListener('DOMContentLoaded', () => {
        //     // Charger les données sauvegardées
        //     loadData();

        //     // Définir les dates par défaut
        //     const today = new Date();
        //     const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        //     const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        //     if (!document.getElementById('periodStart').value) {
        //         document.getElementById('periodStart').value = firstDay.toISOString().split('T')[0];
        //     }
        //     if (!document.getElementById('periodEnd').value) {
        //         document.getElementById('periodEnd').value = lastDay.toISOString().split('T')[0];
        //     }

        //     // Navigation employé détaillée
        //     document.getElementById('prevEmployeeBtn').onclick = () => navigateEmployee('prev');
        //     document.getElementById('nextEmployeeBtn').onclick = () => navigateEmployee('next');

        //     // Initialiser la première vue
        //     switchTab('periode');
        // });
    </script>

    <script>
        // — util : normalise l’objet variable reçu
        function normalizeVariablePayload(p) {
            const normalized = {
                id: p.id ?? null,
                name: p.name ?? '',
                statutVariable: !!p.statutVariable,
                variableImposable: !!p.variableImposable,
                tauxVariable: p.tauxVariable ?? null,
                tauxVariableEntreprise: p.tauxVariableEntreprise ?? null,
                type: p.type ?? undefined,
                categoryId: p.categorie?.id || null,
                categoryName: p.categorie?.nom_categorie || '(Sans catégorie)',
                numeroVariable: p.numeroVariable ?? null,
            };
            console.log('normalizeVariablePayload - normalized:', normalized); // Ajout
            return normalized;
        }

        function ensureCategoryExists(cat) {
            if (!cat || !cat.id) return;
            if (!(rawCategories || []).some(c => c.id === cat.id)) {
                rawCategories.push({
                    id: cat.id,
                    nom_categorie: cat.nom_categorie
                });
            }
        }

        function renameEmployeeDataKey(oldName, newName) {
            if (!oldName || oldName === newName) return;
            for (const empId in (window.employeeData || {})) {
                const row = employeeData[empId];
                if (row && Object.prototype.hasOwnProperty.call(row, oldName)) {
                    row[newName] = row[oldName];
                    delete row[oldName];
                }
            }
        }

        function dropEmployeeDataForVariable(varName) {
            for (const empId in (window.employeeData || {})) {
                const row = employeeData[empId];
                if (row && Object.prototype.hasOwnProperty.call(row, varName)) {
                    delete row[varName];
                }
            }
        }

        function sortPayrollVariables() {
            payrollVariables.sort((a, b) => {
                const cA = (a.categoryName || '').localeCompare(b.categoryName || '');
                return cA !== 0 ? cA : (a.name || '').localeCompare(b.name || '');
            });
        }

        function refreshAllViews() {
            if (typeof renderVariablesGrid === 'function') renderVariablesGrid();
            if (typeof renderPayrollTable === 'function') renderPayrollTable();
            if (typeof renderEmployeeSelector === 'function' && typeof renderEmployeeDetail === 'function') {
                renderEmployeeSelector();
                renderEmployeeDetail();
            }
            if (typeof calculateSynthesis === 'function') calculateSynthesis();
        }


        // — upsert / delete

        function upsertPayrollVariable(payload) {
            const v = normalizeVariablePayload(payload);
            if (payload.categorie) ensureCategoryExists(payload.categorie);

            const idx = payrollVariables.findIndex(x => x.id === v.id);
            console.log('upsertPayrollVariable - index trouvé:', idx); // Ajout

            if (idx >= 0) {
                const oldName = payrollVariables[idx].name;
                if (oldName !== v.name) renameEmployeeDataKey(oldName, v.name);
                payrollVariables[idx] = v;
                console.log('upsertPayrollVariable - variable mise à jour:', v); // Ajout
            } else {
                payrollVariables.push(v);
                console.log('upsertPayrollVariable - variable ajoutée:', v); // Ajout
            }
            sortPayrollVariables();
            sortPayrollVariables();
            refreshAllViews();
        }

        function removePayrollVariableById(id) {
            const idx = payrollVariables.findIndex(x => x.id === id);
            if (idx === -1) return;
            dropEmployeeDataForVariable(payrollVariables[idx]?.name);
            payrollVariables.splice(idx, 1);
            refreshAllViews();
        }

        // — listeners robustes (Livewire v3 browser event)
        function handleUpsertEvt(e) {
            const payload = e?.detail?.payload ?? e?.detail ?? null;
            console.log('handleUpsertEvt - payload:', payload); // Add this
            if (!payload) {
                console.warn('variable-upserted sans payload', e);
                return;
            }
            upsertPayrollVariable(payload);
        }

        function handleDeleteEvt(e) {
            const id = e?.detail?.id ?? e?.detail ?? null;
            if (!id) {
                console.warn('variable-deleted sans id', e);
                return;
            }
            removePayrollVariableById(id);
        }

        window.addEventListener('variable-upserted', handleUpsertEvt);
        window.addEventListener('variable-deleted', handleDeleteEvt);

        // Fallback Livewire.on (au cas où)
        document.addEventListener('livewire:init', () => {
            if (window.Livewire?.on) {
                Livewire.on('variable-upserted', payload => handleUpsertEvt({
                    detail: {
                        payload
                    }
                }));
                Livewire.on('variable-deleted', id => handleDeleteEvt({
                    detail: {
                        id
                    }
                }));
            }
        });
    </script>
@endsection
