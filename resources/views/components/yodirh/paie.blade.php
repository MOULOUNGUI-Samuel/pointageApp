{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion de Paie Complet</title>
</head>
<body class="bg-gray-50 min-h-screen"> --}}
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tickets pouvant être remplacés</label>
                        <select id="replaceTicket" class="form-select">
                            <option value="">Choix du ticket</option>
                            {{-- tu peux pré-remplir côté Blade si tu veux --}}
                        </select>
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
                                <i class="fas fa-file-excel mr-2"></i>Importer Excel
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 no-print">
                        <input type="text" id="searchEmployee" placeholder="Rechercher un employé..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                            oninput="filterEmployees()">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
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
                            <button type="button" data-bs-toggle="modal" data-bs-target="#floatingLabelsModal"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                                <i class="fas fa-plus mr-2"></i>Ajouter Variable
                            </button>
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
                </div>
            </div>
        </div>
        @include('components.yodirh._categorie')
        @include('components.yodirh._variable', $categories)
        <!-- Tab: Saisie Globale -->
        <div id="content-saisie-globale" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-primary flex items-center">
                            <i class="fas fa-table mr-2 "></i>Saisie Globale des Variables
                        </h2>
                        <div class="flex flex-wrap gap-2 no-print">
                            <button onclick="calculateAll()"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-calculator mr-2"></i>Calculer Tout
                            </button>
                            <button onclick="resetAllInputs()"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full" id="payrollTable">
                        <thead class="bg-gray-50" id="payrollTableHead">
                        </thead>
                        <tbody id="payrollTableBody" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
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
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <i class="fas fa-users mr-2 "></i>Détail par Employé
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Employé</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Salaire Base</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gains Variables</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Retenues</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Salaire Net</th>
                                </tr>
                            </thead>
                            <tbody id="employeeDetailsBody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
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
    <div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 no-print">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-gray">
                    <h3 class="text-lg font-semibold text-primary flex items-center">
                        <i class="fas fa-user-plus mr-2 "></i>Ajouter un Employé
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                        <input type="text" id="newEmployeeLastName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus"
                            placeholder="Nom de famille">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                        <input type="text" id="newEmployeeFirstName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus" placeholder="Prénom">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poste</label>
                        <input type="text" id="newEmployeePosition"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus"
                            placeholder="Poste occupé">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                        <select id="newEmployeeDepartment"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                            <option value="Administration">Administration</option>
                            <option value="Comptabilité">Comptabilité</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Technique">Technique</option>
                            <option value="RH">Ressources Humaines</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salaire de Base (F CFA)</label>
                        <input type="number" id="newEmployeeBaseSalary"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus" placeholder="200000"
                            min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select id="newEmployeeStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                            <option value="Stage">Stage</option>
                            <option value="Consultant">Consultant</option>
                        </select>
                    </div>
                </div>
                <div class="p-6 border-t border-gray flex justify-end space-x-3">
                    <button onclick="closeAddEmployeeModal()"
                        class="px-4 py-2 text-gray-600 hover:text-primary transition-colors">Annuler</button>
                    <button onclick="addEmployee()"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Ajouter</button>
                </div>
            </div>
        </div>
    </div>



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
            id: u.matricule ?? u.id, // choisis l’un des deux
            firstName: u.prenom ?? '—',
            lastName: u.nom ?? '—',
            position: u.fonction // champ direct si présent
                ??
                u.categorie_professionnelle?.nom_categorie_professionnelle // depuis la relation
                ??
                '—',
            department: u.service?.nom_service ?? '—', // vérifie le nom du champ de ta table service
            role: u.role?.nom ?? '—',
            baseSalary: u.salaire ? Number(u.salaire) : null, // "350000" -> 350000
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

            tbody.innerHTML = filteredEmployees.map(emp => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${emp.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${emp.lastName} ${emp.firstName}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${emp.position}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${emp.department}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${formatCurrency(emp.baseSalary)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(emp.status)}">
                            ${emp.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print">
                        <button onclick="editEmployee('${emp.id}')" class=" hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteEmployee('${emp.id}')" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
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

        function filterEmployees() {
            renderEmployeesTable();
        }

        function showAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.remove('hidden');
        }

        function closeAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.add('hidden');
            document.getElementById('newEmployeeLastName').value = '';
            document.getElementById('newEmployeeFirstName').value = '';
            document.getElementById('newEmployeePosition').value = '';
            document.getElementById('newEmployeeDepartment').value = 'Administration';
            document.getElementById('newEmployeeBaseSalary').value = '';
            document.getElementById('newEmployeeStatus').value = 'CDI';
        }

        function addEmployee() {
            const lastName = document.getElementById('newEmployeeLastName').value.trim();
            const firstName = document.getElementById('newEmployeeFirstName').value.trim();
            const position = document.getElementById('newEmployeePosition').value.trim();
            const department = document.getElementById('newEmployeeDepartment').value;
            const baseSalary = parseFloat(document.getElementById('newEmployeeBaseSalary').value);
            const status = document.getElementById('newEmployeeStatus').value;

            if (!lastName || !firstName || !position || !baseSalary || baseSalary <= 0) {
                showNotification('Veuillez remplir tous les champs obligatoires', 'error');
                return;
            }

            const newEmployee = {
                id: generateEmployeeId(),
                firstName,
                lastName,
                position,
                department,
                baseSalary,
                status
            };

            employees.push(newEmployee);
            renderEmployeesTable();
            closeAddEmployeeModal();
            showNotification('Employé ajouté avec succès', 'success');
        }

        function deleteEmployee(employeeId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')) {
                employees = employees.filter(emp => emp.id !== employeeId);
                renderEmployeesTable();
                showNotification('Employé supprimé avec succès', 'success');
            }
        }

        function editEmployee(employeeId) {
            const employee = employees.find(emp => emp.id === employeeId);
            if (!employee) return;

            document.getElementById('newEmployeeLastName').value = employee.lastName;
            document.getElementById('newEmployeeFirstName').value = employee.firstName;
            document.getElementById('newEmployeePosition').value = employee.position;
            document.getElementById('newEmployeeDepartment').value = employee.department;
            document.getElementById('newEmployeeBaseSalary').value = employee.baseSalary;
            document.getElementById('newEmployeeStatus').value = employee.status;

            deleteEmployee(employeeId);
            showAddEmployeeModal();
        }

        function importEmployees() {
            showNotification('Fonctionnalité d\'import Excel en cours de développement', 'info');
        }

        function renderVariablesGrid() {
            const container = document.getElementById('variablesContainer');

            // 1) Préparer les groupes avec toutes les catégories (même vides)
            const groupsMap = new Map();
            (rawCategories || []).forEach(cat => {
                groupsMap.set(cat.id, {
                    label: cat.nom_categorie,
                    items: [],
                    catId: cat.id
                });
            });

            // 2) Groupe spécial pour les variables sans catégorie
            const UNCAT_KEY = '__uncat__';

            // 3) Répartir les variables dans leurs groupes
            (payrollVariables || []).forEach((variable, index) => {
                const item = {
                    ...variable,
                    index
                }; // on garde l’index pour deleteVariable(index)
                if (variable.categoryId && groupsMap.has(variable.categoryId)) {
                    groupsMap.get(variable.categoryId).items.push(item);
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

            // 4) Construire le HTML (ordre: catégories connues puis "(Sans catégorie)" s'il existe)
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
                                                      ${group.items.map(variable => `
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border ${getVariableClass(variable.type)}">
                  <div class="flex items-center">
                    <i class="fas ${getVariableIcon(variable.type)} mr-2 ${getVariableIconColor(variable.type)}"></i>
                    <span class="text-sm font-medium">${variable.name}</span>
                  </div>
                  <button onclick="deleteVariable(${variable.index})" class="text-red-500 hover:text-red-700 no-print">
                    <i class="fas fa-times"></i>
                  </button>
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

        async function addVariable() {
            const name = document.getElementById('newVariableName').value.trim();
            const type = document.getElementById('newVariableType').value;
            const category = document.getElementById('newVariableCategory').value;

            if (!name) {
                showNotification('Veuillez entrer un nom pour la variable', 'error');
                return;
            }

            // *** NOUVELLE SECTION : APPEL AJAX ***
            try {
                const res = await fetch("{{ route('variables.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        nom_variable: name,
                        type: type,
                        categorie_id: category
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    showNotification(data.message || 'Erreur lors de l\'ajout de la variable', 'error');
                    return;
                }

                // après succès AJAX de création
                const created = data.data; // { id, nom_variable, type, categorie:{ id, nom_categorie } }

                payrollVariables.push({
                    id: created.id,
                    name: created.nom_variable,
                    type: created.type,
                    categoryId: created.categorie ? created.categorie.id : null,
                    categoryName: created.categorie ? created.categorie.nom_categorie : '(Sans catégorie)'
                });

                renderVariablesGrid();
                closefloatingLabelsModal();
                showNotification('Variable ajoutée avec succès', 'success');

            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de l\'ajout de la variable (réseau)', 'error');
            }
            // *** FIN NOUVELLE SECTION ***
        }

        function deleteVariable(index) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette variable ?')) {
                const variableToDelete = payrollVariables[index];

                // *** NOUVELLE SECTION : APPEL AJAX ***
                fetch("{{ route('variables.destroy', ['id' => '__ID__']) }}".replace('__ID__', variableToDelete
                        .id), { // IMPORTANT : remplacer __ID__
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la suppression de la variable.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Si la suppression sur le serveur réussit, on peut supprimer la variable du tableau et recharger
                        payrollVariables.splice(index, 1);
                        renderVariablesGrid();
                        showNotification('Variable supprimée avec succès', 'success');
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Erreur lors de la suppression de la variable (réseau)', 'error');
                    });
                // *** FIN NOUVELLE SECTION ***
            }
        }

        // --- SAISIE GLOBALE ---
        function renderPayrollTable() {
            const thead = document.getElementById('payrollTableHead');
            const tbody = document.getElementById('payrollTableBody');

            let headerHTML = `
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Employé</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Salaire de Base</th>
            `;

            payrollVariables.forEach(variable => {
                headerHTML += `
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider ${getVariableIconColor(variable.type)}" title="${variable.category}">
                        ${variable.name}
                    </th>
                `;
            });

            headerHTML += `
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net à Payer</th>
                </tr>
            `;

            thead.innerHTML = headerHTML;

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

                payrollVariables.forEach((variable, index) => {
                    const value = employeeData[employee.id] && employeeData[employee.id][variable.name] ||
                        0;
                    rowHTML += `
                        <td class="px-4 py-4 whitespace-nowrap">
                            <input type="number" 
                                   class="w-24 px-2 py-1 text-sm text-right border border-gray-300 rounded input-focus" 
                                   placeholder="0" 
                                   min="0" 
                                   step="1000"
                                   value="${value}"
                                   data-employee-id="${employee.id}" 
                                   data-variable-name="${variable.name}"
                                   onchange="handleGlobalInput(this)">
                        </td>
                    `;
                });

                const netSalary = calculateEmployeeNet(employee.id);
                rowHTML += `
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-bold " data-net-salary="${employee.id}">
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
                            <div class="text-sm text-gray-600">${emp.id} • ${emp.department}</div>
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
            document.getElementById('selectedEmployeeId').textContent = emp.id;
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
                const value = employeeData[emp.id] && employeeData[emp.id][variable.name] || 0;

                const fieldHTML = `
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">${variable.name}</label>
                        <div class="relative">
                            <input type="number" 
                                   class="detail-variable-input w-full px-4 py-3 border border-gray-300 rounded-lg text-right text-lg input-focus" 
                                   placeholder="0" 
                                   min="0" 
                                   value="${value}"
                                   data-variable-name="${variable.name}"
                                   oninput="handleDetailInput(this)">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">F CFA</span>
                            </div>
                        </div>
                    </div>
                `;

                if (variable.type === 'gain') {
                    gainsForm.innerHTML += fieldHTML;
                } else {
                    deductionsForm.innerHTML += fieldHTML;
                }
            });
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
            tbody.innerHTML = employeeDetails.map(detail => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${detail.employee.lastName} ${detail.employee.firstName}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">${formatCurrency(detail.employee.baseSalary)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">${formatCurrency(detail.gains)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">${formatCurrency(detail.deductions)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold ">${formatCurrency(detail.netSalary)}</td>
                </tr>
            `).join('');

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

        // --- INITIALISATION ---
        document.addEventListener('DOMContentLoaded', () => {
            // Charger les données sauvegardées
            loadData();

            // Définir les dates par défaut
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            if (!document.getElementById('periodStart').value) {
                document.getElementById('periodStart').value = firstDay.toISOString().split('T')[0];
            }
            if (!document.getElementById('periodEnd').value) {
                document.getElementById('periodEnd').value = lastDay.toISOString().split('T')[0];
            }

            // Navigation employé détaillée
            document.getElementById('prevEmployeeBtn').onclick = () => navigateEmployee('prev');
            document.getElementById('nextEmployeeBtn').onclick = () => navigateEmployee('next');

            // Initialiser la première vue
            switchTab('periode');
        });
    </script>
@endsection
{{-- </body>
</html> --}}
