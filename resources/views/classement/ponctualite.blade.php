<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>{{ $meta['title'] }} - {{ $meta['company'] }}</title>
  <style>
    body { font-family: 'Poppins', Arial, sans-serif; background:#f4f7f6; color:#333; margin:20px; }
    .header { text-align:center; margin-bottom:30px; }
    .header h1 { color:#005a9e; margin:0; }
    .header p  { color:#555; font-size:1.1em; }
    .table-container { overflow-x:auto; background:#fff; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.08); padding:10px; }
    table { border-collapse:collapse; width:100%; font-size:.9em; }
    thead tr { background:#005a9e; color:#fff; text-align:left; }
    th, td { padding:12px 15px; }
    th:first-child, .rank-cell { text-align:center; }
    .rank-cell { font-weight:700; font-size:1.2em; background:#e9ecef; }
    tbody tr { border-bottom:1px solid #ddd; }
    tbody tr:nth-of-type(even) { background:#f3f3f3; }
    tbody tr:last-of-type { border-bottom:2px solid #005a9e; }
    tbody tr:hover { background:#e1f5fe; }
    .metric-value { font-weight:600; padding:3px 8px; border-radius:4px; color:#fff; display:inline-block; min-width:35px; text-align:center; }
    .good { background:#28a745; }
    .warning { background:#ffc107; color:#333; }
    .bad { background:#dc3545; }
  </style>
</head>
<body>

  <div class="header">
    <h1>{{ $meta['title'] }}</h1>
    <p>{{ $meta['periode'] }}</p>
  </div>
  

  <div class="table-container">
    <table class="styled-table">
      <thead>
        <tr>
          <th>Classement</th>
          <th>Employé</th>
          <th>Fonction</th>
          <th>Retards Cumulés</th>
          <th>Jours en Retard</th>
          <th>Absences Injustifiées</th>
          <th>Heures Travaillées</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ranking as $i => $r)
          @php
            $clsRetardsCum = $r['retards_cumules'] <= 15 ? 'good' : ($r['retards_cumules'] <= 60 ? 'warning' : 'bad');
            $clsJoursRet   = $r['jours_en_retard'] == 0 ? 'good' : ($r['jours_en_retard'] <= 3 ? 'warning' : 'bad');
            $clsAbsInj     = $r['abs_injustifiees'] == 0 ? 'good' : 'bad';
          @endphp
          <tr>
            <td class="rank-cell">{{ $i+1 }}</td>
            <td>{{ $r['nom'] }} {{ mb_strtoupper($r['prenom'],'UTF-8') }}</td>
            <td>{{ $r['fonction'] }}</td>
            <td><span class="metric-value {{ $clsRetardsCum }}">{{ $r['retards_hhmm'] }}</span></td>
            <td><span class="metric-value {{ $clsJoursRet }}">{{ $r['jours_en_retard'] }}</span></td>
            <td><span class="metric-value {{ $clsAbsInj }}">{{ $r['abs_injustifiees'] }}</span></td>
            <td>{{ $r['heures_hhmm'] }}</td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;">Aucune donnée sur la période.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

</body>
</html>
