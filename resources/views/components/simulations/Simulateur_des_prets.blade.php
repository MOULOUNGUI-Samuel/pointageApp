<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Prêt Dynamique Avancé - INGENIUM Assurance</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 20px 30px;
            text-align: center;
        }
        
        .header img {
            max-height: 50px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            padding: 30px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            height: fit-content;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
        }

        .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-success { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
        .btn-warning { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .btn-danger { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        .btn-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }

        .results-section {
            background: white;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .summary-card h3 {
            font-size: 0.9em;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .summary-card .value {
            font-size: 1.8em;
            font-weight: bold;
        }

        .versement-section {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .versement-section h3 {
            color: #27ae60;
            margin-bottom: 15px;
        }

        .versement-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
            margin-bottom: 15px;
        }

        .versement-form input, .versement-form select {
            padding: 10px;
            border: 2px solid #27ae60;
            border-radius: 6px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .table-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 10px;
            text-align: right;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        th:first-child, td:first-child { text-align: center; }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            position: sticky; top: 0;
        }
        tr:hover { background: #f8f9fa; }
        .amount { font-family: 'Courier New', monospace; font-weight: bold; }
        .versements-historique { background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .versements-historique h4 { color: #856404; margin-bottom: 10px; }
        .versement-item {
            display: grid;
            grid-template-columns: auto 1fr auto auto;
            gap: 15px;
            padding: 10px;
            border-bottom: 1px solid #ffeeba;
            align-items: center;
        }
        .versement-item:last-child { border-bottom: none; }
        .versement-details { font-size: 0.9em; color: #6c757d; }
        .status-indicator { display: inline-block; width: 12px; height: 12px; border-radius: 50%; margin-right: 8px; }
        .status-active { background: #28a745; }
        .status-future { background: #6c757d; }
        .status-completed { background: #17a2b8; }
        .info-panel { background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .info-panel h4 { color: #0c5460; margin-bottom: 10px; }
        
        .timeline-container {
            /* max-height: 400px; et overflow-y: auto; sont supprimés pour permettre au tableau de s'étendre */
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .delete-btn { background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .date-display { font-weight: bold; color: #495057; }

        /* Print-specific styles */
        #print-header { display: none; }

        @media print {
            body { background: white; color: black; padding: 0; font-size: 10pt; }
            .container { box-shadow: none; border: none; border-radius: 0; max-width: 100%; }
            
            /* Hide non-printable elements */
            .form-section, .btn, .delete-btn, .versement-section, .versements-historique, .info-panel, .summary-cards { display: none !important; }

            .main-content { grid-template-columns: 1fr; padding: 20px; }
            .header { background: none; color: black; padding: 0 0 20px 0; border-bottom: 2px solid #333; margin-bottom: 20px; }
            .header h1, .header p { text-align: left; }
            .header img { float: right; max-height: 60px; margin: 0; }
            .results-section { padding: 0; }
            .table-container { box-shadow: none; border: 1px solid #ccc; }
            .table-header { background: #eee; color: black; text-align: left; }
            .table-header h3 { font-size: 1.2em; }
            .timeline-container { max-height: none; overflow-y: visible; border: none; }
            
            table { font-size: 9pt; }
            th, td { padding: 8px 6px; color: black; }
            th { background: #f2f2f2; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            tr:hover { background: none; }
            
            /* Show and style the print header */
            #print-header {
                display: block;
                border: 1px solid #ccc;
                padding: 15px;
                margin-bottom: 25px;
                background-color: #f8f9fa;
            }
            #print-header h2 {
                text-align: center;
                margin-bottom: 20px;
                font-size: 1.5em;
                color: #2c3e50;
            }
            .print-summary-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px 20px;
            }
            .print-summary-grid div {
                padding: 5px;
                border-bottom: 1px dotted #ccc;
            }
            .print-summary-grid strong {
                color: #333;
            }

            /* Prevent page breaks inside rows */
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; } /* Repeat header on each page */
        }


        @media (max-width: 1200px) {
            .main-content { grid-template-columns: 1fr; }
            .versement-form { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .summary-cards { grid-template-columns: 1fr; }
            table { font-size: 12px; }
            th, td { padding: 8px 6px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://ingenium-assurance.com/images/logo-ingenium-assu.png" alt="Logo Ingenium Assurance">
            <h1>Simulateur de Prêt Dynamique Avancé</h1>
            <p>Prêt entre Particulier et INGENIUM Assurance - Gestion avancée des dates et durées</p>
        </div>

        <div class="main-content">
            <div class="form-section">
                <h2 style="margin-bottom: 20px; color: #2c3e50;">Configuration du Prêt Initial</h2>
                
                <div class="form-group">
                    <label for="nomClient">Nom du Client</label>
                    <input type="text" id="nomClient" placeholder="Ex: Jean Dupont">
                </div>

                <div class="form-group">
                    <label for="montantInitial">Montant Initial Prêté (FCFA)</label>
                    <input type="number" id="montantInitial" value="0" min="0" step="1000">
                </div>

                <div class="form-group">
                    <label for="dateInitiale">Date de Début du Prêt</label>
                    <input type="date" id="dateInitiale">
                </div>

                <div class="form-group">
                    <label for="dureeInitiale">Durée Initiale (nombre de périodes)</label>
                    <input type="number" id="dureeInitiale" value="12" min="1" max="360">
                </div>

                <div class="form-group">
                    <label for="periodicite">Périodicité des Échéances</label>
                    <select id="periodicite">
                        <option value="hebdomadaire">Hebdomadaire</option>
                        <option value="mensuelle" selected>Mensuelle</option>
                        <option value="trimestrielle">Trimestrielle</option>
                        <option value="semestrielle">Semestrielle</option>
                        <option value="annuelle">Annuelle</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="taux">Taux d'Intérêt Annuel (%)</label>
                    <input type="number" id="taux" value="10" min="0" max="100" step="0.1">
                </div>

                <div class="action-buttons">
                    <button class="btn" onclick="initialiserPret()">Initialiser le Prêt</button>
                    <button class="btn btn-warning" onclick="reinitialiser()">Réinitialiser</button>
                    <button class="btn btn-info" onclick="imprimer()">Imprimer</button>
                </div>

                <div class="versement-section">
                    <h3>🏦 Ajouter un Versement Additionnel</h3>
                    <div class="versement-form">
                        <div>
                            <label for="nouveauVersement" style="font-size: 0.9em; margin-bottom: 5px;">Montant (FCFA)</label>
                            <input type="number" id="nouveauVersement" placeholder="1000000" min="0" step="1000">
                        </div>
                        <div>
                            <label for="dateVersement" style="font-size: 0.9em; margin-bottom: 5px;">Date du versement</label>
                            <input type="date" id="dateVersement">
                        </div>
                        <div>
                            <label for="nouvelleDuree" style="font-size: 0.9em; margin-bottom: 5px;">Nouvelle durée (périodes)</label>
                            <input type="number" id="nouvelleDuree" placeholder="12" min="1" max="360">
                        </div>
                        <div>
                            <button class="btn btn-success" onclick="ajouterVersement()">Ajouter</button>
                        </div>
                    </div>
                </div>

                <div id="versementsHistorique" class="versements-historique" style="display: none;">
                    <h4>📋 Historique des Versements</h4>
                    <div id="listeVersements"></div>
                </div>

                <div class="info-panel">
                    <h4>ℹ️ Information</h4>
                    <p>Chaque versement additionnel recalcule automatiquement le tableau d'amortissement à partir de la date spécifiée, en tenant compte de la nouvelle durée et du capital restant dû à cette date.</p>
                </div>
            </div>

            <div class="results-section">
                <!-- This section is for printing only -->
                <div id="print-header"></div>
                
                <div class="summary-cards">
                    <div class="summary-card">
                        <h3>Capital Total</h3>
                        <div class="value amount" id="capitalTotal">0 FCFA</div>
                    </div>
                    <div class="summary-card">
                        <h3>Échéance Actuelle</h3>
                        <div class="value amount" id="echeanceActuelle">0 FCFA</div>
                    </div>
                    <div class="summary-card">
                        <h3>Capital Restant</h3>
                        <div class="value amount" id="capitalRestant">0 FCFA</div>
                    </div>
                    <div class="summary-card">
                        <h3>Prochaine Échéance</h3>
                        <div class="value" id="prochaineEcheance">-</div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <h3>📊 Tableau d'Amortissement Dynamique</h3>
                    </div>
                    <div class="timeline-container">
                        <table id="tableauAmortissement">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Période</th>
                                    <th>Capital Début</th>
                                    <th>Échéance</th>
                                    <th>Intérêts</th>
                                    <th>Capital Remboursé</th>
                                    <th>Capital Fin</th>
                                    <th>Événement</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let configurationPret = null;
        let versements = [];
        let tableauAmortissement = [];

        // Initialiser la date d'aujourd'hui
        document.getElementById('dateInitiale').value = new Date().toISOString().split('T')[0];
        document.getElementById('dateVersement').value = new Date().toISOString().split('T')[0];

        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(montant)) + ' FCFA';
        }

        function formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit' });
        }

        function calculerTauxPeriodique(tauxAnnuel, periodicite) {
            const nbPeriodes = {
                'hebdomadaire': 52,
                'mensuelle': 12,
                'trimestrielle': 4,
                'semestrielle': 2,
                'annuelle': 1
            };
            return tauxAnnuel / 100 / nbPeriodes[periodicite];
        }

        function calculerDateEcheance(dateDebut, numeroPeriode, periodicite) {
            const date = new Date(dateDebut);
            
            switch(periodicite) {
                case 'hebdomadaire':
                    date.setDate(date.getDate() + (numeroPeriode * 7));
                    break;
                case 'mensuelle':
                    date.setMonth(date.getMonth() + numeroPeriode);
                    break;
                case 'trimestrielle':
                    date.setMonth(date.getMonth() + (numeroPeriode * 3));
                    break;
                case 'semestrielle':
                    date.setMonth(date.getMonth() + (numeroPeriode * 6));
                    break;
                case 'annuelle':
                    date.setFullYear(date.getFullYear() + numeroPeriode);
                    break;
            }
            return date;
        }

        function calculerEcheance(capital, tauxPeriodique, nbPeriodes) {
            if (tauxPeriodique === 0) {
                return capital / nbPeriodes;
            }
            if (nbPeriodes <= 0) {
                return capital;
            }
            return capital * (tauxPeriodique * Math.pow(1 + tauxPeriodique, nbPeriodes)) / 
                   (Math.pow(1 + tauxPeriodique, nbPeriodes) - 1);
        }

        function initialiserPret() {
            const montantInitial = parseFloat(document.getElementById('montantInitial').value) || 0;
            const dateInitiale = document.getElementById('dateInitiale').value;
            const dureeInitiale = parseInt(document.getElementById('dureeInitiale').value) || 12;
            const periodicite = document.getElementById('periodicite').value;
            const taux = parseFloat(document.getElementById('taux').value) || 10;
            const nomClient = document.getElementById('nomClient').value;

            if (!dateInitiale) {
                alert('Veuillez sélectionner une date de début');
                return;
            }
            if (montantInitial <= 0) {
                alert('Veuillez saisir un montant initial valide');
                return;
            }

            configurationPret = {
                montantInitial,
                dateInitiale,
                dureeInitiale,
                periodicite,
                taux,
                nomClient
            };
            versements = [];
            calculerTableauComplet();
        }

        function ajouterVersement() {
            if (!configurationPret) {
                alert('Veuillez d\'abord initialiser le prêt');
                return;
            }

            const montant = parseFloat(document.getElementById('nouveauVersement').value);
            const dateVersement = document.getElementById('dateVersement').value;
            const nouvelleDuree = parseInt(document.getElementById('nouvelleDuree').value);
            
            if (!montant || montant <= 0) {
                alert('Veuillez saisir un montant valide');
                return;
            }
            if (!dateVersement) {
                alert('Veuillez sélectionner une date de versement');
                return;
            }
            if (!nouvelleDuree || nouvelleDuree <= 0) {
                alert('Veuillez saisir une durée valide');
                return;
            }
            if (new Date(dateVersement) < new Date(configurationPret.dateInitiale)) {
                alert("La date d'un versement ne peut pas être antérieure à la date de début du prêt.");
                return;
            }

            const versement = { montant, date: dateVersement, nouvelleDuree, id: Date.now() };
            versements.push(versement);
            versements.sort((a, b) => new Date(a.date) - new Date(b.date));
            
            document.getElementById('nouveauVersement').value = '';
            document.getElementById('dateVersement').value = new Date().toISOString().split('T')[0];
            document.getElementById('nouvelleDuree').value = '';
            
            afficherVersements();
            calculerTableauComplet();
        }

        function supprimerVersement(id) {
            versements = versements.filter(v => v.id !== id);
            afficherVersements();
            calculerTableauComplet();
        }

        function calculerTableauComplet() {
            if (!configurationPret) return;

            tableauAmortissement = [];
            let capitalActuel = configurationPret.montantInitial;
            let dateDeCalcul = new Date(configurationPret.dateInitiale);
            let dureeRestante = configurationPret.dureeInitiale;
            let numeroPeriodeGlobal = 0;
            const tauxPeriodique = calculerTauxPeriodique(configurationPret.taux, configurationPret.periodicite);
            
            let echeanceActuelle = calculerEcheance(capitalActuel, tauxPeriodique, dureeRestante);

            let versementIndex = 0;

            while (capitalActuel > 0.01 && dureeRestante > 0) {
                numeroPeriodeGlobal++;
                let dateEcheance = calculerDateEcheance(dateDeCalcul, 1, configurationPret.periodicite);

                // Vérifier s'il y a un versement avant cette échéance
                let versementApplique = false;
                while(versementIndex < versements.length && new Date(versements[versementIndex].date) < dateEcheance) {
                    const versement = versements[versementIndex];
                    const dateVersement = new Date(versement.date);
                    
                    // Calculer les intérêts jusqu'à la date du versement
                    const joursDepuisDernierEvenement = (dateVersement - dateDeCalcul) / (1000 * 3600 * 24);
                    const joursParPeriode = {'mensuelle': 30.4375, 'hebdomadaire': 7, 'trimestrielle': 91.3125, 'semestrielle': 182.625, 'annuelle': 365.25}[configurationPret.periodicite];
                    const interetProRata = capitalActuel * tauxPeriodique * (joursDepuisDernierEvenement / joursParPeriode);
                    
                    capitalActuel += versement.montant;
                    
                    tableauAmortissement.push({
                        date: dateVersement,
                        periode: '-',
                        capitalDebut: capitalActuel - versement.montant,
                        echeance: 0,
                        interets: interetProRata, // On peut ajouter les intérêts courus ici
                        capitalRembourse: 0,
                        capitalFin: capitalActuel,
                        evenement: `Versement: ${formatMontant(versement.montant)} (Durée: ${versement.nouvelleDuree} p.)`
                    });

                    dateDeCalcul = dateVersement;
                    dureeRestante = versement.nouvelleDuree;
                    echeanceActuelle = calculerEcheance(capitalActuel, tauxPeriodique, dureeRestante);
                    versementIndex++;
                    versementApplique = true;
                }
                
                if (versementApplique) {
                    dateEcheance = calculerDateEcheance(dateDeCalcul, 1, configurationPret.periodicite);
                }

                const interets = capitalActuel * tauxPeriodique;
                const capitalRembourse = Math.min(echeanceActuelle - interets, capitalActuel);
                const capitalFin = Math.max(0, capitalActuel - capitalRembourse);
                
                tableauAmortissement.push({
                    date: dateEcheance,
                    periode: numeroPeriodeGlobal,
                    capitalDebut: capitalActuel,
                    echeance: interets + capitalRembourse,
                    interets,
                    capitalRembourse,
                    capitalFin,
                    evenement: ''
                });

                capitalActuel = capitalFin;
                dureeRestante--;
                dateDeCalcul = dateEcheance;
            }

            afficherTableau();
            mettreAJourResumes();
        }

        function afficherTableau() {
            const tbody = document.querySelector('#tableauAmortissement tbody');
            tbody.innerHTML = '';

            tableauAmortissement.forEach((ligne) => {
                const tr = document.createElement('tr');
                const isEvenement = ligne.evenement.includes('Versement');
                
                tr.innerHTML = `
                    <td class="date-display">${formatDate(ligne.date)}</td>
                    <td><span class="status-indicator ${isEvenement ? 'status-active' : 'status-future'}"></span>${ligne.periode}</td>
                    <td class="amount">${formatMontant(ligne.capitalDebut)}</td>
                    <td class="amount">${formatMontant(ligne.echeance)}</td>
                    <td class="amount">${formatMontant(ligne.interets)}</td>
                    <td class="amount">${formatMontant(ligne.capitalRembourse)}</td>
                    <td class="amount">${formatMontant(ligne.capitalFin)}</td>
                    <td style="font-size: 0.9em; color: ${isEvenement ? '#e74c3c' : '#6c757d'};">${ligne.evenement}</td>
                `;
                
                if (isEvenement) { tr.style.backgroundColor = '#fff3cd'; tr.style.fontWeight = 'bold'; }
                tbody.appendChild(tr);
            });
        }

        function mettreAJourResumes() {
            if (!configurationPret || tableauAmortissement.length === 0) {
                document.getElementById('capitalTotal').textContent = '0 FCFA';
                document.getElementById('echeanceActuelle').textContent = '0 FCFA';
                document.getElementById('capitalRestant').textContent = '0 FCFA';
                document.getElementById('prochaineEcheance').textContent = '-';
                return;
            }

            const capitalTotal = configurationPret.montantInitial + versements.reduce((sum, v) => sum + v.montant, 0);
            const capitalRestant = tableauAmortissement[tableauAmortissement.length - 1].capitalFin;
            
            const prochaineEcheance = tableauAmortissement.find(l => l.periode !== '-' && new Date(l.date) >= new Date());
            const echeanceActuelle = prochaineEcheance ? prochaineEcheance.echeance : 0;

            document.getElementById('capitalTotal').textContent = formatMontant(capitalTotal);
            document.getElementById('echeanceActuelle').textContent = formatMontant(echeanceActuelle);
            document.getElementById('capitalRestant').textContent = formatMontant(capitalRestant);
            document.getElementById('prochaineEcheance').textContent = prochaineEcheance ? formatDate(prochaineEcheance.date) : 'Prêt terminé';
        }

        function afficherVersements() {
            const container = document.getElementById('versementsHistorique');
            const liste = document.getElementById('listeVersements');
            
            container.style.display = versements.length === 0 ? 'none' : 'block';
            liste.innerHTML = '';

            versements.forEach((versement, index) => {
                const div = document.createElement('div');
                div.className = 'versement-item';
                div.innerHTML = `
                    <span>${index + 1}</span>
                    <div>
                        <div style="font-weight: bold;">${formatMontant(versement.montant)}</div>
                        <div class="versement-details">Date: ${formatDate(versement.date)} | Durée: ${versement.nouvelleDuree} périodes</div>
                    </div>
                    <button class="delete-btn" onclick="supprimerVersement(${versement.id})">×</button>
                `;
                liste.appendChild(div);
            });
        }

        function reinitialiser() {
            configurationPret = null;
            versements = [];
            tableauAmortissement = [];
            
            document.getElementById('nomClient').value = '';
            document.getElementById('montantInitial').value = '0';
            document.getElementById('dureeInitiale').value = '12';
            document.getElementById('periodicite').value = 'mensuelle';
            document.getElementById('taux').value = '10';
            document.getElementById('dateInitiale').value = new Date().toISOString().split('T')[0];
            document.getElementById('dateVersement').value = new Date().toISOString().split('T')[0];
            
            mettreAJourResumes();
            document.querySelector('#tableauAmortissement tbody').innerHTML = '';
            document.getElementById('versementsHistorique').style.display = 'none';
        }

        function imprimer() {
            if (!configurationPret || tableauAmortissement.length === 0) {
                alert("Veuillez d'abord initialiser et calculer un prêt avant d'imprimer.");
                return;
            }

            const nomClient = document.getElementById('nomClient').value || "Non spécifié";
            const capitalInitial = configurationPret.montantInitial;
            const versementsAdditionnels = versements.reduce((sum, v) => sum + v.montant, 0);
            const capitalTotalEmprunte = capitalInitial + versementsAdditionnels;
            const totalInterets = tableauAmortissement.reduce((sum, ligne) => sum + ligne.interets, 0);
            const coutTotalCredit = totalInterets;
            const totalRembourse = capitalTotalEmprunte + totalInterets;
            const derniereEcheance = tableauAmortissement.length > 0 ? tableauAmortissement[tableauAmortissement.length - 1].date : null;

            const printHeader = document.getElementById('print-header');
            printHeader.innerHTML = `
                <h2>Synthèse du Prêt - ${nomClient}</h2>
                <div class="print-summary-grid">
                    <div><strong>Client:</strong> ${nomClient}</div>
                    <div><strong>Date d'impression:</strong> ${formatDate(new Date())}</div>
                    <div><strong>Montant Initial:</strong> ${formatMontant(capitalInitial)}</div>
                    <div><strong>Date de Début:</strong> ${formatDate(configurationPret.dateInitiale)}</div>
                    <div><strong>Versements Additionnels:</strong> ${formatMontant(versementsAdditionnels)}</div>
                    <div><strong>Date de Fin Prévue:</strong> ${derniereEcheance ? formatDate(derniereEcheance) : 'N/A'}</div>
                    <div><strong>Capital Total Emprunté:</strong> ${formatMontant(capitalTotalEmprunte)}</div>
                    <div><strong>Taux Annuel:</strong> ${configurationPret.taux}%</div>
                    <div><strong>Total des Intérêts:</strong> ${formatMontant(totalInterets)}</div>
                    <div><strong>Périodicité:</strong> ${configurationPret.periodicite.charAt(0).toUpperCase() + configurationPret.periodicite.slice(1)}</div>
                    <div><strong>Coût Total du Crédit:</strong> ${formatMontant(coutTotalCredit)}</div>
                    <div><strong>Montant Total à Rembourser:</strong> ${formatMontant(totalRembourse)}</div>
                </div>
            `;
            
            window.print();
        }
        
        window.addEventListener('load', function() {
            // Dates déjà initialisées dans le HTML
        });
    </script>
</body>
</html>