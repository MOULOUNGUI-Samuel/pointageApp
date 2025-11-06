{{-- resources/views/pdf/fiche_pointage.blade.php --}}
@php
    $primary = '#00594D';
    $safe = fn($v) => $v ?? '';
@endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>{{ $meta['title'] }}</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            font-size: 11pt;
            line-height: 1.5;
        }

        .page-header {
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 24px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo {
            max-width: 250px;
            max-height: 40px;
            height: auto;
        }

        .ref {
            text-align: right;
            font-size: 10pt;
            font-style: italic;
            color: #555;
        }

        h1 {
            font-size: 18pt;
            text-align: center;
            color: {{ $primary }};
            margin: 0 0 .2em 0;
            font-weight: bold;
        }

        .subtitle {
            text-align: center;
            font-style: italic;
            margin: 0 0 1.2em 0;
        }

        h2 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 1.6em 0 .6em;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
            font-size: 10pt;
        }

        th,
        td {
            padding: 4px 8px;
        }

        thead th {
            border-bottom: 1.5px solid #000;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .employee-info {
            border: 1px solid #ccc;
            padding: 12px;
            margin-bottom: 1.6em;
        }

        .employee-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-info-table td {
            padding: 4px 8px;
        }

        .employee-info strong {
            color: {{ $primary }};
        }

        .status-retard {
            color: #cc3300;
            font-weight: bold;
        }

        .status-absent {
            color: #8B0000;
            font-weight: bold;
        }

        .status-approuve {
            color: {{ $primary }};
            font-weight: bold;
        }

        .summary-total-row td {
            font-weight: bold;
            border-top: 1px solid {{ $primary }};
            border-bottom: 1px solid {{ $primary }};
        }

        .total-row td {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1.5px solid #000;
        }

        .page-footer {
            border-top: .5px solid #000;
            padding-top: 8px;
            margin-top: 24px;
            font-size: 7pt;
            text-align: center;
        }

        .signature-zone {
            width: 100%;
            margin-top: 2.2em;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .signature-table td {
            text-align: center;
            vertical-align: bottom;
            height: 80px;
        }

        .signature-line {
            border-top: 1px dashed #000;
            width: 200px;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    {{-- En-tête --}}
    <div class="page-header">
        <table class="header-table">
            <tr>
                <td>
                    @if (!empty($meta['logo_path']) && file_exists($meta['logo_path']))
                        <img class="logo" src="{{ $meta['logo_path'] }}" alt="Logo">
                    @endif
                </td>
                <td class="ref">Réf. : {{ $safe($meta['reference']) }}</td>
            </tr>
        </table>
    </div>

    {{-- Titre --}}
    <h1>FICHE DE SYNTHÈSE DE POINTAGE MENSUEL</h1>
    <p class="subtitle">{{ $safe($meta['subtitle']) }}</p>

    {{-- Informations employé --}}
    <h2>Informations Employé</h2>
    <div class="employee-info">
        <table class="employee-info-table">
            <tr>
                @php
                    $nomComplet = trim($user->nom . ' ' . mb_strtoupper($user->prenom, 'UTF-8'));
                @endphp
                <td>
                    <strong>NOM & PRÉNOM :</strong>
                    {{ mb_strimwidth($nomComplet, 0, 50, '...', 'UTF-8') }}
                </td>

                <td><strong>N° MATRICULE :</strong> {{ $safe($user->matricule) }}</td>
            </tr>
            @php
                $fonction = trim($safe($user->fonction));
            @endphp
            <tr>
                <td><strong>ENTITÉ :</strong> {{ $safe($meta['company_name']) }}</td>
                <td><strong>FONCTION :</strong> {{mb_strimwidth( $fonction , 0, 30, '...', 'UTF-8')}}</td>
            </tr>
        </table>
    </div>

    {{-- Récap statuts --}}
    <h2>Récapitulatif des Statuts</h2>
    <table>
        <thead>
            <tr>
                <th class="text-left">Statut</th>
                <th class="text-center">Nombre de Jours</th>
                <th class="text-left">Observations</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>À l'heure</td>
                <td class="text-center">{{ $summary['a_l_heure'] }}</td>
                <td>Présence conforme aux attentes.</td>
            </tr>
            <tr>
                <td class="status-retard">En retard</td>
                <td class="text-center">{{ $summary['en_retard'] }}</td>
                <td>Arrivée après l'heure prévue ({{ $safe($meta['heure_debut']) }}, tolérance incluse).</td>
            </tr>
            <tr>
                <td class="status-approuve">Absence approuvée</td>
                <td class="text-center">{{ $summary['absence_ok'] }}</td>
                <td>Congé/mission validé(e).</td>
            </tr>
            <tr>
                <td class="status-absent">Absent injustifié</td>
                <td class="text-center">{{ $summary['absence_inj'] }}</td>
                <td>Absence sans justification.</td>
            </tr>
            <tr class="total-row">
                <td class="text-right" colspan="1">TOTAL Jours Enregistrés</td>
                <td class="text-center">{{ $summary['total_jours'] }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- Synthèse horaire --}}
    <h2>Synthèse Horaire</h2>
    <table>
        <thead>
            <tr>
                <th class="text-left">Indicateur</th>
                <th class="text-right">Total (h:mm)</th>
                <th class="text-left">Base de Calcul</th>
            </tr>
        </thead>
        <tbody>
            <tr class="summary-total-row">
                <td>Heures travaillées nettes du mois</td>
                <td class="text-right">{{ $summary['total_net_hm'] }}</td>
                <td class="text-left">Chevauchement (jour) − pause.</td>
            </tr>
            <tr class="summary-total-row">
                <td class="status-retard">Total cumulé des retards</td>
                <td class="text-right status-retard">{{ $summary['total_late_hm'] }}</td>
                <td class="text-left">Après {{ $safe($meta['heure_debut']) }} (tolérance incluse).</td>
            </tr>
        </tbody>
    </table>

    {{-- Détail quotidien --}}
    <h2>Détail quotidien des mouvements</h2>
    <table>
        <thead>
            <tr>
                <th class="text-left">Date</th>
                <th class="text-center">Jour</th>
                <th class="text-center">Heure Arrivée</th>
                <th class="text-center">Heure Départ</th>
                <th class="text-center">Statut</th>
                <th class="text-right">Heures Net (h:mm)</th>
                <th class="text-right">Retard (m:ss)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $r)
                @php
                    $cls =
                        $r['statut'] === 'En retard'
                            ? 'status-retard'
                            : ($r['statut'] === 'Absent injustifié'
                                ? 'status-absent'
                                : ($r['statut'] === 'Absence approuvée'
                                    ? 'status-approuve'
                                    : ''));
                @endphp
                <tr>
                    <td class="text-left">{{ $r['date_txt'] }}</td>
                    <td class="text-center">{{ $r['jour_abr'] }}</td>
                    <td class="text-center">{{ $r['in'] }}</td>
                    <td class="text-center">{{ $r['out'] }}</td>
                    <td class="text-center {{ $cls }}">{{ $r['statut'] }}</td>
                    <td class="text-right">{{ $r['net_hm'] }}</td>
                    <td class="text-right">{{ $r['late_mss'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total reporté des Heures Net</td>
                <td class="text-right">{{ $summary['total_net_hm'] }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- Signatures --}}
    <div class="signature-zone">
        <table class="signature-table">
            <tr>
                <td>L'Employé(e)<br>(Signature précédée du nom)<br>
                    <div class="signature-line"></div>
                </td>
                <td>Le Manager/Service Paie<br>(Signature & Cachet)<br>
                    <div class="signature-line"></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Pied --}}
    <div class="page-footer">
        <strong>{{ $meta['company_name'] }}</strong> – {{ $meta['company_addr'] }}<br>
        {{ $meta['company_ctc'] }}
    </div>

</body>

</html>
