<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur Facturation & Éditeur - Gabon (TPS/CSS)</title>
    <style>
        :root {
            --primary: #004aad;
            /* Bleu Corporate */
            --secondary: #008751;
            /* Vert Gabon */
            --dark: #2c3e50;
            --light: #f8f9fa;
            --border: #dee2e6;
            --danger: #dc3545;
        }

        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background: #eef2f5;
            color: var(--dark);
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }

        /* --- STYLES ECRAN (Masqués à l'impression) --- */
        .screen-only {
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            color: var(--primary);
            margin: 0;
        }

        header p {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: white;
            border-radius: 8px 8px 0 0;
            overflow: hidden;
            border: 1px solid var(--border);
            margin-bottom: -1px;
        }

        .tab-btn {
            flex: 1;
            padding: 15px;
            border: none;
            background: #f1f1f1;
            cursor: pointer;
            font-weight: 600;
            color: #777;
            transition: 0.3s;
            border-bottom: 3px solid transparent;
        }

        .tab-btn.active {
            background: white;
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* Card Principal */
        .calculator-card {
            background: white;
            border-radius: 0 0 8px 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
        }

        .input-group {
            text-align: center;
            margin-bottom: 30px;
        }

        .input-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .input-wrapper {
            display: flex;
            justify-content: center;
            gap: 10px;
            align-items: center;
        }

        .input-group input {
            padding: 12px;
            font-size: 1.3rem;
            width: 300px;
            text-align: center;
            border: 2px solid var(--border);
            border-radius: 6px;
        }

        .input-group input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn-calc {
            padding: 12px 25px;
            font-size: 1.1rem;
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
            height: 54px;
            /* Align with input */
        }

        .btn-calc:hover {
            background-color: #006b3f;
        }

        /* Grille Comparaison */
        .comparison-grid {
            display: none;
            /* Masqué par défaut */
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
            animation: fadeIn 0.5s ease-out;
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

        .mode-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            position: relative;
            overflow: hidden;
        }

        .mode-header {
            padding: 15px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .mode-provider .mode-header {
            background: #e3f2fd;
            color: #0d47a1;
        }

        .mode-client .mode-header {
            background: #e8f5e9;
            color: #1b5e20;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .data-table td {
            padding: 8px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table .row-total {
            background: #f8f9fa;
            font-weight: bold;
        }

        .data-table .row-deduction {
            color: var(--danger);
        }

        .data-table .row-net {
            font-weight: bold;
            font-size: 1.1rem;
            border-top: 2px solid #dee2e6;
        }

        .amount {
            text-align: right;
            font-family: 'Consolas', monospace;
        }

        .tax-info {
            padding: 10px 15px;
            background: #fff5f5;
            color: #c0392b;
            font-size: 0.8rem;
            border-top: 1px solid #ffebee;
        }

        /* Section Facturation */
        .invoice-setup {
            display: none;
            /* Masqué tant que pas calculé */
            background: #f8f9fa;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .invoice-setup h3 {
            margin-top: 0;
            color: var(--dark);
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            padding-bottom: 5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-print:hover {
            background: #003366;
        }

        /* --- STYLES IMPRESSION (Visibles seulement au print) --- */
        .print-only {
            display: none;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
                font-family: 'Times New Roman', serif;
                color: black;
            }

            .screen-only {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            /* Layout Facture A4 */
            .invoice-page {
                width: 210mm;
                min-height: 297mm;
                padding: 15mm;
                box-sizing: border-box;
                margin: 0 auto;
                position: relative;
            }

            .inv-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 40px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
            }

            .inv-provider h2 {
                margin: 0;
                font-size: 18pt;
                text-transform: uppercase;
            }

            .inv-provider p {
                margin: 2px 0;
                font-size: 10pt;
            }

            .inv-meta {
                text-align: right;
            }

            .inv-meta h1 {
                margin: 0;
                font-size: 24pt;
                color: #333;
            }

            .inv-meta p {
                margin: 5px 0;
                font-size: 11pt;
            }

            .inv-client {
                margin-bottom: 40px;
                border: 1px solid #000;
                padding: 15px;
                width: 45%;
                margin-left: auto;
            }

            .inv-client h3 {
                margin-top: 0;
                font-size: 12pt;
                text-transform: uppercase;
                border-bottom: 1px solid #ccc;
                padding-bottom: 5px;
            }

            .inv-description {
                margin-bottom: 30px;
            }

            .inv-desc-title {
                font-weight: bold;
                border-bottom: 1px solid #000;
                padding-bottom: 5px;
                margin-bottom: 10px;
            }

            .inv-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }

            .inv-table th {
                border: 1px solid #000;
                padding: 8px;
                background: #eee;
                font-size: 11pt;
                text-align: left;
            }

            .inv-table td {
                border: 1px solid #000;
                padding: 8px;
                font-size: 11pt;
            }

            .inv-table .col-amount {
                text-align: right;
                width: 150px;
            }

            .inv-table .total-row td {
                border-top: 2px solid #000;
                font-weight: bold;
            }

            .inv-legal {
                font-size: 9pt;
                margin-top: 50px;
                text-align: center;
                border-top: 1px solid #ccc;
                padding-top: 10px;
            }

            .signature-box {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
                page-break-inside: avoid;
            }

            .sig-area {
                width: 200px;
                height: 100px;
                border: 1px solid #ccc;
                padding: 10px;
                font-size: 10pt;
            }
        }
    </style>
</head>

<body>

    <!-- INTERFACE ECRAN -->
    <div class="screen-only">
        <header>
            <h1>Simulateur Fiscal & Facturation Gabon</h1>
            <p>Conforme Loi de Finances | TPS 9.5% | CSS 1.0%</p>
        </header>

        <div class="tabs">
            <button class="tab-btn active" onclick="setMode('ht')">CAS 1 : HT Connu</button>
            <button class="tab-btn" onclick="setMode('ttc_provider')">CAS 2 : TTC (Prestataire)</button>
            <button class="tab-btn" onclick="setMode('ttc_client')">CAS 3 : Total (Client)</button>
        </div>

        <div class="calculator-card">
            <!-- Zone de Saisie -->
            <div class="input-group">
                <label id="input-label">Montant Honoraires Bruts (HT) :</label>
                <div class="input-wrapper">
                    <input type="number" id="input-amount" placeholder="Entrez le montant"
                        onkeydown="checkEnter(event)">
                    <button class="btn-calc" onclick="calculate()">CALCULER</button>
                </div>
                <div id="math-helper" style="font-size: 0.8rem; color: #888; margin-top: 5px;"></div>
            </div>

            <!-- Comparateur Visuel -->
            <div class="comparison-grid" id="results-area">

                <!-- OPTION A : PRESTATAIRE PAIE LA TPS-->
                <div class="mode-card mode-provider">
                    <div class="mode-header">Option A : Prestataire paie la TPS</div>
                    <table class="data-table">
                        <tr>
                            <td>Honoraires HT</td>
                            <td class="amount" id="pa-ht">0</td>
                        </tr>
                        <tr>
                            <td>CSS (1%)</td>
                            <td class="amount" id="pa-css">0</td>
                        </tr>
                        <tr>
                            <td>TPS (9.5%)</td>
                            <td class="amount" id="pa-tps">0</td>
                        </tr>
                        <tr class="row-total">
                            <td>Total Facture (Réel)</td>
                            <td class="amount" id="pa-total">0</td>
                        </tr>
                        <tr class="row-net">
                            <td>Net à payer par Client</td>
                            <td class="amount" style="color:var(--primary)" id="pa-net">0</td>
                        </tr>
                    </table>
                    <div class="tax-info">
                        <strong>À reverser à l'État :</strong> <span id="pa-reverse">0</span> (CSS + TPS)
                    </div>
                </div>

                <!-- OPTION B : CLIENT PAIE LA TPS -->
                <div class="mode-card mode-client">
                    <div class="mode-header">Option B : Client paie la TPS </div>
                    <table class="data-table">
                        <tr>
                            <td>Honoraires HT</td>
                            <td class="amount" id="pb-ht">0</td>
                        </tr>
                        <tr>
                            <td>CSS (1%)</td>
                            <td class="amount" id="pb-css">0</td>
                        </tr>
                        <tr>
                            <td>TPS (9.5%) <small>(Info)</small></td>
                            <td class="amount" id="pb-tps">0</td>
                        </tr>
                        <tr class="row-total">
                            <td>Total Coût Réel</td>
                            <td class="amount" id="pb-total-real">0</td>
                        </tr>
                        <tr class="row-deduction">
                            <td>- Retenue TPS</td>
                            <td class="amount" id="pb-deduction">0</td>
                        </tr>
                        <tr class="row-net">
                            <td>Net à payer par Client</td>
                            <td class="amount" style="color:var(--secondary)" id="pb-net">0</td>
                        </tr>
                    </table>
                    <div class="tax-info">
                        <strong>À reverser à l'État :</strong> <span id="pb-reverse">0</span> (TPS par le Client)
                    </div>
                </div>
            </div>

            <!-- Configuration Facture -->
            <div class="invoice-setup" id="invoice-area">
                <h3>🧾 Générateur de Facture</h3>
                <p>Remplissez les informations ci-dessous pour générer le document officiel.</p>

                <div class="form-grid">
                    <!-- Colonne Prestataire -->
                    <div class="form-group">
                        <label>VOTRE ENTREPRISE (Prestataire)</label>
                        <input type="text" id="cfg-p-name" placeholder="Nom de votre société"
                            value="MA SOCIÉTÉ SARL">
                        <input type="text" id="cfg-p-nif" placeholder="NIF" value="NIF: 123456789">
                        <input type="text" id="cfg-p-rccm" placeholder="RCCM" value="RCCM: 2024-B-000">
                        <input type="text" id="cfg-p-contact" placeholder="Tél / Email" value="+241 00 00 00">
                    </div>
                    <!-- Colonne Client -->
                    <div class="form-group">
                        <label>CLIENT</label>
                        <input type="text" id="cfg-c-name" placeholder="Nom du Client">
                        <input type="text" id="cfg-c-nif" placeholder="NIF Client (Optionnel)">
                        <input type="text" id="cfg-inv-num" placeholder="Numéro de Facture" value="FAC-2024-001">
                        <input type="date" id="cfg-inv-date">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>DESCRIPTION DE LA PRESTATION</label>
                    <textarea id="cfg-desc" rows="2" placeholder="Ex: Honoraires pour mission de conseil...">Prestation de services - Mission d'assistance technique</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>MODE DE FACTURATION À IMPRIMER</label>
                    <select id="cfg-mode-select">
                        <option value="A">Option A : Je paie la TPS (Facture Classique)</option>
                        <option value="B">Option B : Le Client paie la TPS (Retenue à la source)</option>
                    </select>
                </div>

                <button class="btn-print" onclick="prepareAndPrint()">🖨️ IMPRIMER LA FACTURE OFFICIELLE</button>
            </div>
        </div>
    </div>

    <!-- MODELE D'IMPRESSION (Invisible à l'écran) -->
    <div class="print-only invoice-page" id="invoice-template">
        <!-- Header -->
        <div class="inv-header">
            <div class="inv-provider">
                <h2 id="pr-p-name">NOM SOCIETE</h2>
                <p id="pr-p-nif">NIF</p>
                <p id="pr-p-rccm">RCCM</p>
                <p id="pr-p-contact">Contact</p>
            </div>
            <div class="inv-meta">
                <h1>FACTURE</h1>
                <p><strong>N° :</strong> <span id="pr-inv-num"></span></p>
                <p><strong>Date :</strong> <span id="pr-inv-date"></span></p>
            </div>
        </div>

        <!-- Client -->
        <div class="inv-client">
            <h3>Facturé à :</h3>
            <p><strong id="pr-c-name">Nom Client</strong></p>
            <p id="pr-c-nif">NIF Client</p>
        </div>

        <!-- Description -->
        <div class="inv-description">
            <div class="inv-desc-title">Désignation</div>
            <p id="pr-desc" style="white-space: pre-wrap;">Description...</p>
        </div>

        <!-- Tableau de Calcul -->
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th class="col-amount">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Honoraires H.T.</td>
                    <td class="col-amount" id="pr-val-ht">0</td>
                </tr>
                <tr>
                    <td>CSS (Contribution Spéciale de Solidarité 1%)</td>
                    <td class="col-amount" id="pr-val-css">0</td>
                </tr>
                <tr>
                    <td>TPS (Taxe sur Prestations de Service 9.5%)</td>
                    <td class="col-amount" id="pr-val-tps">0</td>
                </tr>
                <tr class="total-row" style="background: #f0f0f0;">
                    <td>TOTAL GÉNÉRAL (Coût Réel)</td>
                    <td class="col-amount" id="pr-val-total">0</td>
                </tr>
                <!-- Lignes conditionnelles pour RAS -->
                <tr id="row-ras-label" style="display:none; color: #555;">
                    <td><i>Retenue à la source TPS (par le client)</i></td>
                    <td class="col-amount" id="pr-val-ras">0</td>
                </tr>
                <tr id="row-net-pay" style="font-size: 14pt; font-weight: bold;">
                    <td id="label-net-pay">NET À PAYER</td>
                    <td class="col-amount" id="pr-val-net">0</td>
                </tr>
            </tbody>
        </table>

        <div style="font-size: 0.9rem; margin-top: 10px;">
            Arrêté la présente facture à la somme de : <strong><span id="pr-text-amount">...</span> FCFA</strong>.
        </div>

        <!-- Mentions Légales -->
        <div class="inv-legal">
            <p>En application de la législation fiscale en vigueur au Gabon.</p>
            <p>La CSS (1%) est due par le prestataire. La TPS (9.5%) est <span id="pr-legal-mode">collectée par le
                    prestataire</span>.</p>
        </div>

        <!-- Signature -->
        <div class="signature-box">
            <div class="sig-area">
                <strong>Pour le Client</strong><br>
                (Bon pour accord)
            </div>
            <div class="sig-area">
                <strong>Le Prestataire</strong><br>
                (Cachet et Signature)
            </div>
        </div>
    </div>

    <script>
        // --- CONSTANTES ---
        const RATE_TPS = 0.095;
        const RATE_CSS = 0.01;
        let currentMode = 'ht';
        let globalValues = {
            ht: 0,
            css: 0,
            tps: 0,
            total: 0
        };

        // --- DOM HELPERS ---
        const getEl = (id) => document.getElementById(id);
        const formatMoney = (amount) => {
            return new Intl.NumberFormat('fr-GA', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount); // Pas de symbole CFA pour faciliter alignement
        };

        // --- INITIALISATION ---
        getEl('cfg-inv-date').valueAsDate = new Date();

        // --- LOGIQUE METIER ---
        function setMode(mode) {
            currentMode = mode;

            // UI Updates
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');

            const label = getEl('input-label');
            const helper = getEl('math-helper');
            const input = getEl('input-amount');

            // Reset UI
            input.value = '';
            getEl('results-area').style.display = 'none'; // Masquer
            getEl('invoice-area').style.display = 'none'; // Masquer

            if (mode === 'ht') {
                label.innerText = "Montant Honoraires Bruts (HT) :";
                helper.innerText = "Je connais mes honoraires bruts (HT connu).";
            } else if (mode === 'ttc_provider') {
                label.innerText = "Montant TTC (HT + CSS + TPS) :";
                helper.innerText = "Je connais le montant TOTAL facturé quand le prestataire paie la TPS.";
            } else {
                label.innerText = "Montant Facturé Client (HT + CSS) :";
                helper.innerText = "Je connais le montant TOTAL facturé quand le client paie la TPS.";
            }
            input.focus();
        }

        // Gestion touche Entrée
        function checkEnter(event) {
            if (event.key === "Enter") {
                calculate();
            }
        }

        function calculate() {
            const inputVal = parseFloat(getEl('input-amount').value);

            if (isNaN(inputVal) || inputVal <= 0) {
                alert("Veuillez saisir un montant valide.");
                getEl('results-area').style.display = 'none';
                getEl('invoice-area').style.display = 'none';
                return;
            }

            // Afficher les zones
            getEl('results-area').style.display = 'grid';
            getEl('invoice-area').style.display = 'block';

            // 1. Reconstitution du HT
            let ht = 0;
            if (currentMode === 'ht') {
                ht = inputVal;
            } else if (currentMode === 'ttc_provider') {
                // HT = TTC / (1 + 0.01 + 0.095)
                ht = inputVal / (1 + RATE_CSS + RATE_TPS);
            } else if (currentMode === 'ttc_client') {
                // HT = (HT+CSS) / (1 + 0.01)
                ht = inputVal / (1 + RATE_CSS);
            }

            // 2. Calcul des taxes
            const css = ht * RATE_CSS;
            const tps = ht * RATE_TPS;
            const totalReal = ht + css + tps;

            // Stockage global pour impression
            globalValues = {
                ht,
                css,
                tps,
                total: totalReal
            };

            // 3. Affichage Cas A (Prestataire paie)
            getEl('pa-ht').innerText = formatMoney(ht);
            getEl('pa-css').innerText = formatMoney(css);
            getEl('pa-tps').innerText = formatMoney(tps);
            getEl('pa-total').innerText = formatMoney(totalReal);
            getEl('pa-net').innerText = formatMoney(totalReal);
            getEl('pa-reverse').innerText = formatMoney(css + tps);

            // 4. Affichage Cas B (Client paie)
            getEl('pb-ht').innerText = formatMoney(ht);
            getEl('pb-css').innerText = formatMoney(css);
            getEl('pb-tps').innerText = formatMoney(tps);
            getEl('pb-total-real').innerText = formatMoney(totalReal);
            getEl('pb-deduction').innerText = "- " + formatMoney(tps);
            getEl('pb-net').innerText = formatMoney(ht + css);

            // MODIFICATION: Afficher uniquement la TPS dans la zone de reversement (demandé par l'utilisateur)
            // car dans ce mode, l'action fiscale majeure est le reversement TPS par le client.
            getEl('pb-reverse').innerText = formatMoney(tps);
        }

        // --- MOTEUR IMPRESSION ---
        function prepareAndPrint() {
            if (globalValues.ht === 0) {
                alert("Veuillez d'abord faire une simulation avec un montant valide.");
                return;
            }

            // 1. Transfert des infos de base
            getEl('pr-p-name').innerText = getEl('cfg-p-name').value;
            getEl('pr-p-nif').innerText = getEl('cfg-p-nif').value;
            getEl('pr-p-rccm').innerText = getEl('cfg-p-rccm').value;
            getEl('pr-p-contact').innerText = getEl('cfg-p-contact').value;

            getEl('pr-c-name').innerText = getEl('cfg-c-name').value || "..........................";
            getEl('pr-c-nif').innerText = getEl('cfg-c-nif').value || "";

            getEl('pr-inv-num').innerText = getEl('cfg-inv-num').value;

            // Formatage date locale
            const dateRaw = getEl('cfg-inv-date').valueAsDate;
            const dateStr = dateRaw ? dateRaw.toLocaleDateString('fr-FR') : new Date().toLocaleDateString('fr-FR');
            getEl('pr-inv-date').innerText = dateStr;

            getEl('pr-desc').innerText = getEl('cfg-desc').value;

            // 2. Remplissage des chiffres (Communs)
            getEl('pr-val-ht').innerText = formatMoney(globalValues.ht);
            getEl('pr-val-css').innerText = formatMoney(globalValues.css);
            getEl('pr-val-tps').innerText = formatMoney(globalValues.tps);
            getEl('pr-val-total').innerText = formatMoney(globalValues.total);

            // 3. Gestion Spécifique du Mode (A ou B)
            const printMode = getEl('cfg-mode-select').value;
            const rowRas = getEl('row-ras-label');
            const prRas = getEl('pr-val-ras');
            const prNet = getEl('pr-val-net');
            const legalTxt = getEl('pr-legal-mode');

            if (printMode === 'B') {
                // Mode Client Collecte (RAS)
                rowRas.style.display = 'table-row';
                prRas.innerText = "- " + formatMoney(globalValues.tps);

                const netPay = globalValues.ht + globalValues.css;
                prNet.innerText = formatMoney(netPay);
                getEl('pr-text-amount').innerText = formatMoney(netPay);

                legalTxt.innerText = "retenue à la source par le client (Article 168 CGI).";
            } else {
                // Mode Classique
                rowRas.style.display = 'none';
                prNet.innerText = formatMoney(globalValues.total);
                getEl('pr-text-amount').innerText = formatMoney(globalValues.total);

                legalTxt.innerText = "collectée et reversée par le prestataire.";
            }

            // 4. Lancement Impression
            window.print();
        }
    </script>

</body>

</html>
