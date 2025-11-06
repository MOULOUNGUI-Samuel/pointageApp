{{-- resources/views/pdf/rapport_pointages.blade.php --}}
@php
    // Helpers
    $fmtHM = fn (int $min) => sprintf('%d:%02d', intdiv($min,60), $min%60);
    $primary = '#00594D';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>{{ $meta['title'] ?? 'Rapport de Synthèse des Pointages' }}</title>
<style>
  /* ================== Charte ================== */
  @page { margin: 2cm; }
  :root{
    --font-family: "Times New Roman", Times, serif;
    --text-color: #000000;
    --primary-color: {{ $primary }};
  }
  body{
    font-family: var(--font-family);
    color: var(--text-color);
    line-height:1.5; font-size:11pt;
  }

  /* ================== En-tête / pied ================== */
  .page-header{ width:100%; border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:24px; }
  .header-table{ width:100%; border-collapse:collapse; table-layout:fixed; }
  .header-table td{ vertical-align:middle; }
  .ref{ text-align:right; font-size:10pt; font-style:italic; color:#555; }
  .logo{ max-width:250px; max-height:40px; height:auto; }

  .page-footer{ border-top:.5px solid #000; padding-top:8px; margin-top:28px; font-size:7pt; text-align:center; }

  h1{
    font-size:18pt; text-align:center; color:var(--primary-color);
    margin:0 0 .2em 0; font-weight:bold;
  }
  .subtitle{ text-align:center; font-style:italic; margin:0 0 1.6em 0; }

  /* ================== Grille 2 colonnes (fiable DomPDF) ================== */
  table.grid{ width:100%; border-collapse:separate; border-spacing:16px 16px; table-layout:fixed; }
  table.grid td{ width:50%; vertical-align:top; padding:0; page-break-inside:avoid; break-inside:avoid; }

  /* ================== Bloc employé ================== */
  .employee-block{
    border:1px solid var(--primary-color);
    border-left:5px solid var(--primary-color);
    background:#FAFAFA; border-radius:4px;
    padding:15px; font-size:10pt; page-break-inside:avoid; break-inside:avoid;
  }
  .block-header{ font-size:12pt; font-weight:bold; color:var(--primary-color); margin-bottom:5px; line-height:1.2; }
  .block-info{ font-size:9pt; color:#555; margin-bottom:10px; }
  .separator{ border-bottom:1px dashed #ccc; margin:8px 0; }

  /* ================== Tableau stats (icône/label/valeur) ================== */
  table.stats{ width:100%; border-collapse:collapse; }
  table.stats td{ padding:3px 0; }
  .c-ico{ width:18px; }
  .c-lbl{ }
  .c-val{ width:100px; text-align:right; font-weight:bold; font-size:11pt; }

  .stat-green{ color:var(--primary-color); }
  .stat-orange{ color:#FFA500; }
  .stat-red{ color:#CC0000; }
  .stat-blue{ color:#007bff; }
</style>
</head>
<body>

  {{-- En-tête --}}
  <header class="page-header">
    <table class="header-table">
      <tr>
        <td>
          @if(!empty($meta['logo_path']) && file_exists($meta['logo_path']))
            <img class="logo" src="{{ $meta['logo_path'] }}" alt="Logo">
          @endif
        </td>
        <td class="ref">
          Réf. : {{ $meta['reference'] ?? '' }}
        </td>
      </tr>
    </table>
  </header>

  {{-- Titre + sous-titre --}}
  <h1>{{ $meta['title'] ?? 'RAPPORT DE SYNTHÈSE DES POINTAGES' }}</h1>
  <p class="subtitle">{{ $meta['subtitle'] ?? '' }}</p>

  {{-- Grille 2 colonnes (équivalent col-6 / col-6) --}}
  <table class="grid">
    @foreach(array_chunk($employees ?? [], 2) as $pair)
      <tr>
        @foreach($pair as $e)
          <td>
            <div class="employee-block">
              <div class="block-header">
                {{ $e['nom'] ?? '' }} {{ isset($e['prenom']) ? mb_strtoupper($e['prenom'], 'UTF-8') : '' }}
              </div>
              <div class="block-info">
                Mat: {{ $e['matricule'] ?? '' }} | Fonction: {{ $e['fonction'] ?? '' }}
              </div>
              <div class="separator"></div>

              <table class="stats">
                <tr>
                  <td class="c-lbl">Total heures travaillées (Net)</td>
                  <td class="c-val stat-green">{{ $fmtHM((int)($e['heures_net_min'] ?? 0)) }}</td>
                </tr>
                <tr>
                  <td class="c-lbl stat-red">Retards cumulés (après {{ $meta['heure_debutTravail'] ?? '' }})</td>
                  <td class="c-val stat-red">{{ $fmtHM((int)($e['retard_cumule_min'] ?? 0)) }}</td>
                </tr>
                <tr>
                  <td class="c-lbl">Jours À l'heure</td>
                  <td class="c-val stat-green">{{ (int)($e['a_l_heure'] ?? 0) }} jours</td>
                </tr>
                <tr>
                  <td class="c-lbl stat-red">Jours En retard</td>
                  <td class="c-val stat-red">
                    {{ (int)($e['en_retard'] ?? 0) }} jours
                  </td>
                </tr>
                <tr>
                  <td class="c-lbl">Absences approuvées (congés/missions)</td>
                  <td class="c-val stat-blue">{{ (int)($e['absence_approuvee'] ?? 0) }} jours</td>
                </tr>
                <tr>
                  <td class="c-lbl stat-red">Absences injustifiées (non pointé)</td>
                  <td class="c-val stat-red">{{ (int)($e['absence_injustifiee'] ?? 0) }} jours</td>
                </tr>
              </table>
            </div>
          </td>
        @endforeach
        {{-- si impair, on complète la 2e colonne vide --}}
        @if(count($pair) === 1)
          <td></td>
        @endif
      </tr>
    @endforeach
  </table>

  {{-- Pied --}}
  <footer class="page-footer">
    <div>
      <strong>{{ $meta['company_name'] ?? '' }}</strong>
      – {{ $meta['company_addr'] ?? '' }}<br>
      {{ $meta['company_ctc'] ?? '' }}
    </div>
  </footer>

</body>
</html>
