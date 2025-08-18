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
            --primary-color: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --dark-bg: #1f2937;
            --dark-card: #374151;
            --dark-text: #f9fafb;
        }

        .dark {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }

        .dark .bg-white {
            background-color: var(--dark-card) !important;
            color: var(--dark-text);
        }

        .dark .border-gray-200 {
            border-color: #4b5563 !important;
        }

        .dark .text-gray-600 {
            color: #d1d5db !important;
        }

        .dark .text-gray-800 {
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

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
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

            body {
                font-size: 12px;
            }

            .container {
                max-width: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-calculator text-white text-2xl"></i>
                    <h1 class="text-2xl font-bold text-white">Système de Gestion de Paie Complet</h1>
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm">Version Finale</span>
                </div>
                <div class="flex items-center space-x-4 no-print">
                    <button onclick="toggleTheme()"
                        class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors">
                        <i id="themeIcon" class="fas fa-moon"></i>
                    </button>
                    <button onclick="saveData()"
                        class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-save mr-2"></i>Sauvegarder
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-6 space-y-6">
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
            </div>
        </div>

        <!-- Tab: Période de Paie -->
        <div id="content-periode" class="tab-content animate-fade-in">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Configuration de la Période de Paie
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>
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
                <div class="p-6 border-b border-gray-200">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-600"></i>Gestion des Employés
                        </h2>
                        <div class="flex flex-wrap gap-2 no-print">
                            <button onclick="showAddEmployeeModal()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Ajouter Employé
                            </button>
                            <button onclick="importEmployees()"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
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
                <div class="p-6 border-b border-gray-200">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-list mr-2 text-blue-600"></i>Variables de Paie
                        </h2>
                        <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddVariable"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                            <i class="fas fa-plus mr-2"></i>Ajouter Variable
                        </button>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBackdrop3"
                            aria-controls="offcanvasWithBackdrop3"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                            <i class="fas fa-plus mr-2"></i>Ajouter une categorie
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="variablesContainer">
                    </div>
                </div>
            </div>
        </div>
        @include('components.yodirh._categorie')
        @include('components.yodirh._variable',$categories)
        <!-- Tab: Saisie Globale -->
        <div id="content-saisie-globale" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-table mr-2 text-blue-600"></i>Saisie Globale des Variables
                        </h2>
                        <div class="flex flex-wrap gap-2 no-print">
                            <button onclick="calculateAll()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
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
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-user-edit mr-2 text-blue-600"></i>Saisie Détaillée par Employé
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
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
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
                                <span id="selectedEmployeeId" class="ml-2 text-gray-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Salaire de Base:</span>
                                <span id="selectedEmployeeSalary" class="ml-2 text-gray-800 font-semibold"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Service:</span>
                                <span id="selectedEmployeeService" class="ml-2 text-gray-800"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire des variables -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Gains -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
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
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
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
                                <div id="recapBaseSalary" class="text-lg font-bold text-gray-800">0 F CFA</div>
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
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Synthèse de la Paie
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">Masse Salariale de Base</p>
                                    <p class="text-2xl font-bold" id="summaryBaseSalary">0 F CFA</p>
                                </div>
                                <i class="fas fa-money-bill-wave text-3xl text-blue-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm">Total Gains Variables</p>
                                    <p class="text-2xl font-bold" id="summaryVariableGains">0 F CFA</p>
                                </div>
                                <i class="fas fa-arrow-up text-3xl text-green-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-100 text-sm">Total Retenues</p>
                                    <p class="text-2xl font-bold" id="summaryTotalDeductions">0 F CFA</p>
                                </div>
                                <i class="fas fa-arrow-down text-3xl text-red-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
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
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition de la Masse Salariale</h3>
                        <div class="chart-container">
                            <canvas id="salaryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Détail par employé -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-blue-600"></i>Détail par Employé
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-600"></i>Répartition par Service
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
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-blue-600"></i>Ajouter un Employé
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
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button onclick="closeAddEmployeeModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">Annuler</button>
                    <button onclick="addEmployee()"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Ajouter Variable -->
    <div id="addVariableModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 no-print">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-plus mr-2 text-blue-600"></i>Ajouter une Variable
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la variable</label>
                        <input type="text" id="newVariableName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus"
                            placeholder="Prime de performance">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="newVariableType"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                            <option value="gain">Gain (Prime, Indemnité...)</option>
                            <option value="deduction">Retenue (Acompte, Amende...)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select id="newVariableCategory"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                            <option value="Primes">Primes</option>
                            <option value="Indemnités">Indemnités</option>
                            <option value="Heures supplémentaires">Heures supplémentaires</option>
                            <option value="Avantages">Avantages en nature</option>
                            <option value="Retenues">Retenues diverses</option>
                        </select>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button onclick="closeAddVariableModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">Annuler</button>
                    <button onclick="addVariable()"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

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

        let payrollVariables = [{
                name: "Prime de performance",
                type: "gain",
                category: "Primes"
            },
            {
                name: "Prime d'ancienneté",
                type: "gain",
                category: "Primes"
            },
            {
                name: "Prime de 13ème mois",
                type: "gain",
                category: "Primes"
            },
            {
                name: "Heures supplémentaires",
                type: "gain",
                category: "Heures supplémentaires"
            },
            {
                name: "Commissions sur ventes",
                type: "gain",
                category: "Primes"
            },
            {
                name: "Indemnité de transport",
                type: "gain",
                category: "Indemnités"
            },
            {
                name: "Indemnité de repas",
                type: "gain",
                category: "Indemnités"
            },
            {
                name: "Prime de risque",
                type: "gain",
                category: "Primes"
            },
            {
                name: "Acompte sur salaire",
                type: "deduction",
                category: "Retenues"
            },
            {
                name: "Avance sur salaire",
                type: "deduction",
                category: "Retenues"
            },
            {
                name: "Absence non rémunérée",
                type: "deduction",
                category: "Retenues"
            },
            {
                name: "Retard/Sanctions",
                type: "deduction",
                category: "Retenues"
            },
            {
                name: "Saisie sur salaire",
                type: "deduction",
                category: "Retenues"
            }
        ];

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
                        <button onclick="editEmployee('${emp.id}')" class="text-blue-600 hover:text-blue-900 mr-3">
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
                    return 'bg-gray-100 text-gray-800';
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

        // --- GESTION DES VARIABLES ---
        function renderVariablesGrid() {
            const container = document.getElementById('variablesContainer');

            const groupedVariables = payrollVariables.reduce((acc, variable, index) => {
                if (!acc[variable.category]) {
                    acc[variable.category] = [];
                }
                acc[variable.category].push({
                    ...variable,
                    index
                });
                return acc;
            }, {});

            container.innerHTML = Object.entries(groupedVariables).map(([category, variables]) => `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-folder mr-2 text-blue-600"></i>${category}
                    </h4>
                    <div class="space-y-2">
                        ${variables.map(variable => `
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
                    </div>
                </div>
            `).join('');
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
        

        function deleteVariable(index) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette variable ?')) {
                payrollVariables.splice(index, 1);
                renderVariablesGrid();
                showNotification('Variable supprimée avec succès', 'success');
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
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-bold text-blue-600" data-net-salary="${employee.id}">
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
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">${emp.lastName} ${emp.firstName}</div>
                            <div class="text-sm text-gray-600">${emp.id} • ${emp.department}</div>
                            <div class="text-sm font-medium text-blue-600">${formatCurrency(emp.baseSalary)}</div>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-blue-600">${formatCurrency(detail.netSalary)}</td>
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
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <div class="text-lg font-semibold text-gray-800 mb-2">${service}</div>
                    <div class="text-2xl font-bold text-blue-600 mb-1">${formatCurrency(data.totalSalary)}</div>
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

        function saveData() {
            const data = {
                employees,
                payrollVariables,
                employeeData,
                period: {
                    start: document.getElementById('periodStart').value,
                    end: document.getElementById('periodEnd').value
                }
            };

            localStorage.setItem('payrollData', JSON.stringify(data));
            showNotification('Données sauvegardées avec succès', 'success');
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
