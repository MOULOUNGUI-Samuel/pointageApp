<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH & Paie Consolidé BFEV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active {
            background: linear-gradient(135deg, #979796 0%, #fefefd 100%);
            color: black;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .badge-success {
            background: #10b981;
            color: white;
        }

        .badge-warning {
            background: #f59e0b;
            color: white;
        }

        .badge-danger {
            background: #ef4444;
            color: white;
        }

        .badge-info {
            background: #05436b;
            color: white;
        }

        .alert-critical {
            border-left: 4px solid #ef4444;
            background: #fef2f2;
        }

        .alert-major {
            border-left: 4px solid #f59e0b;
            background: #fffbeb;
        }

        .alert-minor {
            border-left: 4px solid #05436b;
            background: #eff6ff;
        }

        .heatmap-cell {
            transition: all 0.3s ease;
        }

        .heatmap-cell:hover {
            transform: scale(1.1);
        }

        .module-section {
            display: none;
        }

        .module-section.active {
            display: block;
        }

        .employee-card:hover {
            background: #f9fafb;
            cursor: pointer;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 from-blue-900 to-purple-900 text-white flex-shrink-0 no-print" style="background: #05436b">
            <div class="p-6">
                <a href="#" class="logo-small">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" class="rounded" alt="Logo">
                </a>
            </div>
            <nav class="mt-6">
                <a href="#"
                    class="sidebar-link active flex items-center px-6 py-3 text-white hover:bg-secondary-800"
                    data-module="dashboard">
                    <i class="fas fa-home mr-3"></i> Tableau de bord
                </a>
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
                    data-module="effectifs">
                    <i class="fas fa-users mr-3"></i> Effectifs & Structure
                </a>
<<<<<<< HEAD
                {{-- <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
                    data-module="contrats">
                    <i class="fas fa-file-contract mr-3"></i> Contrats & Conformité
                </a> --}}
=======
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
                    data-module="contrats">
                    <i class="fas fa-file-contract mr-3"></i> Contrats & Conformité
                </a>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
                    data-module="pointage">
                    <i class="fas fa-clock mr-3"></i> Pointage & Temps
                </a>
<<<<<<< HEAD
                {{-- <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
=======
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                    data-module="paie">
                    <i class="fas fa-money-bill-wave mr-3"></i> Paie & Masse salariale
                </a>
                <a href="#" class="sidebar-link flex items-center px-6 py-3 text-white hover:bg-blue-800"
                    data-module="reporting">
                    <i class="fas fa-file-export mr-3"></i> Reporting & Export
<<<<<<< HEAD
                </a> --}}
=======
                </a>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
            </nav>
            <div class="absolute bottom-0 w-64 p-6 text-xs text-blue-200">
                <p>&copy; 2025 Yodingenierie</p>
                <p>Version 2.1.0</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm p-6 no-print">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800" id="module-title">Tableau de bord principal</h2>
                        <p class="text-gray-500 text-sm mt-1" id="module-subtitle">Vue consolidée BFEV - 5 sociétés</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <select id="company-filter" data-base-url="{{ route('index-consolider') }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all" {{ empty($currentEntrepriseId) ? 'selected' : '' }}>Toutes les
                                    sociétés</option>
                                @foreach ($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}"
                                        {{ isset($currentEntrepriseId) && $currentEntrepriseId === $entreprise->id ? 'selected' : '' }}>
                                        {{ $entreprise->nom_entreprise }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
<<<<<<< HEAD
                            <i class="fas fa-bell mr-2"></i> 
                            {{-- <span class="badge-danger px-2 py-1 rounded-full text-xs"></span> --}}
                        </button>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700">{{Auth::user()->nom}}</p>
                            <p class="text-xs text-gray-500">{{Auth::user()->fonction}}</p>
=======
                            <i class="fas fa-bell mr-2"></i> <span
                                class="badge-danger px-2 py-1 rounded-full text-xs">12</span>
                        </button>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700">Admin BFEV</p>
                            <p class="text-xs text-gray-500">14 octobre 2025</p>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                        </div>
                    </div>
                </div>
            </header>
            <script>
                (function () {
                  const sel = document.getElementById('company-filter');
                  if (!sel) return;
              
                  const baseUrl = sel.dataset.baseUrl || '{{ route('index-consolider') }}';
              
                  sel.addEventListener('change', () => {
                    const val = sel.value;
                    if (!val || val === 'all') {
                      // /consolider  (paramètre optionnel omis)
                      window.location.href = baseUrl;
                    } else {
                      // /consolider/{entreprise_id}
                      window.location.href = baseUrl.replace(/\/$/, '') + '/' + encodeURIComponent(val);
                    }
                  });
                })();
              </script>
              
            <!-- Dashboard Module -->
            <section id="dashboard-module" class="module-section active p-6">
                <!-- KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Effectif Total</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ count($employeesData) }}</h3>
<<<<<<< HEAD
                                <p class="text-green-500 text-xs mt-2"><i class="fas fa-arrow-up"></i> ---</p>
=======
                                <p class="text-green-500 text-xs mt-2"><i class="fas fa-arrow-up"></i> +12 ce mois</p>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Masse Salariale</p>
<<<<<<< HEAD
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">---</h3>
=======
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">127M</h3>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                                <p class="text-gray-500 text-xs mt-2">XOF / mois</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Conformité RH</p>
<<<<<<< HEAD
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">---</h3>
                                <p class="text-orange-500 text-xs mt-2"><i class="fas fa-exclamation-triangle"></i>--</p>
=======
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">78%</h3>
                                <p class="text-orange-500 text-xs mt-2"><i class="fas fa-exclamation-triangle"></i> 23
                                    alertes</p>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                            </div>
                            <div class="bg-orange-100 p-3 rounded-full">
                                <i class="fas fa-shield-alt text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Taux Assiduité</p>
<<<<<<< HEAD
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">---</h3>
                                <p class="text-green-500 text-xs mt-2"><i class="fas fa-arrow-up"></i> --</p>
=======
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">94.2%</h3>
                                <p class="text-green-500 text-xs mt-2"><i class="fas fa-arrow-up"></i> +1.3%</p>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-clock text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Effectifs par Société</h3>
                        <canvas id="effectifChart"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
<<<<<<< HEAD
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution Masse Salariale <span style="color: #05436b;font-size:15px">(<i>non disponible</i></span>)</h3>
=======
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution Masse Salariale (6 mois)</h3>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                        <canvas id="masseSalarialeChart"></canvas>
                    </div>
                </div>

                <!-- Alerts Row -->
<<<<<<< HEAD
                {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
=======
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-red-100 p-2 rounded-full mr-3"><i
                                    class="fas fa-exclamation-circle text-red-600"></i></span>
                            Alertes Critiques
                        </h3>
                        <div class="space-y-3">
                            <div class="alert-critical p-3 rounded">
                                <p class="font-semibold text-sm text-gray-800">8 contrats expirés</p>
                                <p class="text-xs text-gray-600">EGCC - Action immédiate requise</p>
                            </div>
                            <div class="alert-critical p-3 rounded">
                                <p class="font-semibold text-sm text-gray-800">Retard CNSS (3 mois)</p>
                                <p class="text-xs text-gray-600">YOD Bénin - Risque sanctions</p>
                            </div>
                            <div class="alert-critical p-3 rounded">
                                <p class="font-semibold text-sm text-gray-800">5 documents réglementaires manquants</p>
                                <p class="text-xs text-gray-600">COMKETING - Conformité légale</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-orange-100 p-2 rounded-full mr-3"><i
                                    class="fas fa-exclamation-triangle text-orange-600"></i></span>
                            Alertes Majeures
                        </h3>
                        <div class="space-y-3">
                            <div class="alert-major p-3 rounded">
                                <p class="font-semibold text-sm text-gray-800">12 contrats à renouveler (30j)</p>
                                <p class="text-xs text-gray-600">Multi-sociétés</p>
                            </div>
                            <div class="alert-major p-3 rounded">
                                <p class="font-semibold text-sm text-gray-800">Anomalies pointage (>5j)</p>
                                <p class="text-xs text-gray-600">Ingenium - 7 employés</p>
                            </div>
                            <div class="alert-major p-3 rounded">
                                <p class="font-semibold text-sm text-gray-800">Application SMIG non conforme</p>
                                <p class="text-xs text-gray-600">EZER Immo - 3 postes</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Indice Conformité Global</h3>
                        <div id="conformityGauge" class="flex justify-center"></div>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Contrats</span>
                                <span class="font-semibold text-green-600">85%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Documents légaux</span>
                                <span class="font-semibold text-orange-600">72%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Déclarations sociales</span>
                                <span class="font-semibold text-red-600">68%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Code du travail</span>
                                <span class="font-semibold text-green-600">88%</span>
                            </div>
                        </div>
                    </div>
<<<<<<< HEAD
                </div> --}}
=======
                </div>
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
            </section>

            <!-- Effectifs Module -->
            <section id="effectifs-module" class="module-section p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Liste des Employés [
                                {{ count($employeesData) }} ]</h3>
                            <input type="text" id="employee-search" placeholder="Rechercher..."
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow">
                        </div>
                        <div class="overflow-x-auto" style="max-height: 1000px; overflow-y: auto;">
                            <table class="w-full text-sm" id="employees-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100">Nom <i
                                                class="fas fa-sort text-gray-400"></i></th>
                                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100">Société <i
                                                class="fas fa-sort text-gray-400"></i></th>
                                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100">Poste <i
                                                class="fas fa-sort text-gray-400"></i></th>
                                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100">Service <i
                                                class="fas fa-sort text-gray-400"></i></th>
                                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100">Ancienneté <i
                                                class="fas fa-sort text-gray-400"></i></th>
                                        <th class="px-4 py-3 text-left">Statut</th>
                                    </tr>
                                </thead>
                                <tbody id="employees-tbody" class="divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pyramide des âges</h3>
                        <canvas id="pyramideAgesChart"></canvas>
                    </div>
                </div>

                <!-- Employee Modal -->
                <div id="employee-modal"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-2xl font-bold text-gray-800">Fiche Employé</h3>
                            <button onclick="closeEmployeeModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div id="employee-details" class="space-y-4"></div>
                    </div>
                </div>
            </section>

            <!-- Contrats & Conformité Module -->
            <section id="contrats-module" class="module-section p-6">
                <!-- Conformité Dashboard -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Tableau de bord Conformité RH & Légale</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">Société</th>
                                    <th class="px-4 py-3 text-center">Score Global</th>
                                    <th class="px-4 py-3 text-center">Contrats</th>
                                    <th class="px-4 py-3 text-center">Docs Légaux</th>
                                    <th class="px-4 py-3 text-center">Décl. Sociales</th>
                                    <th class="px-4 py-3 text-center">Code Travail</th>
                                    <th class="px-4 py-3 text-center">Alertes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">Ingenium</td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-success px-3 py-1 rounded-full text-xs">82%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-success px-3 py-1 rounded-full text-xs">88%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">78%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-success px-3 py-1 rounded-full text-xs">85%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-success px-3 py-1 rounded-full text-xs">90%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-info px-2 py-1 rounded-full text-xs">3</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">EZER Immo</td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">74%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">79%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">65%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">72%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">78%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-2 py-1 rounded-full text-xs">8</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">COMKETING</td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">76%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-success px-3 py-1 rounded-full text-xs">84%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">68%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">75%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-success px-3 py-1 rounded-full text-xs">82%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-2 py-1 rounded-full text-xs">6</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">YOD Bénin</td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">62%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">71%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">58%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">52%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">68%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-2 py-1 rounded-full text-xs">12</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">EGCC</td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">68%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">65%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">72%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-3 py-1 rounded-full text-xs">63%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-warning px-3 py-1 rounded-full text-xs">71%</span></td>
                                    <td class="px-4 py-3 text-center"><span
                                            class="badge-danger px-2 py-1 rounded-full text-xs">11</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Alertes Prioritaires & Radar -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alertes Prioritaires</h3>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <div class="alert-critical p-4 rounded hover:shadow-md transition cursor-pointer"
                                onclick="showAnomalyDetail('AN001')">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge-danger px-2 py-1 rounded text-xs mr-2">CRITIQUE</span>
                                            <span class="text-xs text-gray-500">EGCC</span>
                                        </div>
                                        <p class="font-semibold text-sm">8 contrats non signés depuis 45 jours</p>
                                        <p class="text-xs text-gray-600 mt-1">Impact juridique majeur - Nullité
                                            possible</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                            <div class="alert-critical p-4 rounded hover:shadow-md transition cursor-pointer"
                                onclick="showAnomalyDetail('AN002')">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge-danger px-2 py-1 rounded text-xs mr-2">CRITIQUE</span>
                                            <span class="text-xs text-gray-500">YOD Bénin</span>
                                        </div>
                                        <p class="font-semibold text-sm">Retard déclarations CNSS (3 mois)</p>
                                        <p class="text-xs text-gray-600 mt-1">Risque sanctions financières + pénalités
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                            <div class="alert-major p-4 rounded hover:shadow-md transition cursor-pointer"
                                onclick="showAnomalyDetail('AN003')">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge-warning px-2 py-1 rounded text-xs mr-2">MAJEUR</span>
                                            <span class="text-xs text-gray-500">COMKETING</span>
                                        </div>
                                        <p class="font-semibold text-sm">5 documents CNAMGS manquants</p>
                                        <p class="text-xs text-gray-600 mt-1">Non-conformité couverture santé
                                            obligatoire</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                            <div class="alert-major p-4 rounded hover:shadow-md transition cursor-pointer"
                                onclick="showAnomalyDetail('AN004')">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge-warning px-2 py-1 rounded text-xs mr-2">MAJEUR</span>
                                            <span class="text-xs text-gray-500">EZER Immo</span>
                                        </div>
                                        <p class="font-semibold text-sm">3 postes sous SMIG légal</p>
                                        <p class="text-xs text-gray-600 mt-1">Infraction Code du Travail Art. 145</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                            <div class="alert-major p-4 rounded hover:shadow-md transition cursor-pointer"
                                onclick="showAnomalyDetail('AN005')">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge-warning px-2 py-1 rounded text-xs mr-2">MAJEUR</span>
                                            <span class="text-xs text-gray-500">Multi-sociétés</span>
                                        </div>
                                        <p class="font-semibold text-sm">12 CDD à renouveler (échéance <30j)< /p>
                                                <p class="text-xs text-gray-600 mt-1">Risque rupture continuité service
                                                </p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                            <div class="alert-minor p-4 rounded hover:shadow-md transition cursor-pointer"
                                onclick="showAnomalyDetail('AN006')">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge-info px-2 py-1 rounded text-xs mr-2">MINEUR</span>
                                            <span class="text-xs text-gray-500">Ingenium</span>
                                        </div>
                                        <p class="font-semibold text-sm">Visites médicales périodiques (8 salariés)</p>
                                        <p class="text-xs text-gray-600 mt-1">Obligation annuelle santé au travail</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Radar Score Conformité</h3>
                        <div id="conformityRadarChart"></div>
                        <div class="mt-4">
                            <h4 class="font-semibold text-sm text-gray-700 mb-2">Timeline Échéances Critiques</h4>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <span class="w-24 text-gray-500">20 Oct 2025</span>
                                    <div class="flex-1 ml-4">
                                        <div class="bg-red-100 px-3 py-1 rounded">Déclaration CNSS - YOD Bénin</div>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="w-24 text-gray-500">28 Oct 2025</span>
                                    <div class="flex-1 ml-4">
                                        <div class="bg-orange-100 px-3 py-1 rounded">Renouvellement CDD (x4) - EGCC
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="w-24 text-gray-500">05 Nov 2025</span>
                                    <div class="flex-1 ml-4">
                                        <div class="bg-orange-100 px-3 py-1 rounded">Régularisation SMIG - EZER</div>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="w-24 text-gray-500">15 Nov 2025</span>
                                    <div class="flex-1 ml-4">
                                        <div class="bg-blue-100 px-3 py-1 rounded">Visites médicales - Ingenium</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion Anomalies & Documentation -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Gestion des Anomalies</h3>
                            <select
                                class="px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                id="anomaly-filter">
                                <option value="all">Tous types</option>
                                <option value="critique">Critique</option>
                                <option value="majeur">Majeur</option>
                                <option value="mineur">Mineur</option>
                            </select>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left">ID</th>
                                        <th class="px-4 py-3 text-left">Type</th>
                                        <th class="px-4 py-3 text-left">Description</th>
                                        <th class="px-4 py-3 text-left">Société</th>
                                        <th class="px-4 py-3 text-left">Gravité</th>
                                        <th class="px-4 py-3 text-left">Statut</th>
                                        <th class="px-4 py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono text-xs">AN001</td>
                                        <td class="px-4 py-3">Contractuel</td>
                                        <td class="px-4 py-3">Contrats non signés</td>
                                        <td class="px-4 py-3">EGCC</td>
                                        <td class="px-4 py-3"><span
                                                class="badge-danger px-2 py-1 rounded text-xs">Critique</span></td>
                                        <td class="px-4 py-3"><span
                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">En
                                                cours</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="showAnomalyDetail('AN001')"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono text-xs">AN002</td>
                                        <td class="px-4 py-3">Déclarations</td>
                                        <td class="px-4 py-3">Retard CNSS</td>
                                        <td class="px-4 py-3">YOD Bénin</td>
                                        <td class="px-4 py-3"><span
                                                class="badge-danger px-2 py-1 rounded text-xs">Critique</span></td>
                                        <td class="px-4 py-3"><span
                                                class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Non
                                                traité</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="showAnomalyDetail('AN002')"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono text-xs">AN003</td>
                                        <td class="px-4 py-3">Documents</td>
                                        <td class="px-4 py-3">Docs CNAMGS manquants</td>
                                        <td class="px-4 py-3">COMKETING</td>
                                        <td class="px-4 py-3"><span
                                                class="badge-warning px-2 py-1 rounded text-xs">Majeur</span></td>
                                        <td class="px-4 py-3"><span
                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">En
                                                cours</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="showAnomalyDetail('AN003')"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono text-xs">AN004</td>
                                        <td class="px-4 py-3">Conformité</td>
                                        <td class="px-4 py-3">Salaires sous SMIG</td>
                                        <td class="px-4 py-3">EZER Immo</td>
                                        <td class="px-4 py-3"><span
                                                class="badge-warning px-2 py-1 rounded text-xs">Majeur</span></td>
                                        <td class="px-4 py-3"><span
                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">En
                                                cours</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="showAnomalyDetail('AN004')"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentation RH Réglementaire</h3>
                        <div class="space-y-3">
                            <a href="#"
                                class="block p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-sm">Code du Travail</p>
                                        <p class="text-xs text-gray-500">Gabon - Version 2023</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#"
                                class="block p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-sm">Actes uniformes OHADA</p>
                                        <p class="text-xs text-gray-500">Droit des sociétés</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#"
                                class="block p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-sm">Règlement CNSS</p>
                                        <p class="text-xs text-gray-500">Déclarations & cotisations</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#"
                                class="block p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-sm">Guide CNAMGS</p>
                                        <p class="text-xs text-gray-500">Couverture santé obligatoire</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#"
                                class="block p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-bookmark text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-sm">Aide-mémoire Obligations RH</p>
                                        <p class="text-xs text-gray-500">Checklist mensuelle</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <button
                            class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-download mr-2"></i> Télécharger Tout (PDF)
                        </button>
                    </div>
                </div>

                <!-- Anomaly Detail Modal -->
                <div id="anomaly-modal"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-2xl font-bold text-gray-800">Détail Anomalie</h3>
                            <button onclick="closeAnomalyModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div id="anomaly-details" class="space-y-4"></div>
                    </div>
                </div>
            </section>

            <!-- Pointage Module -->
            <section id="pointage-module" class="module-section p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6"
                        style="max-height: 1000px; overflow-y: auto;">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Présences - 7 derniers jours</h3>
                        @isset($scopeLabel)
                            <span class="text-xs text-gray-500">Portée : {{ $scopeLabel }}</span>
                        @endisset
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Employé</th>
                                        @foreach ($dayLabels as $lbl)
                                            <th class="px-4 py-3 text-center">{{ $lbl }}</th>
                                        @endforeach
                                        <th class="px-4 py-3 text-center">Anomalies</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($presenceRows as $row)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $row['name'] }}</td>
                                            @foreach ($row['statuses'] as $st)
                                                <td class="px-4 py-3 text-center">
                                                    @switch($st)
                                                        @case('present')
                                                            <span class="text-green-600"><i
                                                                    class="fas fa-check-circle"></i></span>
                                                        @break

                                                        @case('late')
                                                            <span class="text-orange-600"><i
                                                                    class="fas fa-exclamation-circle"></i></span>
                                                        @break

                                                        @case('absent')
                                                            <span class="text-red-600"><i
                                                                    class="fas fa-times-circle"></i></span>
                                                        @break

                                                        @case('weekend')
                                                            <span class="text-gray-300"><i
                                                                    class="fas fa-minus-circle"></i></span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            @endforeach
                                            <td class="px-4 py-3 text-center">
                                                @php
                                                    $an = (int) ($row['anomalies'] ?? 0);
                                                    $badge =
                                                        $an >= 4
                                                            ? 'badge-danger'
                                                            : ($an >= 2
                                                                ? 'badge-warning'
                                                                : 'badge-success');
                                                @endphp
                                                <span
                                                    class="{{ $badge }} px-2 py-1 rounded text-xs">{{ $an }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count($dayLabels) }}"
                                                    class="px-4 py-6 text-center text-gray-400">Aucune donnée</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 flex items-center text-xs text-gray-600 space-x-4">
                                <div><i class="fas fa-check-circle text-green-600 mr-1"></i> Présent</div>
                                <div><i class="fas fa-exclamation-circle text-orange-600 mr-1"></i> Retard</div>
                                <div><i class="fas fa-times-circle text-red-600 mr-1"></i> Absent</div>
                                <div><i class="fas fa-minus-circle text-gray-300 mr-1"></i> Week-end</div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Taux d'Assiduité</h3>
                            <canvas id="assiduitéChart"></canvas>
                            <div class="mt-4 space-y-2">
                                @forelse($assiduiteLabels as $i => $label)
                                    @php
                                        $pct = (float) ($assiduiteRates[$i] ?? 0);
                                        $txt =
                                            $pct >= 95
                                                ? 'text-green-600'
                                                : ($pct >= 90
                                                    ? 'text-orange-600'
                                                    : 'text-red-600');
                                        $bar =
                                            $pct >= 95 ? 'bg-green-500' : ($pct >= 90 ? 'bg-orange-500' : 'bg-red-500');
                                    @endphp
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $label }}</span>
                                        <span
                                            class="font-semibold {{ $txt }}">{{ number_format($pct, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $bar }} h-2 rounded-full"
                                            style="width: {{ max(0, min(100, $pct)) }}%"></div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400">Aucune donnée</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Heatmap -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Heatmap Retards & Absences par Service</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-center">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2">Service</th>
                                        <th class="px-3 py-2">Lun</th>
                                        <th class="px-3 py-2">Mar</th>
                                        <th class="px-3 py-2">Mer</th>
                                        <th class="px-3 py-2">Jeu</th>
                                        <th class="px-3 py-2">Ven</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $max = max(1, (int) ($heatMax ?? 0)); // évite division par zéro
                                        $cls = function ($v) use ($max) {
                                            if ($v <= 0) {
                                                return 'bg-green-100';
                                            }
                                            $r = $v / $max;
                                            return $r < 0.25
                                                ? 'bg-green-200'
                                                : ($r < 0.5
                                                    ? 'bg-yellow-200'
                                                    : ($r < 0.75
                                                        ? 'bg-orange-300'
                                                        : 'bg-red-400'));
                                        };
                                    @endphp

                                    @forelse($heatmap as $row)
                                        <tr>
                                            <td class="px-3 py-2 text-left font-semibold">{{ $row['service'] }}</td>
                                            @foreach ($row['cells'] ?? [] as $val)
                                                <td class="px-3 py-2">
                                                    <div class="heatmap-cell h-8 rounded {{ $cls($val) }}"></div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-3 py-6 text-gray-400">Aucune donnée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-center items-center space-x-4 text-xs">
                            <span>Faible</span>
                            <div class="flex space-x-1">
                                <div class="w-6 h-6 bg-green-100 rounded"></div>
                                <div class="w-6 h-6 bg-green-200 rounded"></div>
                                <div class="w-6 h-6 bg-yellow-200 rounded"></div>
                                <div class="w-6 h-6 bg-orange-300 rounded"></div>
                                <div class="w-6 h-6 bg-red-400 rounded"></div>
                            </div>
                            <span>Élevé</span>
                        </div>
                    </div>
                </section>

                {{-- Chart.js (assiduité) --}}

                </section>

                <!-- Paie Module -->
                <section id="paie-module" class="module-section p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution Masse Salariale (6 mois)</h3>
                            <canvas id="evolutionPaieChart"></canvas>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Comparatif Sociétés (Octobre 2025)</h3>
                            <canvas id="radarPaieChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition Masse Salariale</h3>
                            <canvas id="repartitionPaieChart"></canvas>
                        </div>
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Détail par Société</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Société</th>
                                            <th class="px-4 py-3 text-right">Effectif</th>
                                            <th class="px-4 py-3 text-right">Salaire Brut</th>
                                            <th class="px-4 py-3 text-right">Charges Patronales</th>
                                            <th class="px-4 py-3 text-right">Primes</th>
                                            <th class="px-4 py-3 text-right">Total</th>
                                            <th class="px-4 py-3 text-right">Coût/CA</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-semibold">Ingenium</td>
                                            <td class="px-4 py-3 text-right">145</td>
                                            <td class="px-4 py-3 text-right">32.5M</td>
                                            <td class="px-4 py-3 text-right">8.1M</td>
                                            <td class="px-4 py-3 text-right">4.2M</td>
                                            <td class="px-4 py-3 text-right font-semibold">44.8M</td>
                                            <td class="px-4 py-3 text-right"><span
                                                    class="badge-success px-2 py-1 rounded text-xs">28%</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-semibold">EZER Immo</td>
                                            <td class="px-4 py-3 text-right">87</td>
                                            <td class="px-4 py-3 text-right">18.9M</td>
                                            <td class="px-4 py-3 text-right">4.7M</td>
                                            <td class="px-4 py-3 text-right">2.1M</td>
                                            <td class="px-4 py-3 text-right font-semibold">25.7M</td>
                                            <td class="px-4 py-3 text-right"><span
                                                    class="badge-warning px-2 py-1 rounded text-xs">32%</span></td>
                                        </tr>
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-4 py-3">TOTAL GROUPE</td>
                                            <td class="px-4 py-3 text-right">487</td>
                                            <td class="px-4 py-3 text-right">101.7M</td>
                                            <td class="px-4 py-3 text-right">25.5M</td>
                                            <td class="px-4 py-3 text-right">12.8M</td>
                                            <td class="px-4 py-3 text-right">140.0M</td>
                                            <td class="px-4 py-3 text-right"><span
                                                    class="badge-success px-2 py-1 rounded text-xs">28%</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Reporting Module -->
                <section id="reporting-module" class="module-section p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Générateur de Rapports</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de Rapport</label>
                                    <select
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option>Rapport Effectifs</option>
                                        <option>Rapport Conformité RH</option>
                                        <option>Rapport Pointage</option>
                                        <option>Rapport Paie & Masse Salariale</option>
                                        <option>Rapport Consolidé Mensuel</option>
                                        <option>Rapport Anomalies & Alertes</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Société</label>
                                        <select
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option>Toutes les sociétés</option>
                                            <option>Ingenium</option>
                                            <option>EZER Immo</option>
                                            <option>COMKETING</option>
                                            <option>YOD Bénin</option>
                                            <option>EGCC</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Période</label>
                                        <select
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option>Octobre 2025</option>
                                            <option>Septembre 2025</option>
                                            <option>Août 2025</option>
                                            <option>3ème Trimestre 2025</option>
                                            <option>Année 2025</option>
                                            <option>Personnalisée...</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Format d'Export</label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="format" value="pdf" checked class="mr-2">
                                            <span class="text-sm">PDF</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="format" value="excel" class="mr-2">
                                            <span class="text-sm">Excel</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="format" value="csv" class="mr-2">
                                            <span class="text-sm">CSV</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <button
                                        class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                                        <i class="fas fa-file-export mr-2"></i> Générer le Rapport
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Exports Rapides</h3>
                            <div class="space-y-3">
                                <button
                                    class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-left flex items-center justify-between transition">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-600 mr-3 text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-sm">Dashboard PDF</p>
                                            <p class="text-xs text-gray-500">Vue consolidée actuelle</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-gray-400"></i>
                                </button>
                                <button
                                    class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-left flex items-center justify-between transition">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-excel text-green-600 mr-3 text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-sm">Liste Effectifs Excel</p>
                                            <p class="text-xs text-gray-500">Toutes sociétés</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-gray-400"></i>
                                </button>
                                <button
                                    class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-left flex items-center justify-between transition">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-csv text-blue-600 mr-3 text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-sm">Pointage CSV</p>
                                            <p class="text-xs text-gray-500">7 derniers jours</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-gray-400"></i>
                                </button>
                                <button
                                    class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-left flex items-center justify-between transition">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-purple-600 mr-3 text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-sm">Rapport Conformité</p>
                                            <p class="text-xs text-gray-500">Alertes & anomalies</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-gray-400"></i>
                                </button>
                            </div>
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-sm text-blue-900 mb-2">Rapports Planifiés</h4>
                                <p class="text-xs text-blue-700 mb-3">Envoi automatique par email</p>
                                <ul class="text-xs space-y-1 text-blue-800">
                                    <li><i class="fas fa-check-circle mr-2"></i>Hebdomadaire - Pointage</li>
                                    <li><i class="fas fa-check-circle mr-2"></i>Mensuel - Paie consolidée</li>
                                    <li><i class="fas fa-check-circle mr-2"></i>Mensuel - Conformité RH</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Recherche Rapide -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recherche Rapide Multi-critères</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <input type="text" placeholder="Nom, prénom..."
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <select
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Toutes sociétés</option>
                                <option>Ingenium</option>
                                <option>EZER Immo</option>
                                <option>COMKETING</option>
                                <option>YOD Bénin</option>
                                <option>EGCC</option>
                            </select>
                            <select
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Tous services</option>
                                <option>Direction</option>
                                <option>Commercial</option>
                                <option>Technique</option>
                                <option>Administratif</option>
                                <option>Logistique</option>
                            </select>
                            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Rechercher
                            </button>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <script>
            const companiesData = @json($companiesData);
            const employeesData = @json($employeesData);


            const anomaliesData = {
                AN001: {
                    id: 'AN001',
                    type: 'Contractuel',
                    titre: '8 contrats non signés depuis 45 jours',
                    societe: 'EGCC',
                    gravite: 'CRITIQUE',
                    description: 'Huit employés travaillent sans contrat formellement signé depuis plus de 45 jours. Situation non conforme à l\'article 28 du Code du Travail gabonais.',
                    impact: 'Risque juridique majeur : nullité possible des contrats, requalification automatique en CDI, sanctions administratives possibles (amende de 500 000 à 2 000 000 XOF).',
                    recommandation: 'Action immédiate : Convoquer les employés concernés dans les 7 jours pour signature. Établir un process de suivi systématique des signatures de contrats (délai max 15 jours après embauche).',
                    echeance: '20 octobre 2025',
                    statut: 'En cours'
                },
                AN002: {
                    id: 'AN002',
                    type: 'Déclarations sociales',
                    titre: 'Retard déclarations CNSS (3 mois)',
                    societe: 'YOD Bénin',
                    gravite: 'CRITIQUE',
                    description: 'Les déclarations mensuelles CNSS n\'ont pas été effectuées pour juillet, août et septembre 2025. Retard cumulé de 3 mois.',
                    impact: 'Sanctions financières : majoration de retard 3% par mois + pénalités fixes. Risque de suspension des prestations pour les salariés. Possibilité de contrôle CNSS avec redressement.',
                    recommandation: 'Urgence : Régulariser immédiatement les 3 mois avec paiement des cotisations + majorations. Mettre en place rappel automatique avant échéance mensuelle.',
                    echeance: 'Immédiat',
                    statut: 'Non traité'
                },
                AN003: {
                    id: 'AN003',
                    type: 'Documents réglementaires',
                    titre: '5 documents CNAMGS manquants',
                    societe: 'COMKETING',
                    gravite: 'MAJEUR',
                    description: 'Cinq employés ne disposent pas de leur attestation CNAMGS (couverture maladie obligatoire). Non-conformité avec l\'obligation légale de protection sociale.',
                    impact: 'Infraction à l\'obligation de couverture santé. Responsabilité employeur en cas d\'accident ou maladie. Risque de contentieux prud\'homal.',
                    recommandation: 'Contacter CNAMGS sous 15 jours pour régularisation. Vérifier les cotisations. Établir checklist documents obligatoires à l\'embauche.',
                    echeance: '05 novembre 2025',
                    statut: 'En cours'
                },
                AN004: {
                    id: 'AN004',
                    type: 'Conformité Code du Travail',
                    titre: '3 postes sous SMIG légal',
                    societe: 'EZER Immo',
                    gravite: 'MAJEUR',
                    description: 'Trois employés perçoivent une rémunération inférieure au Salaire Minimum Interprofessionnel Garanti (SMIG) actuellement fixé à 150 000 XOF.',
                    impact: 'Violation directe de l\'article 145 du Code du Travail. Risque de redressement URSSAF + dommages et intérêts aux salariés. Sanctions pénales possibles.',
                    recommandation: 'Régularisation immédiate avec rappel de salaire rétroactif sur 12 mois. Audit complet de la grille salariale. Formation RH sur obligations légales.',
                    echeance: '05 novembre 2025',
                    statut: 'En cours'
                },
                AN005: {
                    id: 'AN005',
                    type: 'Gestion contractuelle',
                    titre: '12 CDD à renouveler (échéance <30j)',
                    societe: 'Multi-sociétés',
                    gravite: 'MAJEUR',
                    description: 'Douze contrats à durée déterminée arrivent à échéance dans les 30 prochains jours. Risque de rupture de continuité de service si non anticipé.',
                    impact: 'Perturbation opérationnelle. Perte de compétences. Risque de requalification en CDI si renouvellement tardif ou irrégulier (au-delà de 2 renouvellements).',
                    recommandation: 'Lancer processus décision (renouvellement/CDI/fin) sous 7 jours. Respecter délai de prévenance légal de 15 jours avant échéance. Anticiper recrutement si non-renouvellement.',
                    echeance: '28 octobre 2025',
                    statut: 'En cours'
                },
                AN006: {
                    id: 'AN006',
                    type: 'Santé au travail',
                    titre: 'Visites médicales périodiques (8 salariés)',
                    societe: 'Ingenium',
                    gravite: 'MINEUR',
                    description: 'Huit employés n\'ont pas effectué leur visite médicale annuelle obligatoire. Retard de 2 à 4 mois selon les cas.',
                    impact: 'Non-respect obligation santé-sécurité au travail. Responsabilité employeur en cas d\'accident. Amende administrative possible.',
                    recommandation: 'Planifier les 8 visites médicales dans les 30 jours. Établir calendrier automatique des visites médicales (suivi annuel). Sensibiliser les managers.',
                    echeance: '15 novembre 2025',
                    statut: 'Planifié'
                }
            };

            // Navigation
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    const module = this.dataset.module;
                    document.querySelectorAll('.module-section').forEach(m => m.classList.remove('active'));
                    document.getElementById(module + '-module').classList.add('active');

                    const titles = {
                        dashboard: {
                            title: 'Tableau de bord principal',
                            subtitle: 'Vue consolidée BFEV - 5 sociétés'
                        },
                        effectifs: {
                            title: 'Effectifs & Structure',
                            subtitle: 'Gestion des employés et organisation'
                        },
                        contrats: {
                            title: 'Contrats & Conformité',
                            subtitle: 'Suivi légal et réglementaire'
                        },
                        pointage: {
                            title: 'Pointage & Temps de travail',
                            subtitle: 'Présences et assiduité'
                        },
                        paie: {
                            title: 'Paie & Masse salariale',
                            subtitle: 'Analyse financière RH'
                        },
                        reporting: {
                            title: 'Reporting & Export',
                            subtitle: 'Rapports et exports de données'
                        }
                    };

                    document.getElementById('module-title').textContent = titles[module].title;
                    document.getElementById('module-subtitle').textContent = titles[module].subtitle;
                });
            });


            const compList = Array.isArray(companiesData) ? companiesData : Object.values(companiesData);

            // Extractions dynamiques
            const labels = compList.map(c => c.name ?? '—');
            const data = compList.map(c => Number(c.effectif ?? 0));
            const colors = compList.map(c => c.color ?? '#6c757d');

            const el = document.getElementById('effectifChart');
            if (el) {
                const ctx = el.getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels,
                        datasets: [{
                            data,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ti) => `${labels[ti.dataIndex]}: ${data[ti.dataIndex]}`
                                }
                            }
                        },
                        cutout: '55%' // donut un peu plus fin (optionnel)
                    }
                });
            }

            // Masse Salariale Chart
            const masseSalarialeCtx = document.getElementById('masseSalarialeChart').getContext('2d');
            new Chart(masseSalarialeCtx, {
                type: 'line',
                data: {
                    labels: ['Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre'],
                    datasets: [{
                        label: 'Masse Salariale (M XOF)',
                        data: [118, 121, 123, 125, 126, 127],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });

            // Conformity Gauge
            const conformityOptions = {
                series: [78],
                chart: {
                    type: 'radialBar',
                    height: 200
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '60%'
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: '24px',
                                fontWeight: 'bold',
                                formatter: function(val) {
                                    return val + '%';
                                }
                            }
                        },
                        track: {
                            background: '#f3f4f6'
                        }
                    }
                },
                colors: ['#f59e0b'],
                labels: ['Conformité']
            };
            const conformityChart = new ApexCharts(document.querySelector("#conformityGauge"), conformityOptions);
            conformityChart.render();

            // Données déjà “propres” (pas de fonctions PHP ici)
            const ageLabels = @json($ageLabels);
            const ageData = @json($ageData);

            window.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('pyramideAgesChart');
                if (!el) return;
                const ctx = el.getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ageLabels,
                        datasets: [{
                            label: 'Effectif',
                            data: ageData,
                            backgroundColor: '#05436b' // doux, pas trop vif
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            });

            // Radar Conformité
            const radarOptions = {
                series: [{
                    name: 'Ingenium',
                    data: [88, 78, 85, 90, 82]
                }, {
                    name: 'EZER Immo',
                    data: [79, 65, 72, 78, 74]
                }, {
                    name: 'COMKETING',
                    data: [84, 68, 75, 82, 76]
                }, {
                    name: 'YOD Bénin',
                    data: [71, 58, 52, 68, 62]
                }, {
                    name: 'EGCC',
                    data: [65, 72, 63, 71, 68]
                }],
                chart: {
                    type: 'radar',
                    height: 300
                },
                xaxis: {
                    categories: ['Contrats', 'Docs Légaux', 'Décl. Sociales', 'Code Travail', 'Global']
                },
                colors: ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe']
            };
            const radarChart = new ApexCharts(document.querySelector("#conformityRadarChart"), radarOptions);
            radarChart.render();

            // Assiduité Chart
            (function() {
                const el = document.getElementById('assiduitéChart');
                if (!el || !window.Chart) return;

                const donut = @json($assiduiteDonut); // { labels: [...], data: [...] }

                new Chart(el.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: donut.labels,
                        datasets: [{
                            data: donut.data,
                            // couleurs douces (pas trop vives)
                            backgroundColor: ['#6FA19A', '#B69A73', '#BF6B6B']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => `${ctx.label}: ${ctx.parsed}%`
                                }
                            }
                        },
                        cutout: '55%'
                    }
                });
            })();

            // Évolution Paie Chart
            const evolutionPaieCtx = document.getElementById('evolutionPaieChart').getContext('2d');
            new Chart(evolutionPaieCtx, {
                type: 'bar',
                data: {
                    labels: ['Mai', 'Juin', 'Juillet', 'Août', 'Sept', 'Oct'],
                    datasets: [{
                        label: 'Salaire Brut',
                        data: [95, 97, 98, 100, 101, 102],
                        backgroundColor: '#667eea'
                    }, {
                        label: 'Charges Patronales',
                        data: [24, 24, 25, 25, 25, 26],
                        backgroundColor: '#764ba2'
                    }, {
                        label: 'Primes',
                        data: [11, 11, 12, 12, 13, 13],
                        backgroundColor: '#f093fb'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });

            // Radar Paie
            const radarPaieCtx = document.getElementById('radarPaieChart').getContext('2d');
            new Chart(radarPaieCtx, {
                type: 'radar',
                data: {
                    labels: ['Ingenium', 'EZER Immo', 'COMKETING', 'YOD Bénin', 'EGCC'],
                    datasets: [{
                        label: 'Masse Salariale (M XOF)',
                        data: [44.8, 25.7, 33.9, 16.7, 18.9],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Répartition Paie
            const repartitionPaieCtx = document.getElementById('repartitionPaieChart').getContext('2d');
            new Chart(repartitionPaieCtx, {
                type: 'pie',
                data: {
                    labels: ['Salaire Brut', 'Charges Patronales', 'Primes'],
                    datasets: [{
                        data: [101.7, 25.5, 12.8],
                        backgroundColor: ['#667eea', '#764ba2', '#f093fb']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Employees Table
            function renderEmployeesTable(employees = employeesData) {
                const tbody = document.getElementById('employees-tbody');
                tbody.innerHTML = '';
                employees.forEach((emp, index) => {
                    const row = `
                    <tr class="employee-card" onclick="showEmployeeDetail(${index})">
                        <td class="px-4 py-3 font-semibold">${emp.name}</td>
                        <td class="px-4 py-3">${emp.company}</td>
                        <td class="px-4 py-3">${emp.poste}</td>
                        <td class="px-4 py-3">${emp.service}</td>
                        <td class="px-4 py-3">${emp.anciennete}</td>
                        <td class="px-4 py-3"><span class="badge-${emp.statut === 'CDI' ? 'success' : 'warning'} px-3 py-1 rounded-full text-xs">${emp.statut}</span></td>
                    </tr>
                `;
                    tbody.innerHTML += row;
                });
            }

            function showEmployeeDetail(index) {
                const emp = employeesData[index];
                const modal = document.getElementById('employee-modal');
                const details = document.getElementById('employee-details');

                details.innerHTML = `
                <div class="flex items-center space-x-4 mb-6 pb-6 border-b">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        ${emp.name.split(' ').map(n => n[0]).join('')}
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">${emp.name}</h4>
                        <p class="text-gray-600">${emp.poste}</p>
                        <p class="text-sm text-gray-500">${emp.company}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Service</p>
                        <p class="font-semibold text-gray-800">${emp.service}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ancienneté</p>
                        <p class="font-semibold text-gray-800">${emp.anciennete}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type de contrat</p>
                        <p class="font-semibold text-gray-800">${emp.statut}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Statut</p>
                        <p class="font-semibold text-green-600">Actif</p>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t">
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Voir le dossier complet
                    </button>
                </div>
            `;

                modal.classList.remove('hidden');
            }

            function closeEmployeeModal() {
                document.getElementById('employee-modal').classList.add('hidden');
            }

            // Employee Search
            document.getElementById('employee-search').addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                const filtered = employeesData.filter(emp =>
                    emp.name.toLowerCase().includes(query) ||
                    emp.company.toLowerCase().includes(query) ||
                    emp.poste.toLowerCase().includes(query)
                );
                renderEmployeesTable(filtered);
            });

            // Anomaly Detail
            function showAnomalyDetail(id) {
                const anomaly = anomaliesData[id];
                if (!anomaly) return;

                const modal = document.getElementById('anomaly-modal');
                const details = document.getElementById('anomaly-details');

                const graviteClass = {
                    'CRITIQUE': 'badge-danger',
                    'MAJEUR': 'badge-warning',
                    'MINEUR': 'badge-info'
                } [anomaly.gravite];

                details.innerHTML = `
                <div class="mb-6 pb-6 border-b">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="${graviteClass} px-3 py-1 rounded-full text-sm">${anomaly.gravite}</span>
                                <span class="text-sm text-gray-500">${anomaly.societe}</span>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">${anomaly.titre}</h4>
                            <p class="text-sm text-gray-500 mt-1">Référence : ${anomaly.id} | Type : ${anomaly.type}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i> Description
                        </h5>
                        <p class="text-gray-700 text-sm">${anomaly.description}</p>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i> Impact
                        </h5>
                        <p class="text-gray-700 text-sm">${anomaly.impact}</p>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i> Recommandation
                        </h5>
                        <p class="text-gray-700 text-sm">${anomaly.recommandation}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                        <div>
                            <p class="text-sm text-gray-500">Échéance</p>
                            <p class="font-semibold text-gray-800">${anomaly.echeance}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Statut</p>
                            <p class="font-semibold ${anomaly.statut === 'Non traité' ? 'text-red-600' : 'text-orange-600'}">${anomaly.statut}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t flex space-x-3">
                    <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-check mr-2"></i> Marquer comme traité
                    </button>
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-file-export mr-2"></i> Exporter fiche
                    </button>
                </div>
            `;

                modal.classList.remove('hidden');
            }

            function closeAnomalyModal() {
                document.getElementById('anomaly-modal').classList.add('hidden');
            }

            // Initialize
            renderEmployeesTable();

            console.log('RH & Paie Consolidé BFEV - Maquette chargée avec succès');
        </script>
    </body>

    </html>
