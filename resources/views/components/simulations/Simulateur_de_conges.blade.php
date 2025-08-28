<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Congés - Gabon | Code du Travail 2021</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg { 
            background: linear-gradient(135deg, #05436b 0%, #122c3d 100%); 
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
            background: linear-gradient(135deg, #05436b 0%, #05436b 100%);
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
            background: linear-gradient(90deg, #05436b, #122c3d);
            border-radius: 3px;
            transition: width 0.8s ease-out;
        }
        
        @media print {
            body { print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-break { page-break-before: always; }
        }
        
        @media (max-width: 768px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- En-tête -->
    <header class="gradient-bg text-white py-6 no-print">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between flex-wrap">
                <div class="mb-2 md:mb-0">
                    <h1 class="text-2xl lg:text-3xl font-bold flex items-center">
                        <i class="fas fa-calendar-check mr-3" aria-hidden="true"></i>
                        Simulateur de Congés - Gabon
                    </h1>
                    <p class="text-blue-100 mt-2">Conforme au Code du Travail gabonais 2021</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">République Gabonaise</p>
                    <p class="text-xs text-blue-200">Loi n°022/2021 du 19 novembre 2021</p>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Barre de progression -->
        <div class="mb-8 no-print">
            <div class="progress-bar">
                <div id="progressBar" class="progress-fill" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">Remplissez le formulaire pour commencer</p>
        </div>

        <!-- Messages d'alerte -->
        <div id="alertContainer" class="mb-6 no-print"></div>
        
        <!-- Formulaire principal -->
        <div class="grid lg:grid-cols-2 gap-8">
            
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
                        </div>
                        
                        <div>
                            <label for="anneeReference" class="block text-sm font-medium text-gray-700 mb-2">
                                Année de référence pour le calcul <span class="text-red-500">*</span>
                            </label>
                            <select id="anneeReference" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required
                                    aria-describedby="anneeReference-help">
                                <!-- Options générées dynamiquement -->
                            </select>
                            <div id="anneeReference-help" class="text-xs text-gray-500 mt-1">
                                Année civile pour laquelle calculer les droits aux congés
                            </div>
                        </div>
                        
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                                Âge (années) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="age" 
                                   min="16" 
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
                                    +1 jour de congé par enfant pour les mères (Art. 223)
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
                                <div class="text-xs text-gray-500">Éligible après 12 mois de service</div>
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
                                <div class="text-sm text-gray-600">Jusqu'à 10 jours par an pour événements spécifiques (Art. 223)</div>
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
                                <div class="text-sm text-gray-600">Jusqu'à 6 mois rémunérés avec certificat médical (Art. 54)</div>
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
                    
                    <button id="resetBtn" 
                            onclick="reinitialiserFormulaire()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-300 w-full md:w-auto">
                        <i class="fas fa-undo mr-2" aria-hidden="true"></i>
                        Réinitialiser
                    </button>
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
                            Loi n°022/2021 du 19 novembre 2021 portant Code du Travail en République Gabonaise
                        </div>
                        <div class="space-y-2">
                            <p><strong>Art. 222 :</strong> Congés annuels - 2 jours ouvrables par mois de service effectif</p>
                            <p><strong>Art. 223 :</strong> Majorations pour mères de famille et ancienneté</p>
                            <p><strong>Art. 224 :</strong> Acquisition des droits après 12 mois de service continu</p>
                            <p><strong>Art. 225 :</strong> Allocation de congé égale à la rémunération habituelle</p>
                            <p><strong>Art. 208 :</strong> Congé de maternité - 14 semaines avec extensions possibles</p>
                            <p><strong>Art. 54 :</strong> Congés spéciaux (paternité, maladie, événements familiaux)</p>
                        </div>
                    </div>
                </div>

                <!-- Bouton PDF -->
                <div class="text-center no-print">
                    <button id="pdfBtn" 
                            onclick="genererPDF()" 
                            class="btn-secondary text-white px-8 py-3 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>
                        Télécharger l'attestation PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12 no-print">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-300">© 2024 - Simulateur de Congés Gabon | Conforme au Code du Travail gabonais 2021</p>
            <p class="text-sm text-gray-400 mt-2">
                <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                Cet outil est fourni à titre informatif. Consultez le Code du Travail officiel pour les dispositions complètes.
            </p>
            <p class="text-xs text-gray-500 mt-2">
                Version 2.1 - Dernière mise à jour : Janvier 2025
            </p>
        </div>
    </footer>

    <script>
        // Configuration globale
        const CONFIG = {
            MOIS_PAR_ANNEE: 12,
            JOURS_PAR_MOIS: 30.416667, // Plus précis que 30.44
            JOURS_BASE_PAR_MOIS: 2,
            JOURS_BASE_PAR_MOIS_MINEUR: 2.5,
            DUREE_MATERNITE_SEMAINES: 14,
            DUREE_PATERNITE_JOURS: 3,
            MOIS_MALADIE_MAX: 6,
            JOURS_EVENEMENTS_MAX: 10
        };

        // Variables globales
        let resultatCalcul = {};
        let validationErrors = {};

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
            
            // Initialiser les années de référence
            initializeAnneeReference();
        }

        function initializeAnneeReference() {
            const anneeSelect = document.getElementById('anneeReference');
            const currentYear = new Date().getFullYear();
            
            // Vider les options existantes
            anneeSelect.innerHTML = '';
            
            // Générer les options pour les 5 dernières années et les 2 prochaines
            for (let year = currentYear - 5; year <= currentYear + 2; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                anneeSelect.appendChild(option);
            }
        }

        function setupEventListeners() {
            // Validation en temps réel
            const requiredFields = ['nomComplet', 'typeContrat', 'dateEmbauche', 'dateActuelle', 'age', 'sexe', 'anneeReference'];
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

            // Écouter les changements de type de congé
            document.querySelectorAll('input[name="typeConge"]').forEach(radio => {
                radio.addEventListener('change', updateProgress);
            });

            // Écouter les changements d'année de référence pour mise à jour automatique
            document.getElementById('anneeReference').addEventListener('change', function() {
                if (resultatCalcul.donnees) {
                    showAlert('L\'année de référence a été modifiée. Veuillez recalculer vos droits.', 'info');
                }
            });
        }

        function updateProgress() {
            const requiredFields = ['nomComplet', 'typeContrat', 'dateEmbauche', 'dateActuelle', 'age', 'sexe', 'anneeReference'];
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

            // Validation spécifique par champ
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
                            errorMessage = 'La date d\'embauche doit être antérieure à la date actuelle';
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
                    } else {
                        const dateActuelle = new Date(field.value);
                        const today = new Date();
                        
                        if (dateActuelle > today) {
                            isValid = false;
                            errorMessage = 'La date de calcul ne peut pas être dans le futur';
                        }
                    }
                    break;

                case 'age':
                    const age = parseInt(field.value);
                    if (!age || age < 16 || age > 65) {
                        isValid = false;
                        errorMessage = 'L\'âge doit être compris entre 16 et 65 ans';
                    }
                    break;

                case 'sexe':
                    if (!field.value) {
                        isValid = false;
                        errorMessage = 'Veuillez indiquer votre sexe';
                    }
                    break;

                case 'anneeReference':
                    const annee = parseInt(field.value);
                    const currentYear = new Date().getFullYear();
                    if (!annee || annee < 1980 || annee > currentYear + 2) {
                        isValid = false;
                        errorMessage = 'Année de référence invalide';
                    } else {
                        // Vérifier que l'année de référence est cohérente avec la date d'embauche
                        const dateEmbauche = new Date(document.getElementById('dateEmbauche').value);
                        if (dateEmbauche && annee < dateEmbauche.getFullYear()) {
                            isValid = false;
                            errorMessage = 'L\'année de référence doit être postérieure ou égale à l\'année d\'embauche';
                        }
                    }
                    break;
            }

            // Affichage des erreurs
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
            const requiredFields = ['nomComplet', 'typeContrat', 'dateEmbauche', 'dateActuelle', 'age', 'sexe', 'anneeReference'];
            let allValid = true;

            requiredFields.forEach(fieldId => {
                if (!validateField(fieldId)) {
                    allValid = false;
                }
            });

            return allValid;
        }

        // FIX: Fonction utilitaire pour calculer la période de congés basée sur la date anniversaire
        function derivePeriodeConges(dateEmbauche, annee) {
            try {
                // Validation des paramètres
                if (!dateEmbauche || !annee || isNaN(annee)) {
                    throw new Error('Paramètres invalides pour le calcul de la période de congés');
                }

                const dateEmb = new Date(dateEmbauche);
                const jourEmb = dateEmb.getDate();
                const moisEmb = dateEmb.getMonth(); // 0-11

                // FIX: Calcul des anniversaires avec gestion du 29 février
                let annivCourant = new Date(annee, moisEmb, jourEmb);
                let annivSuivant = new Date(annee + 1, moisEmb, jourEmb);

                // FIX: Gestion spéciale du 29 février pour les années non bissextiles
                if (moisEmb === 1 && jourEmb === 29) { // Février 29
                    if (!estAnneeBissextile(annee)) {
                        annivCourant = new Date(annee, 1, 28); // 28 février
                    }
                    if (!estAnneeBissextile(annee + 1)) {
                        annivSuivant = new Date(annee + 1, 1, 28); // 28 février
                    }
                }

                // Si l'anniversaire n'a pas encore eu lieu cette année, prendre l'anniversaire de l'année précédente
                if (annivCourant > new Date()) {
                    annivCourant = new Date(annee - 1, moisEmb, jourEmb);
                    annivSuivant = new Date(annee, moisEmb, jourEmb);
                    
                    // Gestion du 29 février pour l'année précédente
                    if (moisEmb === 1 && jourEmb === 29) {
                        if (!estAnneeBissextile(annee - 1)) {
                            annivCourant = new Date(annee - 1, 1, 28);
                        }
                        if (!estAnneeBissextile(annee)) {
                            annivSuivant = new Date(annee, 1, 28);
                        }
                    }
                }

                const debutPeriode = annivCourant;
                const finPeriode = new Date(annivSuivant.getTime() - 24 * 60 * 60 * 1000); // -1 jour

                // FIX: Calcul des mois acquis selon la règle d'ancienneté
                const ancienneteAuDebut = calculerAncienneteMois(dateEmb, debutPeriode);
                let moisAcquis;
                
                if (ancienneteAuDebut >= 12) {
                    moisAcquis = 12; // 12 mois complets
                } else {
                    // Nombre de mois entiers entre embauche et anniversaire
                    moisAcquis = Math.floor(ancienneteAuDebut);
                }

                console.log('FIX: Période de congés calculée:', {
                    debutPeriode: debutPeriode.toLocaleDateString('fr-FR'),
                    finPeriode: finPeriode.toLocaleDateString('fr-FR'),
                    moisAcquis: moisAcquis,
                    ancienneteAuDebut: ancienneteAuDebut
                });

                return {
                    debutPeriode: debutPeriode,
                    finPeriode: finPeriode,
                    moisAcquis: moisAcquis,
                    ancienneteAuDebut: ancienneteAuDebut
                };

            } catch (error) {
                console.error('Erreur dans derivePeriodeConges:', error);
                throw new Error('Impossible de calculer la période de congés: ' + error.message);
            }
        }

        // FIX: Fonction utilitaire pour vérifier si une année est bissextile
        function estAnneeBissextile(annee) {
            return (annee % 4 === 0 && annee % 100 !== 0) || (annee % 400 === 0);
        }

        // FIX: Fonction pour calculer l'ancienneté en mois entre deux dates
        function calculerAncienneteMois(dateDebut, dateFin) {
            const diffTime = dateFin.getTime() - dateDebut.getTime();
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            return diffDays / CONFIG.JOURS_PAR_MOIS;
        }

        function calculerConges() {
            try {
                // Validation complète
                if (!validateAllFields()) {
                    showAlert('Veuillez corriger les erreurs dans le formulaire avant de continuer.', 'error');
                    return;
                }

                // Vérification spécifique de l'année de référence
                const anneeRef = document.getElementById('anneeReference');
                if (!anneeRef || !anneeRef.value) {
                    showAlert('Erreur : Année de référence non sélectionnée.', 'error');
                    return;
                }

                console.log('Début du calcul...'); // Debug

                // Affichage du loading
                setLoadingState(true);

                // Simulation d'un délai pour l'UX
                setTimeout(() => {
                    try {
                        performCalculation();
                        setLoadingState(false);
                        console.log('Calcul terminé avec succès'); // Debug
                    } catch (error) {
                        setLoadingState(false);
                        console.error('Erreur lors du calcul:', error);
                        console.error('Stack trace:', error.stack); // Debug détaillé
                        showAlert(`Erreur lors du calcul : ${error.message}. Consultez la console pour plus de détails.`, 'error');
                    }
                }, 500);

            } catch (error) {
                setLoadingState(false);
                console.error('Erreur lors de l\'initialisation du calcul:', error);
                console.error('Stack trace:', error.stack); // Debug détaillé
                showAlert(`Erreur technique : ${error.message}. Veuillez actualiser la page et réessayer.`, 'error');
            }
        }

        function performCalculation() {
            // Récupération des données
            const donnees = collectFormData();

            // FIX: Validation supplémentaire des données critiques
            if (!donnees.dateEmbauche || !donnees.anneeReference) {
                throw new Error('Données manquantes pour le calcul');
            }

            // Calcul de l'ancienneté précise (globale)
            const ancienneteGlobale = calculerAnciennetePrecise(donnees.dateEmbauche, donnees.dateActuelle);
            
            // Préparation de l'objet résultat
            resultatCalcul = {
                donnees: donnees,
                anciennete: ancienneteGlobale,
                dateCalcul: new Date()
            };

            // FIX: Toujours initialiser periodeReference avant les calculs spécifiques
            if (donnees.typeConge === 'annuel') {
                try {
                    resultatCalcul.periodeReference = derivePeriodeConges(donnees.dateEmbauche, donnees.anneeReference);
                } catch (error) {
                    console.error('Erreur lors du calcul de la période de référence:', error);
                    throw new Error('Impossible de calculer la période de référence: ' + error.message);
                }
            }

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

            // Affichage des résultats
            afficherResultats();
            
            // Activation du bouton PDF
            document.getElementById('pdfBtn').disabled = false;
            
            // Message de succès
            showAlert('Calcul effectué avec succès selon le Code du Travail gabonais.', 'success');
        }

        function collectFormData() {
            // FIX: Validation renforcée des champs obligatoires
            const nomComplet = document.getElementById('nomComplet').value.trim();
            const dateEmbaucheStr = document.getElementById('dateEmbauche').value;
            const dateActuelleStr = document.getElementById('dateActuelle').value;
            const anneeReferenceStr = document.getElementById('anneeReference').value;

            if (!nomComplet) throw new Error('Nom complet manquant');
            if (!dateEmbaucheStr) throw new Error('Date d\'embauche manquante');
            if (!dateActuelleStr) throw new Error('Date actuelle manquante');
            if (!anneeReferenceStr) throw new Error('Année de référence manquante');

            return {
                nomComplet: nomComplet,
                typeContrat: document.getElementById('typeContrat').value,
                dateEmbauche: new Date(dateEmbaucheStr),
                dateActuelle: new Date(dateActuelleStr),
                anneeReference: parseInt(anneeReferenceStr),
                age: parseInt(document.getElementById('age').value),
                salaireMoyen: parseInt(document.getElementById('salaireMoyen').value) || 0,
                sexe: document.getElementById('sexe').value,
                enfantsCharge: parseInt(document.getElementById('enfantsCharge').value) || 0,
                typeConge: document.querySelector('input[name="typeConge"]:checked').value,
                congesPris: parseInt(document.getElementById('congesPris').value) || 0,
                absencesInjustifiees: parseInt(document.getElementById('absencesInjustifiees').value) || 0
            };
        }

        function calculerAnciennetePrecise(dateDebut, dateFin) {
            const diffTime = Math.abs(dateFin - dateDebut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const diffMonths = Math.floor(diffDays / CONFIG.JOURS_PAR_MOIS);
            const diffYears = Math.floor(diffMonths / CONFIG.MOIS_PAR_ANNEE);
            
            return {
                jours: diffDays,
                mois: diffMonths,
                annees: diffYears,
                moisResiduels: diffMonths % CONFIG.MOIS_PAR_ANNEE,
                estEligibleCongeAnnuel: diffMonths >= CONFIG.MOIS_PAR_ANNEE
            };
        }

        function calculerCongeAnnuel() {
            const donnees = resultatCalcul.donnees;
            const anciennete = resultatCalcul.anciennete;

            // FIX: Vérifier que periodeReference est bien définie
            if (!resultatCalcul.periodeReference) {
                throw new Error('Période de référence non définie - impossible de calculer les congés');
            }

            resultatCalcul.typeCalcule = 'Congé annuel ordinaire';
            resultatCalcul.articlesRef = ['Art. 222', 'Art. 223', 'Art. 224', 'Art. 225'];

            // FIX: Utiliser les mois acquis de la nouvelle fonction
            const moisAcquis = resultatCalcul.periodeReference.moisAcquis;

            // Vérification de l'éligibilité basée sur la période de référence
            if (moisAcquis < CONFIG.MOIS_PAR_ANNEE) {
                resultatCalcul.eligible = false;
                resultatCalcul.raison = `Ancienneté insuffisante pour la période de référence ${donnees.anneeReference}. Il faut au moins 12 mois d'ancienneté à la date anniversaire (Art. 224)`;
                
                // Calcul de l'indemnité compensatrice potentielle
                const tauxJours = donnees.age < 18 ? CONFIG.JOURS_BASE_PAR_MOIS_MINEUR : CONFIG.JOURS_BASE_PAR_MOIS;
                resultatCalcul.joursAcquisPartiels = Math.floor(moisAcquis * tauxJours);
                resultatCalcul.indemniteCompensatrice = donnees.salaireMoyen > 0 ? 
                    (donnees.salaireMoyen * resultatCalcul.joursAcquisPartiels / 30) : 0;
                
                return;
            }

            resultatCalcul.eligible = true;

            // FIX: Calcul des congés acquis progressivement selon le temps écoulé dans la période
            const moisEcoulesDansPeriode = calculerMoisEcoulesDansPeriode(
                resultatCalcul.periodeReference.debutPeriode, 
                donnees.dateActuelle
            );

            // Calcul des jours de base selon le temps écoulé (pas le total annuel)
            const tauxJours = donnees.age < 18 ? CONFIG.JOURS_BASE_PAR_MOIS_MINEUR : CONFIG.JOURS_BASE_PAR_MOIS;
            const joursBaseAcquis = Math.floor(moisEcoulesDansPeriode * tauxJours);
            const joursBaseAnnuelMax = 24; // Maximum annuel

            // Jours supplémentaires pour mères de famille (proportionnels)
            const joursEnfantsAnnuelMax = (donnees.sexe === 'F' && donnees.enfantsCharge > 0) ? donnees.enfantsCharge : 0;
            const joursEnfantsAcquis = Math.floor((moisEcoulesDansPeriode / 12) * joursEnfantsAnnuelMax);

            // Majorations d'ancienneté (proportionnelles)
            let majorationAncienneteAnnuelMax = 0;
            if (anciennete.annees >= 5) {
                majorationAncienneteAnnuelMax = Math.min(Math.floor((anciennete.annees - 4) / 5) * 2, 6); // Max 6 jours
            }
            const majorationAncienneteAcquise = Math.floor((moisEcoulesDansPeriode / 12) * majorationAncienneteAnnuelMax);

            // Réduction pour absences injustifiées
            const reductionAbsences = Math.min(donnees.absencesInjustifiees, Math.floor(joursBaseAcquis * 0.1)); // Max 10% de réduction

            // Calculs finaux - Congés acquis au prorata du temps écoulé
            resultatCalcul.joursBase = joursBaseAcquis;
            resultatCalcul.joursEnfants = joursEnfantsAcquis;
            resultatCalcul.majorationAnciennete = majorationAncienneteAcquise;
            resultatCalcul.reductionAbsences = reductionAbsences;
            resultatCalcul.joursAcquis = Math.max(0, joursBaseAcquis + joursEnfantsAcquis + majorationAncienneteAcquise - reductionAbsences);
            
            // Calculs pour affichage comparatif
            resultatCalcul.joursBaseAnnuelMax = joursBaseAnnuelMax;
            resultatCalcul.joursEnfantsAnnuelMax = joursEnfantsAnnuelMax;
            resultatCalcul.majorationAncienneteAnnuelMax = majorationAncienneteAnnuelMax;
            resultatCalcul.joursAcquisAnnuelMax = joursBaseAnnuelMax + joursEnfantsAnnuelMax + majorationAncienneteAnnuelMax;
            resultatCalcul.moisEcoulesDansPeriode = moisEcoulesDansPeriode;
            
            resultatCalcul.joursRestants = Math.max(0, resultatCalcul.joursAcquis - donnees.congesPris);
            
            // Calcul des allocations (Art. 225)
            if (donnees.salaireMoyen > 0) {
                resultatCalcul.allocationTotale = Math.round(donnees.salaireMoyen * resultatCalcul.joursAcquis / 30);
                resultatCalcul.allocationRestante = Math.round(donnees.salaireMoyen * resultatCalcul.joursRestants / 30);
                resultatCalcul.allocationAnnuelleMax = Math.round(donnees.salaireMoyen * resultatCalcul.joursAcquisAnnuelMax / 30);
            }

            // Pourcentage utilisé
            resultatCalcul.pourcentageUtilise = resultatCalcul.joursAcquis > 0 ? 
                Math.round((donnees.congesPris / resultatCalcul.joursAcquis) * 100) : 0;

            // Pourcentage de l'année écoulée
            resultatCalcul.pourcentageAnneeEcoulee = Math.round((moisEcoulesDansPeriode / 12) * 100);

            // FIX: Ajouter des avertissements si nécessaire
            resultatCalcul.avertissements = [];
            if (resultatCalcul.periodeReference.ancienneteAuDebut < 12) {
                resultatCalcul.avertissements.push('Première année de congés - droits proportionnels à l\'ancienneté');
            }
            if (donnees.absencesInjustifiees > 0) {
                resultatCalcul.avertissements.push(`Réduction de ${reductionAbsences} jour(s) appliquée pour absences injustifiées`);
            }
            if (moisEcoulesDansPeriode < 12) {
                resultatCalcul.avertissements.push(`Congés acquis au prorata : ${Math.round(moisEcoulesDansPeriode * 10) / 10} mois écoulés sur 12 dans la période anniversaire`);
            }
        }

        // FIX: Fonction pour calculer les mois écoulés dans la période anniversaire en cours
        function calculerMoisEcoulesDansPeriode(debutPeriode, dateActuelle) {
            // Si la date actuelle est avant le début de période, retourner 0
            if (dateActuelle < debutPeriode) {
                return 0;
            }
            
            // Calculer la différence en mois entre le début de période et la date actuelle
            const diffTime = dateActuelle.getTime() - debutPeriode.getTime();
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            const moisEcoules = diffDays / CONFIG.JOURS_PAR_MOIS;
            
            // Limiter à 12 mois maximum (une période anniversaire complète)
            return Math.min(moisEcoules, 12);
        }

        function calculerCongeMaternite() {
            const donnees = resultatCalcul.donnees;
            
            resultatCalcul.typeCalcule = 'Congé de maternité';
            resultatCalcul.articlesRef = ['Art. 208'];

            if (donnees.sexe !== 'F') {
                resultatCalcul.eligible = false;
                resultatCalcul.raison = 'Le congé de maternité est exclusivement réservé aux femmes salariées (Art. 208)';
                return;
            }

            resultatCalcul.eligible = true;

            // Durées de base (Art. 208)
            resultatCalcul.semainesBase = CONFIG.DUREE_MATERNITE_SEMAINES;
            resultatCalcul.joursBase = CONFIG.DUREE_MATERNITE_SEMAINES * 7;
            resultatCalcul.joursPrenatal = 6 * 7; // 6 semaines avant
            resultatCalcul.joursPostnatal = 8 * 7; // 8 semaines après

            // Extensions possibles
            resultatCalcul.extensions = {
                naissanceMultiple: 21, // 3 semaines
                complicationsMedicales: 21, // 3 semaines
                accouchementPremature: 0 // Calculé selon les circonstances
            };

            resultatCalcul.joursMaximumPossible = resultatCalcul.joursBase + 
                resultatCalcul.extensions.naissanceMultiple + 
                resultatCalcul.extensions.complicationsMedicales;

            // Rémunération (maintien intégral)
            resultatCalcul.maintienSalaire = '100% du salaire habituel';
            resultatCalcul.prisEnChargeParCNSS = true;

            if (donnees.salaireMoyen > 0) {
                resultatCalcul.allocationTotale = Math.round(donnees.salaireMoyen * resultatCalcul.joursBase / 30);
            }
        }

        function calculerCongePaternite() {
            const donnees = resultatCalcul.donnees;
            
            resultatCalcul.typeCalcule = 'Congé de paternité';
            resultatCalcul.articlesRef = ['Art. 54'];

            if (donnees.sexe !== 'M') {
                resultatCalcul.eligible = false;
                resultatCalcul.raison = 'Le congé de paternité est exclusivement réservé aux pères (Art. 54)';
                return;
            }

            resultatCalcul.eligible = true;

            resultatCalcul.joursAcquis = CONFIG.DUREE_PATERNITE_JOURS;
            resultatCalcul.conditions = [
                'Justificatif de certificat de naissance obligatoire',
                'Informer l\'employeur 1 mois avant la date présumée d\'accouchement',
                'Congé à prendre consécutivement dans les jours suivant la naissance'
            ];
            
            resultatCalcul.maintienSalaire = '100% du salaire maintenu';
            
            if (donnees.salaireMoyen > 0) {
                resultatCalcul.allocationTotale = Math.round(donnees.salaireMoyen * CONFIG.DUREE_PATERNITE_JOURS / 30);
            }
        }

        function calculerCongeEvenements() {
            resultatCalcul.typeCalcule = 'Permissions pour événements familiaux';
            resultatCalcul.articlesRef = ['Art. 223'];
            resultatCalcul.eligible = true;

            resultatCalcul.joursMaximumAnnuel = CONFIG.JOURS_EVENEMENTS_MAX;
            resultatCalcul.joursRestants = Math.max(0, CONFIG.JOURS_EVENEMENTS_MAX - resultatCalcul.donnees.congesPris);
            
            resultatCalcul.evenementsCouverts = [
                'Mariage du salarié',
                'Naissance d\'un enfant',
                'Décès d\'un conjoint, parent, enfant',
                'Mariage d\'un enfant',
                'Autres événements familiaux graves'
            ];
            
            resultatCalcul.caracteristiques = [
                'Non déductibles du congé annuel dans la limite de 10 jours par an',
                'Justificatifs obligatoires (actes d\'état civil, certificats médicaux)',
                'Délai de prévenance recommandé sauf urgence'
            ];
        }

        function calculerCongeMaladie() {
            resultatCalcul.typeCalcule = 'Congé maladie';
            resultatCalcul.articlesRef = ['Art. 54'];
            resultatCalcul.eligible = true;

            resultatCalcul.dureeMaximumMois = CONFIG.MOIS_MALADIE_MAX;
            resultatCalcul.joursMaximum = CONFIG.MOIS_MALADIE_MAX * 30;
            
            resultatCalcul.conditions = [
                'Certificat médical obligatoire dès le 1er jour',
                'Contre-expertise possible après 3 certificats successifs en 6 mois',
                'Maintien intégral du salaire pendant la période couverte'
            ];
            
            resultatCalcul.dispositionsSpeciales = {
                maladieConjointEnfant: 15, // 15 jours ouvrables par an
                maladieGrave: 'Extensions possibles sur avis médical spécialisé',
                accidentTravail: 'Prise en charge spécifique par la CNSS'
            };
            
            resultatCalcul.maintienSalaire = '100% du salaire maintenu';
        }

        function afficherResultats() {
            afficherResume();
            afficherTableauDetaille();
        }

        function afficherResume() {
            const resumeDiv = document.getElementById('resumeContenu');
            const donnees = resultatCalcul.donnees;

            let resumeHtml = `
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div>
                            <h3 class="font-semibold text-blue-800">${donnees.nomComplet}</h3>
                            <p class="text-sm text-blue-600">${donnees.typeContrat} • ${resultatCalcul.typeCalcule}</p>
                            <p class="text-xs text-blue-500">Année de référence : ${donnees.anneeReference}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-blue-600">Ancienneté totale</p>
                            <p class="font-semibold text-blue-800">${resultatCalcul.anciennete.annees} an(s) ${resultatCalcul.anciennete.moisResiduels} mois</p>
                            ${resultatCalcul.periodeReference ? 
                                `<p class="text-xs text-blue-500">${Math.round(resultatCalcul.periodeReference.moisAcquis * 10) / 10} mois acquis en ${donnees.anneeReference}</p>` 
                                : ''
                            }
                        </div>
                    </div>
            `;

            if (!resultatCalcul.eligible) {
                resumeHtml += `
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-1" aria-hidden="true"></i>
                            <div>
                                <h4 class="font-semibold text-red-800">Non éligible pour l'année ${donnees.anneeReference}</h4>
                                <p class="text-red-700 mt-1">${resultatCalcul.raison}</p>
                `;
                
                if (resultatCalcul.joursAcquisPartiels) {
                    resumeHtml += `
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800">
                                <strong>Indemnité compensatrice potentielle pour ${donnees.anneeReference} :</strong> 
                                ${resultatCalcul.joursAcquisPartiels} jours
                                ${resultatCalcul.indemniteCompensatrice > 0 ? ` (${formatMontant(resultatCalcul.indemniteCompensatrice)} FCFA)` : ''}
                            </p>
                        </div>
                    `;
                }
                resumeHtml += '</div></div>';
            } else {
                resumeHtml += generateEligibleSummary();
            }

            resumeHtml += '</div>';
            resumeDiv.innerHTML = resumeHtml;
        }

        function generateEligibleSummary() {
            const donnees = resultatCalcul.donnees;
            let summaryHtml = '';

            switch (donnees.typeConge) {
                case 'annuel':
                    summaryHtml = `
                        <!-- Progression des congés acquis -->
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 mb-4">
                            <h4 class="font-semibold text-blue-800 mb-2">Progression dans la période anniversaire</h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-blue-700">Temps écoulé :</span>
                                    <span class="font-medium">${Math.round(resultatCalcul.moisEcoulesDansPeriode * 10) / 10} mois sur 12</span>
                                </div>
                                <div>
                                    <span class="text-blue-700">Progression :</span>
                                    <span class="font-medium">${resultatCalcul.pourcentageAnneeEcoulee}% de l'année</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full transition-all duration-500" style="width: ${resultatCalcul.pourcentageAnneeEcoulee}%"></div>
                            </div>
                        </div>

                        <!-- Congés acquis vs Maximum annuel -->
                        <div class="grid md:grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-2xl font-bold text-green-600">${resultatCalcul.joursAcquis}</div>
                                <div class="text-sm text-green-700">Jours acquis actuellement</div>
                                <div class="text-xs text-green-600 mt-1">${Math.round(resultatCalcul.moisEcoulesDansPeriode * 10) / 10} mois × 2 jours</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                                <div class="text-2xl font-bold text-orange-600">${donnees.congesPris}</div>
                                <div class="text-sm text-orange-700">Jours pris</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="text-2xl font-bold text-blue-600">${resultatCalcul.joursRestants}</div>
                                <div class="text-sm text-blue-700">Jours restants</div>
                            </div>
                        </div>

                        <!-- Projection fin d'année -->
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Projection fin de période (${resultatCalcul.periodeReference.finPeriode.toLocaleDateString('fr-FR')})</h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-700">Maximum possible :</span>
                                    <span class="font-medium">${resultatCalcul.joursAcquisAnnuelMax} jours</span>
                                </div>
                                <div>
                                    <span class="text-gray-700">Congés restants à acquérir :</span>
                                    <span class="font-medium">${resultatCalcul.joursAcquisAnnuelMax - resultatCalcul.joursAcquis} jours</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations sur la période de référence -->
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Période de référence : année ${donnees.anneeReference}</h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-700">Période anniversaire :</span>
                                    <span class="font-medium">${resultatCalcul.periodeReference.debutPeriode.toLocaleDateString('fr-FR')} 
                                    au ${resultatCalcul.periodeReference.finPeriode.toLocaleDateString('fr-FR')}</span>
                                </div>
                                <div>
                                    <span class="text-gray-700">Date de calcul :</span>
                                    <span class="font-medium">${donnees.dateActuelle.toLocaleDateString('fr-FR')}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Barre de progression utilisation -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Utilisation des congés acquis</span>
                                <span>${resultatCalcul.pourcentageUtilise}%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${Math.min(resultatCalcul.pourcentageUtilise, 100)}%"></div>
                            </div>
                        </div>
                    `;
                    
                    // Avertissements spécifiques
                    if (resultatCalcul.avertissements && resultatCalcul.avertissements.length > 0) {
                        summaryHtml += `
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 mb-4">
                                <h4 class="font-semibold text-yellow-800 mb-2">Informations importantes :</h4>
                                <ul class="text-yellow-700 text-sm space-y-1">
                                    ${resultatCalcul.avertissements.map(avert => `<li>• ${avert}</li>`).join('')}
                                </ul>
                            </div>
                        `;
                    }
                    
                    if (resultatCalcul.allocationTotale > 0) {
                        summaryHtml += `
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <h4 class="font-semibold text-green-800 mb-2">Allocation de congé</h4>
                                <div class="grid md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-green-700">Actuellement acquise :</span>
                                        <span class="font-semibold">${formatMontant(resultatCalcul.allocationTotale)} FCFA</span>
                                    </div>
                                    <div>
                                        <span class="text-green-700">Disponible :</span>
                                        <span class="font-semibold">${formatMontant(resultatCalcul.allocationRestante)} FCFA</span>
                                    </div>
                                    <div>
                                        <span class="text-green-700">Maximum annuel :</span>
                                        <span class="font-semibold">${formatMontant(resultatCalcul.allocationAnnuelleMax)} FCFA</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    break;

                case 'maternite':
                    summaryHtml = `
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <div class="text-2xl font-bold text-pink-600">${resultatCalcul.semainesBase}</div>
                                <div class="text-sm text-pink-700">Semaines de base</div>
                            </div>
                            <div class="text-center p-4 bg-pink-50 rounded-lg border border-pink-200">
                                <div class="text-2xl font-bold text-pink-600">${resultatCalcul.joursBase}</div>
                                <div class="text-sm text-pink-700">Jours de base</div>
                            </div>
                        </div>
                        <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                            <h4 class="font-semibold text-pink-800 mb-2">Répartition standard</h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-pink-700">Prénatal :</span>
                                    <span class="font-semibold">${resultatCalcul.joursPrenatal} jours (6 semaines)</span>
                                </div>
                                <div>
                                    <span class="text-pink-700">Postnatal :</span>
                                    <span class="font-semibold">${resultatCalcul.joursPostnatal} jours (8 semaines)</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 mt-4">
                            <h4 class="font-semibold text-blue-800 mb-2">Extensions possibles</h4>
                            <div class="text-sm text-blue-700 space-y-1">
                                <div>• Naissances multiples : +${resultatCalcul.extensions.naissanceMultiple} jours</div>
                                <div>• Complications médicales : +${resultatCalcul.extensions.complicationsMedicales} jours</div>
                                <div>• Maximum théorique : ${resultatCalcul.joursMaximumPossible} jours</div>
                            </div>
                        </div>
                    `;
                    break;

                case 'paternite':
                    summaryHtml = `
                        <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200 mb-4">
                            <div class="text-3xl font-bold text-blue-600">${resultatCalcul.joursAcquis}</div>
                            <div class="text-sm text-blue-700">Jours consécutifs</div>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <h4 class="font-semibold text-yellow-800 mb-2">Conditions obligatoires</h4>
                            <ul class="text-yellow-700 text-sm space-y-1">
                                ${resultatCalcul.conditions.map(condition => `<li>• ${condition}</li>`).join('')}
                            </ul>
                        </div>
                    `;
                    break;

                case 'evenements':
                    summaryHtml = `
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="text-2xl font-bold text-purple-600">${resultatCalcul.joursMaximumAnnuel}</div>
                                <div class="text-sm text-purple-700">Jours maximum/an</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="text-2xl font-bold text-purple-600">${resultatCalcul.joursRestants}</div>
                                <div class="text-sm text-purple-700">Jours restants</div>
                            </div>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <h4 class="font-semibold text-purple-800 mb-2">Événements couverts</h4>
                            <ul class="text-purple-700 text-sm space-y-1">
                                ${resultatCalcul.evenementsCouverts.map(event => `<li>• ${event}</li>`).join('')}
                            </ul>
                        </div>
                    `;
                    break;

                case 'maladie':
                    summaryHtml = `
                        <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200 mb-4">
                            <div class="text-2xl font-bold text-red-600">${resultatCalcul.dureeMaximumMois}</div>
                            <div class="text-sm text-red-700">Mois maximum rémunérés</div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <h4 class="font-semibold text-red-800 mb-2">Conditions et dispositions</h4>
                            <ul class="text-red-700 text-sm space-y-1">
                                ${resultatCalcul.conditions.map(condition => `<li>• ${condition}</li>`).join('')}
                            </ul>
                        </div>
                        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200 mt-4">
                            <h4 class="font-semibold text-orange-800 mb-2">Dispositions spéciales</h4>
                            <div class="text-orange-700 text-sm space-y-1">
                                <div>• Maladie conjoint/enfant : ${resultatCalcul.dispositionsSpeciales.maladieConjointEnfant} jours ouvrables/an</div>
                                <div>• Maladie grave : ${resultatCalcul.dispositionsSpeciales.maladieGrave}</div>
                                <div>• Accident de travail : ${resultatCalcul.dispositionsSpeciales.accidentTravail}</div>
                            </div>
                        </div>
                    `;
                    break;
            }

            return summaryHtml;
        }

        function afficherTableauDetaille() {
            const tableauDiv = document.getElementById('tableauContenu');
            
            let tableauHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Élément</th>
                                <th class="border border-gray-300 px-4 py-3 text-right font-semibold">Valeur</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Référence légale</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            // Lignes communes
            tableauHtml += `
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">Type de congé</td>
                    <td class="border border-gray-300 px-4 py-2 text-right font-medium">${resultatCalcul.typeCalcule}</td>
                    <td class="border border-gray-300 px-4 py-2">${resultatCalcul.articlesRef.join(', ')}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">Année de référence</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">${resultatCalcul.donnees.anneeReference}</td>
                    <td class="border border-gray-300 px-4 py-2">Sélectionnée</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">Ancienneté totale</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">${resultatCalcul.anciennete.annees} an(s) ${resultatCalcul.anciennete.moisResiduels} mois</td>
                    <td class="border border-gray-300 px-4 py-2">Calculée</td>
                </tr>
            `;

            // FIX: Lignes spécifiques selon le type avec vérification de periodeReference
            if (resultatCalcul.donnees.typeConge === 'annuel' && resultatCalcul.eligible && resultatCalcul.periodeReference) {
                const rows = [
                    ['Mois acquis en ' + resultatCalcul.donnees.anneeReference, `${resultatCalcul.periodeReference.moisAcquis} mois`, 'Calculé selon date anniversaire'],
                    ['Période anniversaire', `${resultatCalcul.periodeReference.debutPeriode.toLocaleDateString('fr-FR')} au ${resultatCalcul.periodeReference.finPeriode.toLocaleDateString('fr-FR')}`, 'Calculé selon date anniversaire'],
                    ['Jours de base (2/mois)', resultatCalcul.joursBase, 'Art. 222'],
                ];

                if (resultatCalcul.joursEnfants > 0) {
                    rows.push(['Jours enfants à charge', `+${resultatCalcul.joursEnfants}`, 'Art. 223']);
                }
                
                if (resultatCalcul.majorationAnciennete > 0) {
                    rows.push(['Majoration ancienneté', `+${resultatCalcul.majorationAnciennete}`, 'Art. 223']);
                }

                if (resultatCalcul.reductionAbsences > 0) {
                    rows.push(['Réduction (absences)', `-${resultatCalcul.reductionAbsences}`, 'Calculée']);
                }

                rows.push(
                    ['Total acquis pour ' + resultatCalcul.donnees.anneeReference, resultatCalcul.joursAcquis, 'Art. 222-223', 'bg-green-50 font-semibold'],
                    ['Jours déjà pris en ' + resultatCalcul.donnees.anneeReference, `-${resultatCalcul.donnees.congesPris}`, '-'],
                    ['Solde restant', resultatCalcul.joursRestants, '-', 'bg-blue-50 font-semibold']
                );

                if (resultatCalcul.allocationTotale > 0) {
                    rows.push(['Allocation totale', `${formatMontant(resultatCalcul.allocationTotale)} FCFA`, 'Art. 225']);
                    rows.push(['Allocation restante', `${formatMontant(resultatCalcul.allocationRestante)} FCFA`, 'Art. 225']);
                }

                rows.forEach(([element, valeur, reference, classe = '']) => {
                    tableauHtml += `
                        <tr class="hover:bg-gray-50 ${classe}">
                            <td class="border border-gray-300 px-4 py-2">${element}</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">${valeur}</td>
                            <td class="border border-gray-300 px-4 py-2">${reference}</td>
                        </tr>
                    `;
                });
            } else {
                // Autres types de congés - lignes simplifiées
                const typeSpecificRows = getTypeSpecificTableRows();
                typeSpecificRows.forEach(([element, valeur, reference, classe = '']) => {
                    tableauHtml += `
                        <tr class="hover:bg-gray-50 ${classe}">
                            <td class="border border-gray-300 px-4 py-2">${element}</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">${valeur}</td>
                            <td class="border border-gray-300 px-4 py-2">${reference}</td>
                        </tr>
                    `;
                });
            }

            tableauHtml += `
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Articles de référence du Code du Travail gabonais :</h4>
                    <div class="flex flex-wrap gap-2">
            `;

            resultatCalcul.articlesRef.forEach(article => {
                tableauHtml += `<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">${article}</span>`;
            });

            tableauHtml += `
                    </div>
                    <p class="text-xs text-gray-600 mt-2">
                        Loi n°022/2021 du 19 novembre 2021 portant Code du Travail en République Gabonaise
                    </p>
                </div>
            `;
            
            tableauDiv.innerHTML = tableauHtml;
        }

        function getTypeSpecificTableRows() {
            const donnees = resultatCalcul.donnees;
            let rows = [];

            if (!resultatCalcul.eligible) {
                rows.push(['Statut', 'Non éligible', '-', 'bg-red-50']);
                rows.push(['Raison', resultatCalcul.raison, '-']);
                if (resultatCalcul.joursAcquisPartiels) {
                    rows.push(['Jours partiels acquis', resultatCalcul.joursAcquisPartiels, 'Calculé']);
                }
                return rows;
            }

            switch (donnees.typeConge) {
                case 'maternite':
                    rows = [
                        ['Durée de base', `${resultatCalcul.semainesBase} semaines`, 'Art. 208'],
                        ['Jours de base', `${resultatCalcul.joursBase} jours`, 'Art. 208'],
                        ['Période prénatal', `${resultatCalcul.joursPrenatal} jours`, 'Art. 208'],
                        ['Période postnatal', `${resultatCalcul.joursPostnatal} jours`, 'Art. 208'],
                        ['Extension naissances multiples', `+${resultatCalcul.extensions.naissanceMultiple} jours`, 'Art. 208'],
                        ['Extension complications', `+${resultatCalcul.extensions.complicationsMedicales} jours`, 'Art. 208'],
                        ['Maximum possible', `${resultatCalcul.joursMaximumPossible} jours`, 'Art. 208'],
                        ['Rémunération', resultatCalcul.maintienSalaire, 'Art. 208', 'bg-green-50']
                    ];
                    break;

                case 'paternite':
                    rows = [
                        ['Durée', `${resultatCalcul.joursAcquis} jours consécutifs`, 'Art. 54', 'bg-blue-50 font-semibold'],
                        ['Rémunération', resultatCalcul.maintienSalaire, 'Art. 54']
                    ];
                    if (resultatCalcul.allocationTotale > 0) {
                        rows.push(['Allocation', `${formatMontant(resultatCalcul.allocationTotale)} FCFA`, 'Art. 54']);
                    }
                    break;

                case 'evenements':
                    rows = [
                        ['Maximum annuel', `${resultatCalcul.joursMaximumAnnuel} jours`, 'Art. 223', 'bg-purple-50 font-semibold'],
                        ['Jours déjà pris', `${donnees.congesPris} jours`, '-'],
                        ['Jours restants', `${resultatCalcul.joursRestants} jours`, '-', 'bg-blue-50 font-semibold']
                    ];
                    break;

                case 'maladie':
                    rows = [
                        ['Durée maximum', `${resultatCalcul.dureeMaximumMois} mois`, 'Art. 54', 'bg-red-50 font-semibold'],
                        ['Équivalent jours', `${resultatCalcul.joursMaximum} jours`, 'Art. 54'],
                        ['Maladie conjoint/enfant', `${resultatCalcul.dispositionsSpeciales.maladieConjointEnfant} jours/an`, 'Art. 54'],
                        ['Rémunération', resultatCalcul.maintienSalaire, 'Art. 54', 'bg-green-50']
                    ];
                    break;
            }

            return rows;
        }

        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR', { 
                minimumFractionDigits: 0, 
                maximumFractionDigits: 0 
            }).format(Math.round(montant));
        }

        function setLoadingState(loading) {
            const calculBtn = document.getElementById('calculBtn');
            const calculBtnText = document.getElementById('calculBtnText');
            
            if (loading) {
                calculBtn.disabled = true;
                calculBtn.classList.add('loading');
                calculBtnText.innerHTML = '<div class="spinner"></div>Calcul en cours...';
            } else {
                calculBtn.disabled = false;
                calculBtn.classList.remove('loading');
                calculBtnText.innerHTML = 'Calculer mes congés';
            }
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            let alertClass, iconClass;
            
            switch(type) {
                case 'error':
                    alertClass = 'bg-red-50 border-red-200 text-red-700';
                    iconClass = 'fa-exclamation-circle text-red-400';
                    break;
                case 'success':
                    alertClass = 'bg-green-50 border-green-200 text-green-700';
                    iconClass = 'fa-check-circle text-green-400';
                    break;
                case 'info':
                    alertClass = 'bg-blue-50 border-blue-200 text-blue-700';
                    iconClass = 'fa-info-circle text-blue-400';
                    break;
                default:
                    alertClass = 'bg-gray-50 border-gray-200 text-gray-700';
                    iconClass = 'fa-info-circle text-gray-400';
            }
            
            const alertHtml = `
                <div class="p-4 border rounded-lg ${alertClass} transition-all duration-300">
                    <div class="flex items-start">
                        <i class="fas ${iconClass} mr-3 mt-0.5" aria-hidden="true"></i>
                        <div class="flex-1">
                            <p class="font-medium">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            `;
            
            alertContainer.innerHTML = alertHtml;
            
            // Auto-masquage après 5 secondes
            setTimeout(() => {
                const alert = alertContainer.querySelector('div');
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        function reinitialiserFormulaire() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.')) {
                // Reset du formulaire
                document.querySelectorAll('input, select').forEach(field => {
                    if (field.type === 'radio') {
                        field.checked = field.value === 'annuel';
                    } else if (field.type === 'number') {
                        field.value = field.id === 'enfantsCharge' || field.id === 'congesPris' || field.id === 'absencesInjustifiees' ? '0' : '';
                    } else if (field.id === 'anneeReference') {
                        // Réinitialiser à l'année courante
                        field.value = new Date().getFullYear();
                    } else if (field.id !== 'dateActuelle' && field.id !== 'dateEmbauche') {
                        field.value = field.type === 'select-one' ? '' : '';
                    }
                    
                    // Reset des styles d'erreur
                    field.classList.remove('form-field-error');
                });

                // Reset des messages d'erreur
                document.querySelectorAll('.error-message').forEach(errorDiv => {
                    errorDiv.textContent = '';
                    errorDiv.classList.add('hidden');
                });

                // Réinitialiser les dates
                initializeDates();

                // Reset des résultats
                resultatCalcul = {};
                validationErrors = {};
                
                document.getElementById('resumeContenu').innerHTML = `
                    <div class="text-center py-8 text-gray-600">
                        <i class="fas fa-info-circle text-4xl text-gray-300 mb-4" aria-hidden="true"></i>
                        <p class="text-lg font-medium mb-2">Aucun calcul effectué</p>
                        <p class="text-sm">Remplissez le formulaire et cliquez sur "Calculer mes congés" pour voir vos droits selon le Code du Travail gabonais.</p>
                    </div>
                `;
                
                document.getElementById('tableauContenu').innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-table text-4xl text-gray-300 mb-4" aria-hidden="true"></i>
                        <p>Les détails apparaîtront après le calcul</p>
                    </div>
                `;

                // Désactiver le bouton PDF
                document.getElementById('pdfBtn').disabled = true;

                // Reset de la barre de progression
                updateProgress();

                // Reset des alertes
                document.getElementById('alertContainer').innerHTML = '';

                showAlert('Formulaire réinitialisé avec succès.', 'success');
            }
        }

        function genererPDF() {
            // FIX: Vérification robuste de l'état du calcul
            if (!resultatCalcul.donnees || !resultatCalcul.typeCalcule) {
                showAlert('Veuillez d\'abord effectuer un calcul avant de générer le PDF.', 'error');
                return;
            }

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Configuration
                const pageWidth = doc.internal.pageSize.width;
                const margin = 20;
                const lineHeight = 7;
                let yPos = 30;

                // En-tête officiel
                doc.setFillColor(102, 126, 234);
                doc.rect(0, 0, pageWidth, 35, 'F');
                
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(18);
                doc.setFont('helvetica', 'bold');
                doc.text('ATTESTATION DE DROITS AUX CONGÉS', pageWidth/2, 20, { align: 'center' });
                
                doc.setFontSize(10);
                doc.setFont('helvetica', 'normal');
                doc.text('République Gabonaise - Code du Travail 2021', pageWidth/2, 28, { align: 'center' });
                
                yPos = 50;

                // Informations du document
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);
                doc.text(`Date d'édition : ${new Date().toLocaleDateString('fr-FR')}`, pageWidth - margin, yPos, { align: 'right' });
                doc.text(`Référence : ATT-${Date.now().toString().slice(-6)}`, margin, yPos);
                
                yPos += 15;

                // Informations personnelles
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                doc.text('INFORMATIONS DU SALARIÉ', margin, yPos);
                yPos += 10;
                
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                
                const infosPersonnelles = [
                    ['Nom et prénom', resultatCalcul.donnees.nomComplet],
                    ['Type de contrat', resultatCalcul.donnees.typeContrat],
                    ['Date d\'embauche', resultatCalcul.donnees.dateEmbauche.toLocaleDateString('fr-FR')],
                    ['Date de calcul', resultatCalcul.donnees.dateActuelle.toLocaleDateString('fr-FR')],
                    ['Année de référence', resultatCalcul.donnees.anneeReference.toString()],
                    ['Ancienneté totale', `${resultatCalcul.anciennete.annees} an(s) et ${resultatCalcul.anciennete.moisResiduels} mois`],
                    ['Âge', `${resultatCalcul.donnees.age} ans`]
                ];

                infosPersonnelles.forEach(([label, value]) => {
                    doc.text(`${label} :`, margin, yPos);
                    doc.text(value, margin + 50, yPos);
                    yPos += lineHeight;
                });

                yPos += 10;

                // Type de congé calculé
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                doc.text('CALCUL DES DROITS', margin, yPos);
                yPos += 10;

                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                doc.text(`Type de congé : ${resultatCalcul.typeCalcule}`, margin, yPos);
                yPos += lineHeight;
                doc.text(`Période de référence : Année civile ${resultatCalcul.donnees.anneeReference}`, margin, yPos);
                yPos += lineHeight;

                // FIX: Affichage de la période anniversaire pour les congés annuels
                if (resultatCalcul.donnees.typeConge === 'annuel' && resultatCalcul.periodeReference) {
                    doc.text(`Période anniversaire : ${resultatCalcul.periodeReference.debutPeriode.toLocaleDateString('fr-FR')} au ${resultatCalcul.periodeReference.finPeriode.toLocaleDateString('fr-FR')}`, margin, yPos);
                    yPos += lineHeight;
                }

                if (resultatCalcul.eligible) {
                    // Résultats détaillés selon le type
                    yPos = generatePDFDetails(doc, margin, yPos, lineHeight);
                } else {
                    doc.text(`Statut : Non éligible`, margin, yPos);
                    yPos += lineHeight;
                    doc.text(`Raison : ${resultatCalcul.raison}`, margin, yPos);
                    yPos += lineHeight;
                }

                // Références légales
                yPos += 15;
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                doc.text('RÉFÉRENCES LÉGALES', margin, yPos);
                yPos += 10;
                
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                doc.text('Code du Travail gabonais - Loi n°022/2021 du 19 novembre 2021', margin, yPos);
                yPos += lineHeight;
                
                resultatCalcul.articlesRef.forEach(article => {
                    doc.text(`• ${article}`, margin + 5, yPos);
                    yPos += lineHeight - 1;
                });

                // Pied de page
                doc.setFontSize(8);
                doc.setTextColor(100, 100, 100);
                const footerY = doc.internal.pageSize.height - 20;
                doc.text('Document généré automatiquement - À titre informatif uniquement', margin, footerY);
                doc.text(`Généré le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}`, margin, footerY + 6);

                // Espace pour signature
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);
                doc.text('Service des Ressources Humaines', pageWidth - margin, footerY - 20, { align: 'right' });
                doc.text('Signature et cachet :', pageWidth - margin, footerY - 13, { align: 'right' });
                doc.rect(pageWidth - margin - 50, footerY - 10, 45, 15);

                // Téléchargement
                const fileName = `attestation_conges_${resultatCalcul.donnees.nomComplet.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
                doc.save(fileName);

                showAlert('Attestation PDF générée avec succès !', 'success');

            } catch (error) {
                console.error('Erreur lors de la génération du PDF:', error);
                showAlert('Erreur lors de la génération du PDF. Veuillez réessayer.', 'error');
            }
        }

        function generatePDFDetails(doc, margin, startY, lineHeight) {
            let yPos = startY;
            const donnees = resultatCalcul.donnees;

            switch (donnees.typeConge) {
                case 'annuel':
                    const details = [
                        ['Jours acquis total', `${resultatCalcul.joursAcquis} jours ouvrables`],
                        ['Jours déjà pris', `${donnees.congesPris} jours`],
                        ['Solde restant', `${resultatCalcul.joursRestants} jours`]
                    ];

                    // FIX: Ajouter les détails de la période anniversaire
                    if (resultatCalcul.periodeReference) {
                        details.unshift(['Mois acquis', `${resultatCalcul.periodeReference.moisAcquis} mois`]);
                    }

                    if (resultatCalcul.allocationTotale > 0) {
                        details.push(
                            ['Allocation totale', `${formatMontant(resultatCalcul.allocationTotale)} FCFA`],
                            ['Allocation restante', `${formatMontant(resultatCalcul.allocationRestante)} FCFA`]
                        );
                    }

                    details.forEach(([label, value]) => {
                        doc.text(`${label} :`, margin, yPos);
                        doc.text(value, margin + 60, yPos);
                        yPos += lineHeight;
                    });
                    break;

                case 'maternite':
                    const materniteDetails = [
                        ['Durée de base', `${resultatCalcul.semainesBase} semaines (${resultatCalcul.joursBase} jours)`],
                        ['Période prénatal', `${resultatCalcul.joursPrenatal} jours (6 semaines)`],
                        ['Période postnatal', `${resultatCalcul.joursPostnatal} jours (8 semaines)`],
                        ['Extension naissances multiples', `+${resultatCalcul.extensions.naissanceMultiple} jours possibles`],
                        ['Extension complications', `+${resultatCalcul.extensions.complicationsMedicales} jours possibles`],
                        ['Rémunération', resultatCalcul.maintienSalaire]
                    ];

                    materniteDetails.forEach(([label, value]) => {
                        doc.text(`${label} :`, margin, yPos);
                        doc.text(value, margin + 60, yPos);
                        yPos += lineHeight;
                    });
                    break;

                case 'paternite':
                    doc.text(`Durée : ${resultatCalcul.joursAcquis} jours consécutifs`, margin, yPos);
                    yPos += lineHeight;
                    doc.text(`Rémunération : ${resultatCalcul.maintienSalaire}`, margin, yPos);
                    yPos += lineHeight;
                    doc.text('Conditions :', margin, yPos);
                    yPos += lineHeight;
                    resultatCalcul.conditions.forEach(condition => {
                        doc.text(`• ${condition}`, margin + 5, yPos);
                        yPos += lineHeight;
                    });
                    break;

                case 'evenements':
                    doc.text(`Maximum annuel : ${resultatCalcul.joursMaximumAnnuel} jours`, margin, yPos);
                    yPos += lineHeight;
                    doc.text(`Jours restants : ${resultatCalcul.joursRestants} jours`, margin, yPos);
                    yPos += lineHeight;
                    doc.text('Événements couverts :', margin, yPos);
                    yPos += lineHeight;
                    resultatCalcul.evenementsCouverts.forEach(event => {
                        doc.text(`• ${event}`, margin + 5, yPos);
                        yPos += lineHeight;
                    });
                    break;

                case 'maladie':
                    doc.text(`Durée maximum rémunérée : ${resultatCalcul.dureeMaximumMois} mois`, margin, yPos);
                    yPos += lineHeight;
                    doc.text(`Maladie conjoint/enfant : ${resultatCalcul.dispositionsSpeciales.maladieConjointEnfant} jours/an`, margin, yPos);
                    yPos += lineHeight;
                    doc.text(`Rémunération : ${resultatCalcul.maintienSalaire}`, margin, yPos);
                    yPos += lineHeight;
                    doc.text('Conditions :', margin, yPos);
                    yPos += lineHeight;
                    resultatCalcul.conditions.forEach(condition => {
                        doc.text(`• ${condition}`, margin + 5, yPos);
                        yPos += lineHeight;
                    });
                    break;
            }

            return yPos;
        }
    </script>
</body>
</html>