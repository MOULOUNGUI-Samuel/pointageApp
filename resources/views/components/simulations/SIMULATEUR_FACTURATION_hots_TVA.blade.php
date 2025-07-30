<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur Facturation - TPS & CSS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        header img {
            max-height: 80px;
        }
        .container {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            max-width: 650px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #003366;
        }
        .choice-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }
        .choice-card {
            flex: 1;
            padding: 15px;
            border: 2px solid #ccc;
            border-radius: 8px;
            background: #f0f0f0;
            cursor: pointer;
            text-align: center;
            position: relative;
        }
        .choice-card:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
        }
        .choice-card.selected {
            border-color: #556b2f;
            background: #e0e7da;
        }
        .sub-choice {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
        }
        .sub-choice label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #556b2f;
            color: white;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background: #3d4d1f;
        }
        .result {
            margin-top: 20px;
            border-top: 2px solid #003366;
            padding-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            text-align: right;
            padding: 8px;
        }
        th {
            background-color: #003366;
            color: #fff;
        }
        .note {
            font-size: 12px;
            color: #555;
            margin-top: 10px;
        }
        .tabs {
            display: flex;
            margin-top: 20px;
            cursor: pointer;
        }
        .tab {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: #eee;
            border: 1px solid #ccc;
        }
        .tab.active {
            background: #556b2f;
            color: #fff;
        }
        .tab-content {
            display: none;
            margin-top: 10px;
        }
        .tab-content.active {
            display: block;
        }
        .highlight-ttc {
            font-weight: bold;
            color: #800000;
        }
        .negative {
            color: red;
        }
        .positive {
            color: blue;
        }
    </style>
</head>
<body>
<header>
    <img src="https://ingenium-assurance.com/rapports/BFEV_logo_principal.jpg" alt="Logo Ingenium Assurance">
    <h2>Simulateur Facturation - TPS & CSS</h2>
</header>

<div class="container">
    <div class="choice-container">
        <div class="choice-card" id="card-netavantcss" data-tooltip="Utilisez cette option si vous connaissez le montant que vous voulez obtenir après la TPS mais avant la CSS." onclick="selectOption('netavantcss')">
            Montant souhaité avant CSS<br><small>(Net après TPS)</small>
        </div>
        <div class="choice-card" id="card-netclient" data-tooltip="Utilisez cette option si vous connaissez directement le montant que le client est prêt à payer." onclick="selectOption('netclient')">
            Net à payer par le client
        </div>
    </div>

    <div class="sub-choice">
        <label><input type="radio" name="tpsMode" value="client" checked> TPS retenue par le client</label>
        <label><input type="radio" name="tpsMode" value="prestataire"> TPS gérée par le prestataire</label>
    </div>

    <input type="number" id="amount" placeholder="Entrez le montant" min="0">

    <button onclick="calculate()">Calculer</button>

    <div class="tabs">
        <div class="tab active" onclick="switchTab('synthese')">Synthèse</div>
        <div class="tab" onclick="switchTab('detail')">Détail</div>
    </div>

    <div id="synthese" class="tab-content active">
        <div class="result" id="syntheseResult"></div>
    </div>

    <div id="detail" class="tab-content">
        <div class="result" id="detailResult"></div>
    </div>

    <p class="note">La TPS (9,5%) peut être retenue par le client ou gérée par le prestataire selon l'accord. La CSS (1%) est facturée au client mais reste à la charge du prestataire.</p>
</div>

<script>
let selectedOption = 'netavantcss';

function selectOption(option) {
    selectedOption = option;
    document.querySelectorAll('.choice-card').forEach(card => card.classList.remove('selected'));
    document.getElementById(`card-${option}`).classList.add('selected');
}

function formatMoney(value) {
    return Math.round(value).toLocaleString('fr-FR') + ' FCFA';
}

function calculate() {
    const tpsMode = document.querySelector('input[name="tpsMode"]:checked').value;
    const amount = parseFloat(document.getElementById('amount').value);

    if (isNaN(amount) || amount <= 0) {
        alert('Veuillez entrer un montant valide');
        return;
    }

    let ht = 0, css = 0, ttc = 0, tps = 0, netClient = 0;

    if (selectedOption === 'netavantcss') {
        ht = amount / 0.905;
    } else if (selectedOption === 'netclient') {
        ht = amount / 0.915;
    }

    css = ht * 0.01;
    ttc = ht + css;
    tps = ht * 0.095;

    if (tpsMode === 'client') {
        netClient = ttc - tps;
    } else {
        netClient = ttc;
    }

    document.getElementById('syntheseResult').innerHTML = `
        <h3>Net à payer par le client : ${formatMoney(netClient)}</h3>
        <p class="highlight-ttc">Total TTC facturé : ${formatMoney(ttc)}</p>
    `;

    let tpsClass = "positive";
    let tpsValue = formatMoney(tps);
    if (tpsMode === 'client') {
        tpsClass = "negative";
        tpsValue = "- " + formatMoney(tps);
    }

    document.getElementById('detailResult').innerHTML = `
        <table>
            <tr><th>Description</th><th>Montant (FCFA)</th></tr>
            <tr><td>Montant HT</td><td>${formatMoney(ht)}</td></tr>
            <tr><td>+ CSS 1%</td><td>${formatMoney(css)}</td></tr>
            <tr><td>Total TTC facturé au client</td><td>${formatMoney(ttc)}</td></tr>
            <tr><td>TPS 9,5%</td><td class="${tpsClass}">${tpsValue}</td></tr>
            <tr><td><strong>Net à payer par le client</strong></td><td><strong>${formatMoney(netClient)}</strong></td></tr>
        </table>
    `;
}

function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));

    document.querySelector(`.tab-content#${tab}`).classList.add('active');
    document.querySelector(`.tab:nth-child(${tab === 'synthese' ? 1 : 2})`).classList.add('active');
}
</script>
</body>
</html>
