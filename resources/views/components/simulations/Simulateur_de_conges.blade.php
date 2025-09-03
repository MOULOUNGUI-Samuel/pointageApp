<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Congés BFEV V.2.0 | Code du Travail 2021</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg { 
            background: linear-gradient(135deg, #05436b 0%, #05436b 100%); 
        }
        
        .card-shadow { 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
            transition: all 0.3s ease;
        }
        
        .card-shadow:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-2px);
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #05436b 0%, #05436b 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover { 
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }
        
        .result-card { 
            border-left: 4px solid #05436b; 
            animation: fadeInUp 0.5s ease-out;
        }
        
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #05436b;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-field-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .success-message {
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .progress-bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin: 1rem 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #05436b, #05436b);
            border-radius: 3px;
            transition: width 0.8s ease-out;
        }

        /* Drapeau du Gabon : vert, jaune, bleu */
        .flag-gabon {
            display: inline-block;
            width: 24px;
            height: 16px;
            background: linear-gradient(to bottom, #009639 33.33%, #FCD116 33.33%, #FCD116 66.66%, #3A75C4 66.66%);
            border-radius: 2px;
            margin-right: 8px;
            vertical-align: middle;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .tab-active {
            background: linear-gradient(135deg, #05436b 0%, #05436b 100%);
            color: white;
        }
        
        .tab-inactive {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        
        .tab-inactive:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .warning-banner {
            background: linear-gradient(90deg, #f59e0b, #d97706);
            color: white;
            animation: pulse 2s ease-in-out infinite alternate;
        }

        /* Styles d'impression améliorés */
        @media print {
            body { 
                print-color-adjust: exact; 
                -webkit-print-color-adjust: exact;
                font-size: 11pt;
                line-height: 1.3;
                color: #000;
                background: white;
            }
            .no-print { 
                display: none !important; 
            }
            .print-break { 
                page-break-before: always; 
            }
            .container {
                max-width: none !important;
                margin: 0 !important;
                padding: 15px !important;
            }
            .card-shadow {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                margin-bottom: 15px !important;
                background: white !important;
            }
            .gradient-bg {
                background: #4a5568 !important;
                color: white !important;
            }
            .flag-gabon {
                background: #ccc !important;
                border: 1px solid #000 !important;
            }
            h1, h2, h3 {
                color: #000 !important;
                page-break-after: avoid;
            }
            .grid {
                display: block !important;
            }
            .grid > div {
                margin-bottom: 20px !important;
                page-break-inside: avoid;
            }
            table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            table, th, td {
                border: 1px solid #000 !important;
            }
            th, td {
                padding: 8px !important;
                text-align: left !important;
            }
            .print-signature {
                display: block !important;
                margin-top: 40px;
                border-top: 1px solid #000;
                padding-top: 20px;
            }
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #000;
                padding: 10px;
                font-size: 9pt;
                text-align: center;
            }
            .page-number:after {
                content: counter(page);
            }
        }
        
        @media (max-width: 768px) {
            .container { 
                padding-left: 1rem; 
                padding-right: 1rem; 
            }
            .grid { 
                grid-template-columns: 1fr; 
            }
        }

        /* Styles pour les alertes légales */
        .legal-disclaimer {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .legal-warning {
            background: #fecaca;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Bannière d'avertissement légal -->
    <div class="warning-banner text-center py-2 no-print">
        <p class="text-sm font-medium">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            OUTIL INFORMATIF - Seul le Code du Travail gabonais fait foi juridiquement
        </p>
    </div>

    <!-- En-tête avec logo BFEV -->
    <header class="gradient-bg text-white py-6 no-print">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between flex-wrap">
                <div class="flex items-center mb-2 md:mb-0">
                    <img src="https://ingenium-assurance.com/rapports/BFEV_logo_principal.jpg" 
                         alt="Logo BFEV" 
                         class="h-12 mr-4 bg-white rounded px-2 py-1"
                         onerror="this.style.display='none'">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold flex items-center">
                            <i class="fas fa-calendar-check mr-3" aria-hidden="true"></i>
                            Simulateur de Congés BFEV V.2.0
                        </h1>
                        <p class="text-blue-100 mt-2">Conforme au Code du Travail gabonais 2021 - Version Améliorée</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100 flex items-center justify-end">
                        <span class="flag-gabon"></span>
                        République Gabonaise
                    </p>
                    <p class="text-xs text-blue-200">Loi n°022/2021 du 19 novembre 2021</p>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Avertissement légal principal -->
        <div class="legal-disclaimer no-print">
            <div class="flex items-start">
                <i class="fas fa-balance-scale text-amber-600 mr-3 mt-1 text-lg"></i>
                <div>
                    <h3 class="font-semibold text-amber-800 mb-2">Avertissement Légal Important</h3>
                    <p class="text-amber-700 text-sm leading-relaxed">
                        Ce simulateur est un <strong>outil informatif</strong> basé sur le Code du Travail gabonais (Loi n°022/2021). 
                        Les résultats sont <strong>indicatifs</strong> et ne constituent pas un conseil juridique. 
                        En cas de litige, seules les dispositions officielles du Code du Travail font foi. 
                        Consultez un juriste ou l'Inspection du Travail pour des situations complexes.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <div class="mb-8 no-print">
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                <button id="tabConges" 
                        onclick="switchTab('conges')" 
                        class="flex-1 py-3 px-6 rounded-md font-medium transition-all duration-200 tab-active">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Droits aux Congés
                </button>
                <button id="tabAvances" 
                        onclick="switchTab('avances')" 
                        class="flex-1 py-3 px-6 rounded-md font-medium transition-all duration-200 tab-inactive">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Avances sur Congé
                </button>
            </div>
        </div>

        <!-- Barre de progression -->
        <div class="mb-8 no-print">
            <div class="progress-bar">
                <div id="progressBar" class="progress-fill" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">Remplissez le formulaire pour commencer</p>
        </div>

        <!-- Messages d'alerte -->
        <div id="alertContainer" class="mb-6 no-print"></div>
        
        <!-- Section Droits aux Congés -->
        <div id="sectionConges" class="grid lg:grid-cols-2 gap-8">
            
            <!-- Colonne gauche : Saisie des données -->
            <div class="space-y-6 no-print">
                
                <!-- Informations personnelles -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2" aria-hidden="true"></i>
                        Informations du salarié
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="nomComplet" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nomComplet" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Ex: MBANGO Jean-Pierre"
                                   required
                                   aria-describedby="nomComplet-error">
                            <div id="nomComplet-error" class="error-message hidden"></div>
                        </div>
                        
                        <div>
                            <label for="typeContrat" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de contrat <span class="text-red-500">*</span>
                            </label>
                            <select id="typeContrat" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required
                                    aria-describedby="typeContrat-error">
                                <option value="">-- Sélectionnez --</option>
                                <option value="CDI">CDI - Contrat à Durée Indéterminée</option>
                                <option value="CDD">CDD - Contrat à Durée Déterminée</option>
                                <option value="Stage">Stage</option>
                                <option value="Apprentissage">Apprentissage</option>
                            </select>
                            <div id="typeContrat-error" class="error-message hidden"></div>
                        </div>
                        
                        <div>
                            <label for="dateEmbauche" class="block text-sm font-medium text-gray-700 mb-2">
                                Date d'embauche <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="dateEmbauche" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required
                                   aria-describedby="dateEmbauche-error">
                            <div id="dateEmbauche-error" class="error-message hidden"></div>
                        </div>
                        
                        <div>
                            <label for="dateActuelle" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de calcul <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="dateActuelle" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required
                                   aria-describedby="dateActuelle-error">
                            <div id="dateActuelle-error" class="error-message hidden"></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Vous pouvez sélectionner une date future pour une simulation
                            </div>
                        </div>
                        
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                                Âge (années) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="age" 
                                   min="14" 
                                   max="65" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Ex: 28"
                                   required
                                   aria-describedby="age-error">
                            <div id="age-error" class="error-message hidden"></div>
                        </div>
                        
                        <div>
                            <label for="salaireMoyen" class="block text-sm font-medium text-gray-700 mb-2">
                                Salaire mensuel moyen (FCFA)
                            </label>
                            <input type="number" 
                                   id="salaireMoyen" 
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Ex: 500000"
                                   aria-describedby="salaireMoyen-help">
                            <div id="salaireMoyen-help" class="text-xs text-gray-500 mt-1">Optionnel - pour calcul des allocations</div>
                        </div>
                    </div>

                    <!-- Informations familiales -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h3 class="font-medium text-gray-800 mb-3">Situation familiale</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="sexe" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sexe <span class="text-red-500">*</span>
                                </label>
                                <select id="sexe" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="M">Homme</option>
                                    <option value="F">Femme</option>
                                </select>
                            </div>
                            <div>
                                <label for="enfantsCharge" class="block text-sm font-medium text-gray-700 mb-2">
                                    Enfants à charge (< 16 ans)
                                </label>
                                <input type="number" 
                                       id="enfantsCharge" 
                                       min="0" 
                                       max="15" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="0"
                                       value="0"
                                       aria-describedby="enfantsCharge-help">
                                <div id="enfantsCharge-help" class="text-xs text-gray-500 mt-1">
                                    Majoration possible selon conventions (à vérifier)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Type de congé -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-calendar-alt text-green-600 mr-2" aria-hidden="true"></i>
                        Type de congé à calculer
                    </h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" 
                                   name="typeConge" 
                                   value="annuel" 
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500" 
                                   checked>
                            <div>
                                <div class="font-medium text-gray-800">Congé annuel ordinaire</div>
                                <div class="text-sm text-gray-600">2 jours ouvrables par mois de service effectif (Art. 222)</div>
                                <div class="text-xs text-gray-500">Calculé sur l'année anniversaire d'embauche</div>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" 
                                   name="typeConge" 
                                   value="maternite" 
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-medium text-gray-800">Congé de maternité</div>
                                <div class="text-sm text-gray-600">14 semaines consécutives (6 avant + 8 après accouchement) - Art. 208</div>
                                <div class="text-xs text-gray-500">Réservé aux femmes salariées</div>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" 
                                   name="typeConge" 
                                   value="paternite" 
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-medium text-gray-800">Congé de paternité</div>
                                <div class="text-sm text-gray-600">3 jours consécutifs avec certificat de naissance (Art. 54)</div>
                                <div class="text-xs text-gray-500">Réservé aux pères</div>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" 
                                   name="typeConge" 
                                   value="evenements" 
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-medium text-gray-800">Événements familiaux</div>
                                <div class="text-sm text-gray-600">Variable selon événement - À vérifier selon convention</div>
                                <div class="text-xs text-gray-500">Mariage, décès, naissance, etc.</div>
                            </div>
                        </label>
                        
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" 
                                   name="typeConge" 
                                   value="maladie" 
                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="font-medium text-gray-800">Congé maladie</div>
                                <div class="text-sm text-gray-600">Jusqu'à 6 mois rémunérés avec certificat médical (Art. 55)</div>
                                <div class="text-xs text-gray-500">Contre-expertise possible après 3 certificats</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Historique des congés -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-history text-orange-600 mr-2" aria-hidden="true"></i>
                        Historique et absences
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="congesPris" class="block text-sm font-medium text-gray-700 mb-2">
                                Jours de congés déjà pris cette année
                            </label>
                            <input type="number" 
                                   id="congesPris" 
                                   min="0" 
                                   max="365"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="0"
                                   value="0">
                        </div>
                        
                        <div>
                            <label for="absencesInjustifiees" class="block text-sm font-medium text-gray-700 mb-2">
                                Absences injustifiées (jours)
                            </label>
                            <input type="number" 
                                   id="absencesInjustifiees" 
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="0"
                                   value="0"
                                   aria-describedby="absences-help">
                            <div id="absences-help" class="text-xs text-gray-500 mt-1">
                                Peuvent réduire les droits aux congés
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="text-center space-y-4">
                    <button id="calculBtn" 
                            onclick="calculerConges()" 
                            class="btn-primary text-white px-8 py-3 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 w-full md:w-auto">
                        <i class="fas fa-calculator mr-2" aria-hidden="true"></i>
                        <span id="calculBtnText">Calculer mes congés</span>
                    </button>
                    
                    <div class="flex justify-center space-x-4">
                        <button id="printBtn" 
                                onclick="imprimerResultats()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <i class="fas fa-print mr-2" aria-hidden="true"></i>
                            Imprimer
                        </button>
                        
                        <button id="pdfBtn" 
                                onclick="genererPDF()" 
                                class="btn-secondary text-white px-6 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>
                            PDF
                        </button>
                        
                        <button id="resetBtn" 
                                onclick="reinitialiserFormulaire()" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-undo mr-2" aria-hidden="true"></i>
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Résultats -->
            <div id="resultatsSection" class="space-y-6">
                
                <!-- Résumé du calcul -->
                <div id="resumeCalcul" class="bg-white rounded-lg card-shadow p-6 result-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-line text-blue-600 mr-2" aria-hidden="true"></i>
                        Résumé du calcul
                    </h2>
                    <div id="resumeContenu">
                        <div class="text-center py-8 text-gray-600">
                            <i class="fas fa-info-circle text-4xl text-gray-300 mb-4" aria-hidden="true"></i>
                            <p class="text-lg font-medium mb-2">Aucun calcul effectué</p>
                            <p class="text-sm">Remplissez le formulaire et cliquez sur "Calculer mes congés" pour voir vos droits selon le Code du Travail gabonais.</p>
                        </div>
                    </div>
                </div>

                <!-- Tableau détaillé -->
                <div id="tableauDetaille" class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-table text-green-600 mr-2" aria-hidden="true"></i>
                        Détail des droits
                    </h2>
                    <div id="tableauContenu">
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-table text-4xl text-gray-300 mb-4" aria-hidden="true"></i>
                            <p>Les détails apparaîtront après le calcul</p>
                        </div>
                    </div>
                </div>

                <!-- Références légales -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-balance-scale text-purple-600 mr-2" aria-hidden="true"></i>
                        Références légales
                    </h2>
                    <div id="referencesLegales" class="space-y-3 text-sm text-gray-700">
                        <div class="p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <strong>Code du Travail gabonais 2021</strong><br>
                            <span class="flag-gabon"></span>
                            Loi n°022/2021 du 19 novembre 2021 portant Code du Travail en République Gabonaise
                        </div>
                        <div class="space-y-2">
                            <p><strong>Art. 222 :</strong> Congés annuels - 2 jours ouvrables par mois de service effectif</p>
                            <p><strong>Art. 224 :</strong> Acquisition des droits après 12 mois de service continu</p>
                            <p><strong>Art. 225 :</strong> Allocation de congé égale à la rémunération habituelle</p>
                            <p><strong>Art. 208 :</strong> Congé de maternité - 14 semaines</p>
                            <p><strong>Art. 54 :</strong> Congés spéciaux (paternité, suspension contrat)</p>
                            <p><strong>Art. 55 :</strong> Maintien rémunération congé maladie</p>
                        </div>
                        
                        <div class="legal-warning mt-4">
                            <p class="text-red-700 text-xs">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Important :</strong> Certaines dispositions peuvent varier selon les conventions collectives sectorielles. 
                                Consultez votre convention collective ou l'Inspection du Travail pour des précisions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Avances sur Congé -->
        <div id="sectionAvances" class="hidden">
            <div class="grid lg:grid-cols-2 gap-8">
                
                <!-- Colonne gauche : Formulaire avances -->
                <div class="space-y-6 bg-white rounded-lg card-shadow p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center justify-center">
                            <span class="flag-gabon mr-2"></span>
                            Calculateur d'avances sur congé
                        </h2>
                        <p class="text-gray-600">République Gabonaise - Code du travail 2021</p>
                    </div>
                    
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Demande d'avance
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="joursAvance" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de jours d'avance demandés
                                </label>
                                <input type="number" 
                                       id="joursAvance" 
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Ex: 10"
                                       value="10">
                            </div>
                            <div>
                                <label for="dateAvance" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date souhaitée pour l'avance
                                </label>
                                <input type="date" 
                                       id="dateAvance" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="calculerAvance()" 
                            class="w-full btn-primary text-white px-8 py-3 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-calculator mr-2"></i>
                        Calculer les droits aux avances
                    </button>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                            <i class="fas fa-book text-yellow-600 mr-2"></i>
                            Base légale - Code du travail gabonais
                        </h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li><strong>Article 222</strong> : 2 jours ouvrables de congé par mois de service effectif (2,5 jours pour les moins de 18 ans)</li>
                            <li><strong>Article 224</strong> : Droit acquis après 1 an, mais calcul proportionnel possible</li>
                            <li><strong>Article 225</strong> : Allocation basée sur la rémunération habituelle</li>
                        </ul>
                    </div>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-700">
                            <strong><i class="fas fa-exclamation-triangle mr-1"></i> Important :</strong> 
                            Les avances sur congé sont possibles sur les droits déjà acquis proportionnellement. L'accord de l'employeur reste nécessaire pour la planification des congés.
                        </p>
                    </div>
                </div>
                
                <!-- Colonne droite : Résultats avances -->
                <div class="space-y-6">
                    <div id="resultatsAvances" class="bg-white rounded-lg card-shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                            Résultats du calcul d'avance
                        </h2>
                        <div id="avanceContenu">
                            <div class="text-center py-8 text-gray-600">
                                <i class="fas fa-money-bill-wave text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium mb-2">Aucun calcul d'avance effectué</p>
                                <p class="text-sm">Remplissez d'abord vos informations dans l'onglet "Droits aux Congés", puis spécifiez votre demande d'avance.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section d'impression cachée -->
    <div class="print-only" style="display: none;">
        <div class="print-header" style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-size: 18pt; font-weight: bold; margin-bottom: 10px;">
                SIMULATION DE CONGÉS PAYÉS
            </h1>
            <p style="font-size: 12pt;">République Gabonaise - Code du Travail 2021</p>
            <p style="font-size: 10pt;">Loi n°022/2021 du 19 novembre 2021</p>
            <hr style="margin: 20px 0; border: 1px solid #000;">
        </div>
        
        <div id="printContent"></div>
        
        <div class="print-signature" style="display: none;">
            <div style="margin-top: 50px; display: flex; justify-content: space-between;">
                <div style="width: 45%;">
                    <p><strong>L'employé :</strong></p>
                    <br><br>
                    <p>Date : ________________</p>
                    <p>Signature : ________________</p>
                </div>
                <div style="width: 45%;">
                    <p><strong>L'employeur :</strong></p>
                    <br><br>
                    <p>Date : ________________</p>
                    <p>Signature et cachet : ________________</p>
                </div>
            </div>
        </div>
        
        <div class="print-footer">
            <p>Document généré par le Simulateur BFEV V.2.0 - Page <span class="page-number"></span></p>
            <p style="font-size: 8pt; color: #666;">
                Ce document est informatif et ne constitue pas un acte juridique officiel
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12 no-print">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-2">
                <img src="https://ingenium-assurance.com/rapports/BFEV_logo_principal.jpg" 
                     alt="Logo BFEV" 
                     class="h-8 mr-3 bg-white rounded px-1"
                     onerror="this.style.display='none'">
                <span class="flag-gabon mr-2"></span>
                <span>© 2024 - Simulateur de Congés BFEV V.2.0 | Conforme au Code du Travail gabonais 2021</span>
            </div>
            <p class="text-sm text-gray-400 mt-2">
                <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                Cet outil est fourni à titre informatif. Consultez le Code du Travail officiel pour les dispositions complètes.
            </p>
            <p class="text-xs text-gray-500 mt-2">
                Version 2.0 - Dernière mise à jour : Septembre 2024 - Améliorations conformité légale et impression
            </p>
        </div>
    </footer>

    <script>
        // Configuration globale
        const CONFIG = {
            MOIS_PAR_ANNEE: 12,
            JOURS_PAR_MOIS: 30.416667,
            JOURS_BASE_PAR_MOIS: 2,
            JOURS_BASE_PAR_MOIS_MINEUR: 2.5,
            DUREE_MATERNITE_SEMAINES: 14,
            DUREE_PATERNITE_JOURS: 3,
            MOIS_MALADIE_MAX: 6,
            JOURS_OUVRABLES_PAR_MOIS: 26
        };

        // Variables globales
        let resultatCalcul = {};
        let validationErrors = {};
        let currentTab = 'conges';

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            initializeDates();
            setupEventListeners();
            updateProgress();
        });

        function initializeDates() {
            const today = new Date();
            const oneYearAgo = new Date(today);
            oneYearAgo.setFullYear(today.getFullYear() - 1);
            
            document.getElementById('dateActuelle').value = today.toISOString().split('T')[0];
            document.getElementById('dateEmbauche').value = oneYearAgo.toISOString().split('T')[0];
            document.getElementById('dateAvance').value = today.toISOString().split('T')[0];
        }

        function setupEventListeners() {
            const requiredFields = ['nomComplet', 'typeContrat', 'dateEmbauche', 'dateActuelle', 'age', 'sexe'];
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', () => {
                        validateField(fieldId);
                        updateProgress();
                    });
                    field.addEventListener('blur', () => validateField(fieldId));
                }
            });

            document.querySelectorAll('input[name="typeConge"]').forEach(radio => {
                radio.addEventListener('change', updateProgress);
            });
        }

        function switchTab(tab) {
            currentTab = tab;
            
            // Mise à jour des onglets
            if (tab === 'conges') {
                document.getElementById('tabConges').className = 'flex-1 py-3 px-6 rounded-md font-medium transition-all duration-200 tab-active';
                document.getElementById('tabAvances').className = 'flex-1 py-3 px-6 rounded-md font-medium transition-all duration-200 tab-inactive';
                document.getElementById('sectionConges').classList.remove('hidden');
                document.getElementById('sectionAvances').classList.add('hidden');
            } else {
                document.getElementById('tabConges').className = 'flex-1 py-3 px-6 rounded-md font-medium transition-all duration-200 tab-inactive';
                document.getElementById('tabAvances').className = 'flex-1 py-3 px-6 rounded-md font-medium transition-all duration-200 tab-active';
                document.getElementById('sectionConges').classList.add('hidden');
                document.getElementById('sectionAvances').classList.remove('hidden');
                
                // Calcul automatique des avances si les données de base sont disponibles
                if (resultatCalcul.donnees) {
                    calculerAvance();
                }
            }
        }

        function updateProgress() {
            const requiredFields = ['nomComplet', 'typeContrat', 'dateEmbauche', 'dateActuelle', 'age', 'sexe'];
            const filledFields = requiredFields.filter(fieldId => {
                const field = document.getElementById(fieldId);
                return field && field.value.trim() !== '';
            });

            const progress = (filledFields.length / requiredFields.length) * 100;
            
            document.getElementById('progressBar').style.width = progress + '%';
            
            let progressText = '';
            if (progress === 0) {
                progressText = 'Remplissez le formulaire pour commencer';
            } else if (progress < 100) {
                progressText = `Progression : ${Math.round(progress)}% (${filledFields.length}/${requiredFields.length} champs remplis)`;
            } else {
                progressText = 'Formulaire complet - Prêt pour le calcul';
            }
            
            document.getElementById('progressText').textContent = progressText;
        }

        function validateField(fieldId) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(fieldId + '-error');
            
            if (!field || !errorDiv) return true;

            let isValid = true;
            let errorMessage = '';

            switch (fieldId) {
                case 'nomComplet':
                    if (!field.value.trim()) {
                        isValid = false;
                        errorMessage = 'Le nom complet est obligatoire';
                    } else if (field.value.trim().length < 2) {
                        isValid = false;
                        errorMessage = 'Le nom doit contenir au moins 2 caractères';
                    }
                    break;

                case 'typeContrat':
                    if (!field.value) {
                        isValid = false;
                        errorMessage = 'Veuillez sélectionner un type de contrat';
                    }
                    break;

                case 'dateEmbauche':
                    if (!field.value) {
                        isValid = false;
                        errorMessage = 'La date d\'embauche est obligatoire';
                    } else {
                        const dateEmbauche = new Date(field.value);
                        const dateActuelle = new Date(document.getElementById('dateActuelle').value || new Date());
                        
                        if (dateEmbauche >= dateActuelle) {
                            isValid = false;
                            errorMessage = 'La date d\'embauche doit être antérieure à la date de calcul';
                        } else if (dateEmbauche < new Date('1960-01-01')) {
                            isValid = false;
                            errorMessage = 'Date d\'embauche trop ancienne';
                        }
                    }
                    break;

                case 'dateActuelle':
                    if (!field.value) {
                        isValid = false;
                        errorMessage = 'La date de calcul est obligatoire';
                    }
                    break;

                case 'age':
                    const age = parseInt(field.value);
                    if (!age || age < 14 || age > 65) {
                        isValid = false;
                        errorMessage = 'L\'âge doit être compris entre 14 et 65 ans';
                    }
                    break;

                case 'sexe':
                    if (!field.value) {
                        isValid = false;
                        errorMessage = 'Veuillez indiquer votre sexe';
                    }
                    break;
            }

            if (isValid) {
                field.classList.remove('form-field-error');
                errorDiv.textContent = '';
                errorDiv.classList.add('hidden');
                delete validationErrors[fieldId];
            } else {
                field.classList.add('form-field-error');
                errorDiv.textContent = errorMessage;
                errorDiv.classList.remove('hidden');
                validationErrors[fieldId] = errorMessage;
            }

            return isValid;
        }

        function validateAllFields() {
            const requiredFields = ['nomComplet', 'typeContrat', 'dateEmbauche', 'dateActuelle', 'age', 'sexe'];
            let allValid = true;

            requiredFields.forEach(fieldId => {
                if (!validateField(fieldId)) {
                    allValid = false;
                }
            });

            return allValid;
        }

        // Calcul de la période anniversaire d'embauche (CORRIGÉ)
        function calculerPeriodeAnniversaire(dateEmbauche, dateCalcul) {
            const embauche = new Date(dateEmbauche);
            const calcul = new Date(dateCalcul);
            
            // Calcul de l'ancienneté totale en mois
            const diffTime = calcul.getTime() - embauche.getTime();
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            const ancienneteTotaleMois = diffDays / CONFIG.JOURS_PAR_MOIS;
            
            // Détermination de la période anniversaire courante
            const anniversaireEnCours = Math.floor(ancienneteTotaleMois / 12);
            
            // Calcul des dates de début et fin de période
            const debutPeriode = new Date(embauche);
            debutPeriode.setFullYear(embauche.getFullYear() + anniversaireEnCours);
            
            const finPeriode = new Date(debutPeriode);
            finPeriode.setFullYear(finPeriode.getFullYear() + 1);
            finPeriode.setDate(finPeriode.getDate() - 1);
            
            // Si la date de calcul est avant la fin de la première période anniversaire
            if (calcul < finPeriode && anniversaireEnCours === 0) {
                // Première période : de l'embauche à la date de calcul
                const moisEcoules = Math.floor(ancienneteTotaleMois);
                return {
                    debutPeriode: embauche,
                    finPeriode: calcul, // CORRECTION: garder l'objet Date
                    moisAcquis: moisEcoules,
                    estPeriodeComplete: ancienneteTotaleMois >= 12,
                    numeroPeriode: 1,
                    ancienneteTotale: ancienneteTotaleMois
                };
            } else {
                // Période anniversaire complète
                // CORRECTION: utiliser une condition explicite au lieu de Math.min
                const finPeriodeCalcul = calcul < finPeriode ? calcul : finPeriode;
                
                return {
                    debutPeriode: debutPeriode,
                    finPeriode: finPeriodeCalcul, // CORRECTION: garder l'objet Date
                    moisAcquis: Math.min(12, Math.floor(ancienneteTotaleMois - (anniversaireEnCours * 12))),
                    estPeriodeComplete: ancienneteTotaleMois >= ((anniversaireEnCours + 1) * 12),
                    numeroPeriode: anniversaireEnCours + 1,
                    ancienneteTotale: ancienneteTotaleMois
                };
            }
        }

        function calculerConges() {
            try {
                if (!validateAllFields()) {
                    showAlert('Veuillez corriger les erreurs dans le formulaire avant de continuer.', 'error');
                    return;
                }

                setLoadingState(true);

                setTimeout(() => {
                    try {
                        performCalculation();
                        setLoadingState(false);
                    } catch (error) {
                        setLoadingState(false);
                        console.error('Erreur lors du calcul:', error);
                        showAlert(`Erreur lors du calcul : ${error.message}`, 'error');
                    }
                }, 500);

            } catch (error) {
                setLoadingState(false);
                console.error('Erreur lors de l\'initialisation du calcul:', error);
                showAlert(`Erreur technique : ${error.message}`, 'error');
            }
        }

        function performCalculation() {
            const donnees = collectFormData();
            
            // Calcul de la période anniversaire
            const periodeAnniversaire = calculerPeriodeAnniversaire(donnees.dateEmbauche, donnees.dateActuelle);
            
            resultatCalcul = {
                donnees: donnees,
                periodeAnniversaire: periodeAnniversaire,
                dateCalcul: new Date()
            };

            // Calculs selon le type de congé
            switch (donnees.typeConge) {
                case 'annuel':
                    calculerCongeAnnuel();
                    break;
                case 'maternite':
                    calculerCongeMaternite();
                    break;
                case 'paternite':
                    calculerCongePaternite();
                    break;
                case 'evenements':
                    calculerCongeEvenements();
                    break;
                case 'maladie':
                    calculerCongeMaladie();
                    break;
                default:
                    throw new Error('Type de congé non reconnu');
            }

            afficherResultats();
            document.getElementById('pdfBtn').disabled = false;
            document.getElementById('printBtn').disabled = false;
            showAlert('Calcul effectué avec succès selon le Code du Travail gabonais.', 'success');
        }

        function collectFormData() {
            const nomComplet = document.getElementById('nomComplet').value.trim();
            const dateEmbaucheStr = document.getElementById('dateEmbauche').value;
            const dateActuelleStr = document.getElementById('dateActuelle').value;

            if (!nomComplet) throw new Error('Nom complet manquant');
            if (!dateEmbaucheStr) throw new Error('Date d\'embauche manquante');
            if (!dateActuelleStr) throw new Error('Date actuelle manquante');

            return {
                nomComplet: nomComplet,
                typeContrat: document.getElementById('typeContrat').value,
                dateEmbauche: new Date(dateEmbaucheStr),
                dateActuelle: new Date(dateActuelleStr),
                age: parseInt(document.getElementById('age').value),
                salaireMoyen: parseInt(document.getElementById('salaireMoyen').value) || 0,
                sexe: document.getElementById('sexe').value,
                enfantsCharge: parseInt(document.getElementById('enfantsCharge').value) || 0,
                typeConge: document.querySelector('input[name="typeConge"]:checked').value,
                congesPris: parseInt(document.getElementById('congesPris').value) || 0,
                absencesInjustifiees: parseInt(document.getElementById('absencesInjustifiees').value) || 0
            };
        }

        function calculerCongeAnnuel() {
            const donnees = resultatCalcul.donnees;
            const periode = resultatCalcul.periodeAnniversaire;
            
            // Calcul des jours de base (Article 222 confirmé)
            const tauxJourParMois = donnees.age < 18 ? CONFIG.JOURS_BASE_PAR_MOIS_MINEUR : CONFIG.JOURS_BASE_PAR_MOIS;
            const joursBase = Math.floor(periode.moisAcquis * tauxJourParMois);
            
            // Majorations pour enfants (à vérifier selon convention - indication prudente)
            let majorationEnfants = 0;
            if (donnees.sexe === 'F' && donnees.enfantsCharge > 0) {
                // Note: cette majoration nécessite vérification selon la convention collective
                const facteurProportionnel = periode.moisAcquis / 12;
                majorationEnfants = 0; // Remis à 0 en attendant clarification légale
            }
            
            // Majoration d'ancienneté (à vérifier - indication prudente)
            let majorationAnciennete = 0;
            // Note: cette majoration nécessite vérification selon la convention collective
            
            // Total avant réductions
            const totalAvantReduction = joursBase + majorationEnfants + majorationAnciennete;
            
            // Réduction pour absences injustifiées (max 10%)
            const reductionMax = Math.floor(totalAvantReduction * 0.1);
            const reductionAppliquee = Math.min(donnees.absencesInjustifiees, reductionMax);
            
            // Total des droits
            const totalDroits = Math.max(0, totalAvantReduction - reductionAppliquee);
            
            // Solde disponible
            const soldeDisponible = Math.max(0, totalDroits - donnees.congesPris);
            
            // Calcul des allocations
            let allocationTotale = 0;
            let allocationDisponible = 0;
            if (donnees.salaireMoyen > 0) {
                const allocationJournaliere = donnees.salaireMoyen / CONFIG.JOURS_OUVRABLES_PAR_MOIS;
                allocationTotale = totalDroits * allocationJournaliere;
                allocationDisponible = soldeDisponible * allocationJournaliere;
            }
            
            resultatCalcul.resultat = {
                type: 'annuel',
                periode: {
                    debut: periode.debutPeriode.toLocaleDateString('fr-FR'),
                    fin: periode.finPeriode.toLocaleDateString('fr-FR'), // CORRECTION: maintenant ça marche
                    numero: periode.numeroPeriode,
                    complete: periode.estPeriodeComplete
                },
                calcul: {
                    moisAcquis: periode.moisAcquis,
                    joursBase: joursBase,
                    majorationEnfants: majorationEnfants,
                    majorationAnciennete: majorationAnciennete,
                    totalAvantReduction: totalAvantReduction,
                    reductionAppliquee: reductionAppliquee,
                    totalDroits: totalDroits,
                    congesPris: donnees.congesPris,
                    soldeDisponible: soldeDisponible
                },
                allocation: {
                    totale: allocationTotale,
                    disponible: allocationDisponible,
                    journaliere: donnees.salaireMoyen > 0 ? donnees.salaireMoyen / CONFIG.JOURS_OUVRABLES_PAR_MOIS : 0
                }
            };
        }

        function calculerCongeMaternite() {
            const donnees = resultatCalcul.donnees;
            
            if (donnees.sexe !== 'F') {
                throw new Error('Le congé de maternité est réservé aux femmes');
            }
            
            const semainesBase = CONFIG.DUREE_MATERNITE_SEMAINES;
            const joursBase = semainesBase * 7;
            
            // Extensions possibles
            let extensionNaissanceMultiple = 0; // À vérifier selon la situation
            let extensionComplication = 0; // À vérifier selon prescription médicale
            
            let allocationTotale = 0;
            if (donnees.salaireMoyen > 0) {
                allocationTotale = (semainesBase * donnees.salaireMoyen * 7) / 30;
            }
            
            resultatCalcul.resultat = {
                type: 'maternite',
                duree: {
                    semainesBase: semainesBase,
                    joursBase: joursBase,
                    repartition: {
                        avantAccouchement: 6 * 7,
                        apresAccouchement: 8 * 7
                    }
                },
                extensions: {
                    naissanceMultiple: extensionNaissanceMultiple,
                    complication: extensionComplication
                },
                allocation: {
                    totale: allocationTotale,
                    maintienSalaire: true
                }
            };
        }

        function calculerCongePaternite() {
            const donnees = resultatCalcul.donnees;
            
            if (donnees.sexe !== 'M') {
                throw new Error('Le congé de paternité est réservé aux hommes');
            }
            
            const jours = CONFIG.DUREE_PATERNITE_JOURS;
            
            let allocationTotale = 0;
            if (donnees.salaireMoyen > 0) {
                allocationTotale = (jours * donnees.salaireMoyen) / 30;
            }
            
            resultatCalcul.resultat = {
                type: 'paternite',
                duree: {
                    jours: jours,
                    consecutifs: true
                },
                conditions: {
                    certificatNaissance: true,
                    delaiInformation: 'Un mois avant date présumée'
                },
                allocation: {
                    totale: allocationTotale,
                    maintienSalaire: true
                }
            };
        }

        function calculerCongeEvenements() {
            const evenements = [
                { type: 'Mariage du salarié', jours: '3 (à vérifier)' },
                { type: 'Naissance/adoption', jours: '3 (à vérifier)' },
                { type: 'Décès conjoint/enfant', jours: '3 (à vérifier)' },
                { type: 'Décès parents/beaux-parents', jours: '2 (à vérifier)' },
                { type: 'Mariage enfant', jours: '1 (à vérifier)' }
            ];
            
            let allocationTotale = 0;
            const joursEstimes = 10; // Estimation, à vérifier selon convention
            if (resultatCalcul.donnees.salaireMoyen > 0) {
                allocationTotale = (joursEstimes * resultatCalcul.donnees.salaireMoyen) / 30;
            }
            
            resultatCalcul.resultat = {
                type: 'evenements',
                droits: {
                    evenements: evenements,
                    note: 'Les durées peuvent varier selon la convention collective applicable'
                },
                allocation: {
                    totale: allocationTotale,
                    maintienSalaire: true
                }
            };
        }

        function calculerCongeMaladie() {
            const maxMois = CONFIG.MOIS_MALADIE_MAX;
            const maxJours = maxMois * 30;
            
            let allocationTotale = 0;
            if (resultatCalcul.donnees.salaireMoyen > 0) {
                allocationTotale = maxMois * resultatCalcul.donnees.salaireMoyen;
            }
            
            resultatCalcul.resultat = {
                type: 'maladie',
                duree: {
                    maxMois: maxMois,
                    maxJours: maxJours
                },
                conditions: {
                    certificatMedical: true,
                    contreExpertise: 'Après 3 certificats (Article 55)'
                },
                allocation: {
                    totale: allocationTotale,
                    maintienSalaire: 'Selon Article 55',
                    dureeRemuneration: maxMois + ' mois maximum'
                }
            };
        }

        function calculerAvance() {
            if (!resultatCalcul.donnees) {
                showAlert('Veuillez d\'abord effectuer le calcul de vos droits aux congés dans l\'onglet principal.', 'warning');
                return;
            }
            
            if (resultatCalcul.donnees.typeConge !== 'annuel') {
                showAlert('Les avances ne sont calculables que pour les congés annuels.', 'warning');
                return;
            }
            
            const joursAvanceDemandes = parseInt(document.getElementById('joursAvance').value) || 0;
            const dateAvance = new Date(document.getElementById('dateAvance').value || new Date());
            
            if (joursAvanceDemandes <= 0) {
                showAlert('Veuillez indiquer un nombre de jours d\'avance valide.', 'error');
                return;
            }
            
            // Recalcul des droits à la date de l'avance
            const periodeAvance = calculerPeriodeAnniversaire(resultatCalcul.donnees.dateEmbauche, dateAvance);
            const tauxJourParMois = resultatCalcul.donnees.age < 18 ? CONFIG.JOURS_BASE_PAR_MOIS_MINEUR : CONFIG.JOURS_BASE_PAR_MOIS;
            const droitsAcquisALaDate = Math.floor(periodeAvance.moisAcquis * tauxJourParMois);
            
            // Vérification de la faisabilité
            const avancePossible = joursAvanceDemandes <= droitsAcquisALaDate;
            const droitsRestants = Math.max(0, droitsAcquisALaDate - joursAvanceDemandes);
            
            // Calcul des montants
            const allocationJournaliere = resultatCalcul.donnees.salaireMoyen > 0 ? 
                resultatCalcul.donnees.salaireMoyen / CONFIG.JOURS_OUVRABLES_PAR_MOIS : 0;
            const montantAvance = joursAvanceDemandes * allocationJournaliere;
            const montantTotalDisponible = droitsAcquisALaDate * allocationJournaliere;
            
            const resultatsAvance = {
                dateAvance: dateAvance.toLocaleDateString('fr-FR'),
                joursAvanceDemandes: joursAvanceDemandes,
                droitsAcquisALaDate: droitsAcquisALaDate,
                avancePossible: avancePossible,
                droitsRestants: droitsRestants,
                montantAvance: montantAvance,
                montantTotalDisponible: montantTotalDisponible,
                allocationJournaliere: allocationJournaliere
            };
            
            afficherResultatsAvance(resultatsAvance);
        }

        function afficherResultats() {
            afficherResume();
            afficherTableauDetaille();
        }

        function afficherResume() {
            const donnees = resultatCalcul.donnees;
            const resultat = resultatCalcul.resultat;
            
            let resumeHTML = `
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-lg mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                ${donnees.nomComplet}
                            </h3>
                            <p class="text-blue-100">
                                Contrat: ${donnees.typeContrat} | 
                                Embauche: ${donnees.dateEmbauche.toLocaleDateString('fr-FR')} | 
                                Type de congé: ${getTypeCongeLabel(donnees.typeConge)}
                            </p>
                            <p class="text-blue-100 text-sm">
                                Calculé le: ${donnees.dateActuelle.toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold">${getDroitsDisplay()}</p>
                            <p class="text-blue-100 text-sm">${getDroitsLabel()}</p>
                        </div>
                    </div>
                </div>
            `;
            
            if (resultat.type === 'annuel' && resultat.periode) {
                resumeHTML += `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">${resultat.calcul.totalDroits}</div>
                            <div class="text-sm text-green-700">Jours acquis</div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">${resultat.calcul.soldeDisponible}</div>
                            <div class="text-sm text-blue-700">Jours disponibles</div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">${formatMontant(resultat.allocation.disponible)}</div>
                            <div class="text-sm text-purple-700">Allocation disponible</div>
                        </div>
                    </div>
                `;
                
                if (!resultat.periode.complete) {
                    resumeHTML += `
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Information:</strong> Période anniversaire en cours. 
                                Les droits sont calculés proportionnellement (${resultat.calcul.moisAcquis} mois sur 12).
                            </p>
                        </div>
                    `;
                }
            }
            
            // Avertissement de vérification pour certains calculs
            if (resultat.type === 'annuel') {
                resumeHTML += `
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-sm text-amber-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Note importante:</strong> Ce calcul se base sur les dispositions de base du Code du Travail. 
                            Les majorations peuvent varier selon votre convention collective sectorielle.
                        </p>
                    </div>
                `;
            }
            
            document.getElementById('resumeContenu').innerHTML = resumeHTML;
        }

        function afficherTableauDetaille() {
            const resultat = resultatCalcul.resultat;
            let tableauHTML = '';
            
            if (resultat.type === 'annuel') {
                tableauHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left p-3 border-b">Élément</th>
                                    <th class="text-right p-3 border-b">Jours</th>
                                    <th class="text-right p-3 border-b">Montant (FCFA)</th>
                                    <th class="text-center p-3 border-b">Base légale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="p-3">Droits de base (${resultat.calcul.moisAcquis} mois)</td>
                                    <td class="text-right p-3">${resultat.calcul.joursBase}</td>
                                    <td class="text-right p-3">${formatMontant(resultat.calcul.joursBase * resultat.allocation.journaliere)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-blue-100 px-2 py-1 rounded">Art. 222</span></td>
                                </tr>
                                ${resultat.calcul.majorationEnfants > 0 ? `
                                <tr class="border-b">
                                    <td class="p-3">Majoration enfants (${resultatCalcul.donnees.enfantsCharge})</td>
                                    <td class="text-right p-3">+${resultat.calcul.majorationEnfants}</td>
                                    <td class="text-right p-3">+${formatMontant(resultat.calcul.majorationEnfants * resultat.allocation.journaliere)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-amber-100 px-2 py-1 rounded">À vérifier</span></td>
                                </tr>
                                ` : ''}
                                ${resultat.calcul.majorationAnciennete > 0 ? `
                                <tr class="border-b">
                                    <td class="p-3">Majoration ancienneté</td>
                                    <td class="text-right p-3">+${resultat.calcul.majorationAnciennete}</td>
                                    <td class="text-right p-3">+${formatMontant(resultat.calcul.majorationAnciennete * resultat.allocation.journaliere)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-amber-100 px-2 py-1 rounded">À vérifier</span></td>
                                </tr>
                                ` : ''}
                                ${resultat.calcul.reductionAppliquee > 0 ? `
                                <tr class="border-b">
                                    <td class="p-3">Réduction absences injustifiées</td>
                                    <td class="text-right p-3 text-red-600">-${resultat.calcul.reductionAppliquee}</td>
                                    <td class="text-right p-3 text-red-600">-${formatMontant(resultat.calcul.reductionAppliquee * resultat.allocation.journaliere)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-red-100 px-2 py-1 rounded">Pratique</span></td>
                                </tr>
                                ` : ''}
                                <tr class="border-b bg-green-50">
                                    <td class="p-3 font-semibold">Total des droits acquis</td>
                                    <td class="text-right p-3 font-semibold">${resultat.calcul.totalDroits}</td>
                                    <td class="text-right p-3 font-semibold">${formatMontant(resultat.allocation.totale)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-green-100 px-2 py-1 rounded">Art. 224</span></td>
                                </tr>
                                ${resultat.calcul.congesPris > 0 ? `
                                <tr class="border-b">
                                    <td class="p-3">Congés déjà pris</td>
                                    <td class="text-right p-3 text-red-600">-${resultat.calcul.congesPris}</td>
                                    <td class="text-right p-3 text-red-600">-${formatMontant(resultat.calcul.congesPris * resultat.allocation.journaliere)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-gray-100 px-2 py-1 rounded">Déclaré</span></td>
                                </tr>
                                ` : ''}
                                <tr class="bg-blue-50">
                                    <td class="p-3 font-bold">Solde disponible</td>
                                    <td class="text-right p-3 font-bold text-blue-600">${resultat.calcul.soldeDisponible}</td>
                                    <td class="text-right p-3 font-bold text-blue-600">${formatMontant(resultat.allocation.disponible)}</td>
                                    <td class="text-center p-3"><span class="text-xs bg-blue-100 px-2 py-1 rounded">Art. 225</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <strong>Période de référence:</strong> 
                            Du ${resultat.periode.debut} au ${resultat.periode.fin}
                            ${resultat.allocation.journaliere > 0 ? ` | <strong>Allocation journalière:</strong> ${formatMontant(resultat.allocation.journaliere)}` : ''}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Les mentions "À vérifier" indiquent des dispositions qui peuvent varier selon votre convention collective.
                        </p>
                    </div>
                `;
            } else {
                // Autres types de congés
                tableauHTML = getTableauAutresConges(resultat);
            }
            
            document.getElementById('tableauContenu').innerHTML = tableauHTML;
        }

        function afficherResultatsAvance(resultats) {
            let statusClass = resultats.avancePossible ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
            let statusIcon = resultats.avancePossible ? 'fas fa-check-circle text-green-600' : 'fas fa-times-circle text-red-600';
            let statusText = resultats.avancePossible ? 'AVANCE POSSIBLE' : 'AVANCE NON POSSIBLE';
            
            const avanceHTML = `
                <div class="space-y-4">
                    <div class="${statusClass} border rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold flex items-center">
                                <i class="${statusIcon} mr-2"></i>
                                ${statusText}
                            </h3>
                            <div class="text-right">
                                <div class="text-lg font-bold">
                                    ${resultats.joursAvanceDemandes} jours demandés
                                </div>
                                <div class="text-sm text-gray-600">
                                    Sur ${resultats.droitsAcquisALaDate} jours acquis
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white border rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-3">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Détails de l'employé
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>👤 Nom :</span>
                                    <span class="font-medium">${resultatCalcul.donnees.nomComplet}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>📋 Contrat :</span>
                                    <span class="font-medium">${resultatCalcul.donnees.typeContrat}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>📅 Embauche :</span>
                                    <span class="font-medium">${resultatCalcul.donnees.dateEmbauche.toLocaleDateString('fr-FR')}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>⏱️ Ancienneté :</span>
                                    <span class="font-medium">${Math.floor(resultatCalcul.periodeAnniversaire.ancienneteTotale)} mois</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-3">
                                <i class="fas fa-calculator text-green-600 mr-2"></i>
                                Calcul de l'avance
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>🏖️ Droits acquis à ce jour :</span>
                                    <span class="font-medium">${resultats.droitsAcquisALaDate} jours</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>📋 Avance demandée :</span>
                                    <span class="font-medium">${resultats.joursAvanceDemandes} jours</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>💰 Indemnité journalière :</span>
                                    <span class="font-medium">${formatMontant(resultats.allocationJournaliere)}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span>🏖️ Droits restants :</span>
                                    <span class="font-medium">${resultats.droitsRestants} jours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold">${formatMontant(resultats.montantAvance)}</div>
                                <div class="text-blue-100">Montant de l'avance demandée</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold">${formatMontant(resultats.montantTotalDisponible)}</div>
                                <div class="text-blue-100">Total disponible à ce jour</div>
                            </div>
                        </div>
                    </div>
                    
                    ${!resultats.avancePossible ? `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-700">
                            <strong><i class="fas fa-exclamation-triangle mr-1"></i> Avance non possible :</strong>
                            Vous demandez ${resultats.joursAvanceDemandes} jours mais vous n'avez acquis que ${resultats.droitsAcquisALaDate} jours à ce jour.
                            Maximum disponible : ${resultats.droitsAcquisALaDate} jours (${formatMontant(resultats.montantTotalDisponible)}).
                        </p>
                    </div>
                    ` : ''}
                    
                    <div class="legal-disclaimer">
                        <p class="text-amber-700 text-sm">
                            <i class="fas fa-balance-scale mr-1"></i>
                            <strong>Rappel légal :</strong> L'avance sur congé reste soumise à l'accord de l'employeur et aux modalités prévues par votre contrat ou convention collective.
                        </p>
                    </div>
                </div>
            `;
            
            document.getElementById('avanceContenu').innerHTML = avanceHTML;
        }

        function getTypeCongeLabel(type) {
            const labels = {
                'annuel': 'Congé annuel ordinaire',
                'maternite': 'Congé de maternité',
                'paternite': 'Congé de paternité',
                'evenements': 'Événements familiaux',
                'maladie': 'Congé maladie'
            };
            return labels[type] || type;
        }

        function getDroitsDisplay() {
            const resultat = resultatCalcul.resultat;
            switch (resultat.type) {
                case 'annuel':
                    return resultat.calcul.soldeDisponible;
                case 'maternite':
                    return resultat.duree.semainesBase + ' semaines';
                case 'paternite':
                    return resultat.duree.jours + ' jours';
                case 'evenements':
                    return 'Variable';
                case 'maladie':
                    return resultat.duree.maxMois + ' mois';
                default:
                    return '0';
            }
        }

        function getDroitsLabel() {
            const resultat = resultatCalcul.resultat;
            switch (resultat.type) {
                case 'annuel':
                    return 'Jours disponibles';
                case 'maternite':
                    return 'Durée du congé';
                case 'paternite':
                    return 'Jours consécutifs';
                case 'evenements':
                    return 'Selon événement';
                case 'maladie':
                    return 'Durée maximale';
                default:
                    return '';
            }
        }

        function getTableauAutresConges(resultat) {
            switch (resultat.type) {
                case 'maternite':
                    return `
                        <div class="space-y-4">
                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                                <h4 class="font-semibold text-pink-800 mb-2">Durée du congé de maternité</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <strong>Avant accouchement :</strong> 6 semaines (42 jours)
                                    </div>
                                    <div>
                                        <strong>Après accouchement :</strong> 8 semaines (56 jours)
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-pink-200">
                                    <strong>Total :</strong> ${resultat.duree.semainesBase} semaines (${resultat.duree.joursBase} jours)
                                </div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-800 mb-2">Rémunération</h4>
                                <p class="text-sm text-green-700">
                                    Maintien intégral du salaire pendant toute la durée du congé (Article 208).
                                    ${resultat.allocation.totale > 0 ? `Allocation estimée : ${formatMontant(resultat.allocation.totale)}` : ''}
                                </p>
                            </div>
                            
                            <div class="legal-disclaimer">
                                <p class="text-amber-700 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Base légale :</strong> Article 208 du Code du Travail gabonais - Extensions possibles selon prescriptions médicales.
                                </p>
                            </div>
                        </div>
                    `;
                    
                case 'paternite':
                    return `
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">Congé de paternité</h4>
                                <div class="text-sm space-y-2">
                                    <div><strong>Durée :</strong> ${resultat.duree.jours} jours consécutifs</div>
                                    <div><strong>Condition :</strong> Présentation du certificat de naissance</div>
                                    <div><strong>Délai :</strong> ${resultat.conditions.delaiInformation}</div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-800 mb-2">Rémunération</h4>
                                <p class="text-sm text-green-700">
                                    Maintien intégral du salaire (Article 55).
                                    ${resultat.allocation.totale > 0 ? `Allocation : ${formatMontant(resultat.allocation.totale)}` : ''}
                                </p>
                            </div>
                            
                            <div class="legal-disclaimer">
                                <p class="text-amber-700 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Base légale :</strong> Article 54 alinéa 13 du Code du Travail gabonais.
                                </p>
                            </div>
                        </div>
                    `;
                    
                case 'evenements':
                    return `
                        <div class="space-y-4">
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <h4 class="font-semibold text-orange-800 mb-2">Événements familiaux</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="text-left p-2">Événement</th>
                                                <th class="text-right p-2">Jours</th>
                                                <th class="text-center p-2">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${resultat.droits.evenements.map(evt => `
                                                <tr class="border-b">
                                                    <td class="p-2">${evt.type}</td>
                                                    <td class="text-right p-2">${evt.jours}</td>
                                                    <td class="text-center p-2"><span class="text-xs bg-amber-100 px-2 py-1 rounded">À vérifier</span></td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="legal-warning">
                                <p class="text-red-700 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Important :</strong> ${resultat.droits.note}
                                </p>
                            </div>
                        </div>
                    `;
                    
                case 'maladie':
                    return `
                        <div class="space-y-4">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <h4 class="font-semibold text-red-800 mb-2">Congé maladie</h4>
                                <div class="text-sm space-y-2">
                                    <div><strong>Durée maximale :</strong> ${resultat.duree.maxMois} mois (${resultat.duree.maxJours} jours)</div>
                                    <div><strong>Condition :</strong> ${resultat.conditions.certificatMedical ? 'Certificat médical obligatoire' : 'N/A'}</div>
                                    <div><strong>Contre-expertise :</strong> ${resultat.conditions.contreExpertise}</div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-800 mb-2">Rémunération</h4>
                                <p class="text-sm text-green-700">
                                    ${resultat.allocation.maintienSalaire} pendant ${resultat.allocation.dureeRemuneration}.
                                    ${resultat.allocation.totale > 0 ? `Allocation maximale : ${formatMontant(resultat.allocation.totale)}` : ''}
                                </p>
                            </div>
                            
                            <div class="legal-disclaimer">
                                <p class="text-amber-700 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Base légale :</strong> Articles 54 et 55 du Code du Travail gabonais.
                                </p>
                            </div>
                        </div>
                    `;
                    
                default:
                    return '<p class="text-gray-500">Détails non disponibles pour ce type de congé.</p>';
            }
        }

        function formatMontant(montant) {
            if (!montant || montant === 0) return '0 FCFA';
            return new Intl.NumberFormat('fr-FR').format(Math.round(montant)) + ' FCFA';
        }

        function setLoadingState(loading) {
            const btn = document.getElementById('calculBtn');
            const btnText = document.getElementById('calculBtnText');
            
            if (loading) {
                btn.classList.add('loading');
                btnText.innerHTML = '<div class="spinner"></div>Calcul en cours...';
                btn.disabled = true;
            } else {
                btn.classList.remove('loading');
                btnText.innerHTML = 'Calculer mes congés';
                btn.disabled = false;
            }
        }

        function showAlert(message, type = 'info') {
            const container = document.getElementById('alertContainer');
            const alertClass = {
                'success': 'bg-green-50 border-green-200 text-green-800',
                'error': 'bg-red-50 border-red-200 text-red-800',
                'warning': 'bg-yellow-50 border-yellow-200 text-yellow-800',
                'info': 'bg-blue-50 border-blue-200 text-blue-800'
            };
            
            const iconClass = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle'
            };
            
            const alert = document.createElement('div');
            alert.className = `${alertClass[type]} border rounded-lg p-4 mb-4 flex items-center`;
            alert.innerHTML = `
                <i class="${iconClass[type]} mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-lg hover:opacity-70">×</button>
            `;
            
            container.appendChild(alert);
            
            // Auto-suppression après 5 secondes
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.remove();
                }
            }, 5000);
        }

        function imprimerResultats() {
            if (!resultatCalcul.donnees) {
                showAlert('Aucun résultat à imprimer. Effectuez d\'abord un calcul.', 'warning');
                return;
            }

            // Préparation du contenu d'impression
            prepareContentForPrint();
            
            // Sauvegarde de l'état actuel
            const originalTitle = document.title;
            
            // Modification temporaire pour l'impression
            document.title = `Congés ${resultatCalcul.donnees.nomComplet} - ${new Date().toLocaleDateString('fr-FR')}`;
            
            // Affichage de la section d'impression
            const printSection = document.querySelector('.print-only');
            printSection.style.display = 'block';
            
            // Impression
            window.print();
            
            // Restauration
            document.title = originalTitle;
            printSection.style.display = 'none';
        }

        function prepareContentForPrint() {
            const donnees = resultatCalcul.donnees;
            const resultat = resultatCalcul.resultat;
            
            let printHTML = `
                <div style="margin-bottom: 30px;">
                    <h2 style="font-size: 16pt; font-weight: bold; margin-bottom: 15px; color: #000;">
                        INFORMATIONS DU SALARIÉ
                    </h2>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold; width: 30%;">Nom complet :</td>
                            <td style="border: 1px solid #000; padding: 8px;">${donnees.nomComplet}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Type de contrat :</td>
                            <td style="border: 1px solid #000; padding: 8px;">${donnees.typeContrat}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Date d'embauche :</td>
                            <td style="border: 1px solid #000; padding: 8px;">${donnees.dateEmbauche.toLocaleDateString('fr-FR')}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Date de calcul :</td>
                            <td style="border: 1px solid #000; padding: 8px;">${donnees.dateActuelle.toLocaleDateString('fr-FR')}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Âge :</td>
                            <td style="border: 1px solid #000; padding: 8px;">${donnees.age} ans</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Type de congé :</td>
                            <td style="border: 1px solid #000; padding: 8px;">${getTypeCongeLabel(donnees.typeConge)}</td>
                        </tr>
                    </table>
                </div>
            `;

            if (resultat.type === 'annuel') {
                printHTML += `
                    <div style="margin-bottom: 30px;">
                        <h2 style="font-size: 16pt; font-weight: bold; margin-bottom: 15px; color: #000;">
                            CALCUL DES DROITS AUX CONGÉS ANNUELS
                        </h2>
                        
                        <div style="background-color: #f8f9fa; padding: 15px; border: 1px solid #000; margin-bottom: 20px;">
                            <h3 style="font-size: 14pt; font-weight: bold; margin-bottom: 10px;">RÉSUMÉ</h3>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span><strong>Période de référence :</strong></span>
                                <span>Du ${resultat.periode.debut} au ${resultat.periode.fin}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span><strong>Mois acquis :</strong></span>
                                <span>${resultat.calcul.moisAcquis} mois</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span><strong>Total des droits :</strong></span>
                                <span style="font-weight: bold; font-size: 14pt;">${resultat.calcul.totalDroits} jours</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span><strong>Solde disponible :</strong></span>
                                <span style="font-weight: bold; font-size: 14pt; color: #0066cc;">${resultat.calcul.soldeDisponible} jours</span>
                            </div>
                        </div>
                        
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <thead>
                                <tr style="background-color: #e9ecef;">
                                    <th style="border: 1px solid #000; padding: 8px; text-align: left;">Élément</th>
                                    <th style="border: 1px solid #000; padding: 8px; text-align: right;">Jours</th>
                                    <th style="border: 1px solid #000; padding: 8px; text-align: right;">Montant (FCFA)</th>
                                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Base légale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 8px;">Droits de base (${resultat.calcul.moisAcquis} mois)</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right;">${resultat.calcul.joursBase}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right;">${formatMontant(resultat.calcul.joursBase * resultat.allocation.journaliere)}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Art. 222</td>
                                </tr>
                                ${resultat.calcul.reductionAppliquee > 0 ? `
                                <tr>
                                    <td style="border: 1px solid #000; padding: 8px;">Réduction absences injustifiées</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; color: red;">-${resultat.calcul.reductionAppliquee}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; color: red;">-${formatMontant(resultat.calcul.reductionAppliquee * resultat.allocation.journaliere)}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Pratique</td>
                                </tr>` : ''}
                                <tr style="background-color: #d4edda;">
                                    <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">Total des droits acquis</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">${resultat.calcul.totalDroits}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">${formatMontant(resultat.allocation.totale)}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Art. 224</td>
                                </tr>
                                ${resultat.calcul.congesPris > 0 ? `
                                <tr>
                                    <td style="border: 1px solid #000; padding: 8px;">Congés déjà pris</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; color: red;">-${resultat.calcul.congesPris}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; color: red;">-${formatMontant(resultat.calcul.congesPris * resultat.allocation.journaliere)}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Déclaré</td>
                                </tr>` : ''}
                                <tr style="background-color: #cce5ff;">
                                    <td style="border: 1px solid #000; padding: 8px; font-weight: bold;">SOLDE DISPONIBLE</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold; color: #0066cc;">${resultat.calcul.soldeDisponible}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold; color: #0066cc;">${formatMontant(resultat.allocation.disponible)}</td>
                                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Art. 225</td>
                                </tr>
                            </tbody>
                        </table>
                `;
            } else {
                printHTML += `
                    <div style="margin-bottom: 30px;">
                        <h2 style="font-size: 16pt; font-weight: bold; margin-bottom: 15px; color: #000;">
                            DROITS AUX CONGÉS SPÉCIAUX
                        </h2>
                        <div style="background-color: #f8f9fa; padding: 15px; border: 1px solid #000;">
                            <h3 style="font-size: 14pt; margin-bottom: 10px;">${getTypeCongeLabel(donnees.typeConge)}</h3>
                            <p><strong>Droits accordés :</strong> ${getDroitsDisplay()}</p>
                            ${resultat.allocation && resultat.allocation.totale > 0 ? `<p><strong>Allocation :</strong> ${formatMontant(resultat.allocation.totale)}</p>` : ''}
                        </div>
                    </div>
                `;
            }

            printHTML += `
                <div style="margin-top: 40px; page-break-inside: avoid;">
                    <h2 style="font-size: 16pt; font-weight: bold; margin-bottom: 15px; color: #000;">
                        RÉFÉRENCES LÉGALES
                    </h2>
                    <div style="border: 1px solid #000; padding: 15px; background-color: #f8f9fa;">
                        <p style="margin-bottom: 10px;"><strong>Code du Travail gabonais 2021</strong></p>
                        <p style="margin-bottom: 5px;">Loi n°022/2021 du 19 novembre 2021 portant Code du Travail en République Gabonaise</p>
                        <ul style="margin-top: 10px; padding-left: 20px;">
                            <li>Article 222 : Congés annuels - 2 jours ouvrables par mois de service effectif</li>
                            <li>Article 224 : Acquisition des droits après 12 mois de service continu</li>
                            <li>Article 225 : Allocation de congé égale à la rémunération habituelle</li>
                            ${donnees.typeConge === 'maternite' ? '<li>Article 208 : Congé de maternité - 14 semaines</li>' : ''}
                            ${donnees.typeConge === 'paternite' ? '<li>Article 54 : Congé de paternité et suspensions de contrat</li>' : ''}
                            ${donnees.typeConge === 'maladie' ? '<li>Article 55 : Maintien rémunération congé maladie</li>' : ''}
                        </ul>
                    </div>
                </div>
                
                <div style="margin-top: 30px; padding: 15px; border: 2px solid #ff6b35; background-color: #fff3cd;">
                    <p style="margin: 0; font-weight: bold; color: #856404;">
                        AVERTISSEMENT LÉGAL : Ce document est informatif et ne constitue pas un acte juridique officiel. 
                        En cas de litige, seules les dispositions du Code du Travail gabonais font foi. 
                        Consultez votre convention collective et/ou l'Inspection du Travail pour des précisions.
                    </p>
                </div>
                
                <div style="margin-top: 30px; text-align: center; color: #666;">
                    <p style="margin: 0; font-size: 10pt;">
                        Document généré le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}
                    </p>
                    <p style="margin: 0; font-size: 10pt;">
                        Simulateur BFEV V.2.0 - Conforme au Code du Travail gabonais 2021
                    </p>
                </div>
            `;

            document.getElementById('printContent').innerHTML = printHTML;
        }

        function genererPDF() {
            if (!resultatCalcul.donnees) {
                showAlert('Aucun résultat à exporter. Effectuez d\'abord un calcul.', 'warning');
                return;
            }

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Configuration
                const margin = 20;
                let y = margin;
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                // Fonction pour ajouter une nouvelle page si nécessaire
                function checkPageBreak(neededHeight) {
                    if (y + neededHeight > pageHeight - margin) {
                        doc.addPage();
                        y = margin;
                        return true;
                    }
                    return false;
                }
                
                // En-tête avec logo et drapeau
                doc.setFontSize(18);
                doc.setFont(undefined, 'bold');
                doc.text('SIMULATION DE CONGÉS PAYÉS', pageWidth/2, y, { align: 'center' });
                
                y += 10;
                doc.setFontSize(12);
                doc.setFont(undefined, 'normal');
                doc.text('🇬🇦 République Gabonaise - Code du Travail 2021', pageWidth/2, y, { align: 'center' });
                
                y += 8;
                doc.setFontSize(10);
                doc.text('Loi n°022/2021 du 19 novembre 2021', pageWidth/2, y, { align: 'center' });
                
                y += 15;
                doc.line(margin, y, pageWidth - margin, y);
                y += 10;
                
                // Informations de l'employé
                checkPageBreak(60);
                doc.setFontSize(14);
                doc.setFont(undefined, 'bold');
                doc.text('INFORMATIONS DU SALARIÉ', margin, y);
                
                y += 10;
                doc.setFont(undefined, 'normal');
                doc.setFontSize(11);
                const donnees = resultatCalcul.donnees;
                const infos = [
                    `Nom complet : ${donnees.nomComplet}`,
                    `Type de contrat : ${donnees.typeContrat}`,
                    `Date d'embauche : ${donnees.dateEmbauche.toLocaleDateString('fr-FR')}`,
                    `Date de calcul : ${donnees.dateActuelle.toLocaleDateString('fr-FR')}`,
                    `Âge : ${donnees.age} ans`,
                    `Type de congé : ${getTypeCongeLabel(donnees.typeConge)}`
                ];
                
                infos.forEach(info => {
                    checkPageBreak(8);
                    doc.text(info, margin, y);
                    y += 7;
                });
                
                y += 10;
                
                // Résultats du calcul
                const resultat = resultatCalcul.resultat;
                checkPageBreak(40);
                doc.setFont(undefined, 'bold');
                doc.setFontSize(14);
                doc.text('RÉSULTATS DU CALCUL', margin, y);
                
                y += 10;
                doc.setFont(undefined, 'normal');
                doc.setFontSize(11);
                
                if (resultat.type === 'annuel') {
                    // Tableau des résultats pour congé annuel
                    const tableData = [
                        ['Période de référence', `Du ${resultat.periode.debut} au ${resultat.periode.fin}`],
                        ['Mois acquis', `${resultat.calcul.moisAcquis} mois`],
                        ['Droits de base', `${resultat.calcul.joursBase} jours`],
                        ['Total des droits', `${resultat.calcul.totalDroits} jours`],
                        ['Congés déjà pris', `${resultat.calcul.congesPris} jours`],
                        ['SOLDE DISPONIBLE', `${resultat.calcul.soldeDisponible} jours`]
                    ];
                    
                    if (resultat.allocation.disponible > 0) {
                        tableData.push(['Allocation disponible', formatMontant(resultat.allocation.disponible)]);
                    }
                    
                    tableData.forEach(row => {
                        checkPageBreak(8);
                        doc.text(`${row[0]} :`, margin, y);
                        doc.text(row[1], margin + 80, y);
                        y += 7;
                    });
                } else {
                    // Autres types de congés
                    checkPageBreak(20);
                    doc.text(`Type : ${getTypeCongeLabel(donnees.typeConge)}`, margin, y);
                    y += 7;
                    doc.text(`Droits accordés : ${getDroitsDisplay()}`, margin, y);
                    y += 7;
                    
                    if (resultat.allocation && resultat.allocation.totale > 0) {
                        doc.text(`Allocation : ${formatMontant(resultat.allocation.totale)}`, margin, y);
                        y += 7;
                    }
                }
                
                y += 15;
                
                // Références légales
                checkPageBreak(50);
                doc.setFont(undefined, 'bold');
                doc.setFontSize(14);
                doc.text('RÉFÉRENCES LÉGALES', margin, y);
                
                y += 10;
                doc.setFont(undefined, 'normal');
                doc.setFontSize(10);
                doc.text('Code du Travail gabonais 2021 - Loi n°022/2021 du 19 novembre 2021', margin, y);
                y += 6;
                
                const references = [
                    'Article 222 : Congés annuels - 2 jours ouvrables par mois de service effectif',
                    'Article 224 : Acquisition des droits après 12 mois de service continu',
                    'Article 225 : Allocation de congé égale à la rémunération habituelle'
                ];
                
                if (donnees.typeConge === 'maternite') {
                    references.push('Article 208 : Congé de maternité - 14 semaines');
                }
                if (donnees.typeConge === 'paternite') {
                    references.push('Article 54 : Congé de paternité et suspensions de contrat');
                }
                if (donnees.typeConge === 'maladie') {
                    references.push('Article 55 : Maintien rémunération congé maladie');
                }
                
                references.forEach(ref => {
                    checkPageBreak(6);
                    doc.text(`• ${ref}`, margin + 5, y);
                    y += 5;
                });
                
                y += 10;
                
                // Avertissement légal
                checkPageBreak(30);
                doc.setFont(undefined, 'bold');
                doc.setFontSize(11);
                doc.text('AVERTISSEMENT LÉGAL', margin, y);
                y += 8;
                
                doc.setFont(undefined, 'normal');
                doc.setFontSize(9);
                const disclaimer = 'Ce document est informatif et ne constitue pas un acte juridique officiel. En cas de litige, seules les dispositions du Code du Travail gabonais font foi. Consultez votre convention collective et/ou l\'Inspection du Travail pour des précisions.';
                const lines = doc.splitTextToSize(disclaimer, pageWidth - 2 * margin);
                doc.text(lines, margin, y);
                y += lines.length * 4;
                
                // Pied de page
                y = pageHeight - 30;
                doc.setFontSize(8);
                doc.text(`Document généré le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}`, margin, y);
                y += 4;
                doc.text('Simulateur BFEV V.2.0 - Conforme au Code du Travail gabonais 2021', margin, y);
                
                // Téléchargement
                const fileName = `simulation_conges_${donnees.nomComplet.replace(/\s/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
                doc.save(fileName);
                
                showAlert('Document PDF généré avec succès !', 'success');
                
            } catch (error) {
                console.error('Erreur lors de la génération du PDF:', error);
                showAlert('Erreur lors de la génération du PDF. Veuillez réessayer.', 'error');
            }
        }

        function reinitialiserFormulaire() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les champs ?')) {
                // Réinitialisation des champs
                document.querySelectorAll('input, select').forEach(field => {
                    if (field.type === 'number') {
                        field.value = field.id === 'enfantsCharge' || field.id === 'congesPris' || field.id === 'absencesInjustifiees' ? '0' : '';
                    } else if (field.type === 'radio') {
                        field.checked = field.value === 'annuel';
                    } else if (field.type === 'date') {
                        // Garder les dates par défaut
                    } else {
                        field.value = '';
                    }
                    field.classList.remove('form-field-error');
                });
                
                // Réinitialisation des messages d'erreur
                document.querySelectorAll('.error-message').forEach(error => {
                    error.textContent = '';
                    error.classList.add('hidden');
                });
                
                // Réinitialisation des résultats
                document.getElementById('resumeContenu').innerHTML = `
                    <div class="text-center py-8 text-gray-600">
                        <i class="fas fa-info-circle text-4xl text-gray-300 mb-4"></i>
                        <p class="text-lg font-medium mb-2">Aucun calcul effectué</p>
                        <p class="text-sm">Remplissez le formulaire et cliquez sur "Calculer mes congés" pour voir vos droits.</p>
                    </div>
                `;
                
                document.getElementById('tableauContenu').innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-table text-4xl text-gray-300 mb-4"></i>
                        <p>Les détails apparaîtront après le calcul</p>
                    </div>
                `;
                
                document.getElementById('avanceContenu').innerHTML = `
                    <div class="text-center py-8 text-gray-600">
                        <i class="fas fa-money-bill-wave text-4xl text-gray-300 mb-4"></i>
                        <p class="text-lg font-medium mb-2">Aucun calcul d'avance effectué</p>
                        <p class="text-sm">Remplissez d'abord vos informations dans l'onglet "Droits aux Congés".</p>
                    </div>
                `;
                
                // Désactivation des boutons
                document.getElementById('pdfBtn').disabled = true;
                document.getElementById('printBtn').disabled = true;
                
                // Réinitialisation des variables
                resultatCalcul = {};
                validationErrors = {};
                
                // Retour à l'onglet principal
                switchTab('conges');
                
                // Réinitialisation des dates
                initializeDates();
                
                // Mise à jour de la progression
                updateProgress();
                
                showAlert('Formulaire réinitialisé avec succès.', 'success');
            }
        }
    </script>
</body>
</html> 