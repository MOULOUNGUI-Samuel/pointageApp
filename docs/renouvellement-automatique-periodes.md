# 🔄 Système de Renouvellement Automatique des Périodes

## 🎯 Vue d'ensemble

Ce système permet de renouveler automatiquement les périodes de validité des items lorsqu'elles arrivent à échéance, sans intervention manuelle de l'administrateur.

---

## 📊 Fonctionnement

### Principe

Quand une période de validité arrive à son terme (date de fin dépassée), le système peut automatiquement créer une nouvelle période avec la même durée si l'option **"Renouveler automatiquement"** est activée.

### Exemple

```
Période initiale  : 01/01/2025 → 31/01/2025 (1 mois)
Option activée    : ✅ Renouveler automatiquement (1 mois)

Le 01/02/2025, le système crée automatiquement :
Nouvelle période  : 01/02/2025 → 28/02/2025 (1 mois)
```

---

## 🗄️ Structure de la base de données

### Nouveaux champs dans `periode_items`

| Champ | Type | Description | Valeurs |
|-------|------|-------------|---------|
| `auto_renew` | `boolean` | Activer le renouvellement automatique | `true` / `false` (défaut: `false`) |
| `renew_duration_value` | `integer` | Durée du renouvellement (valeur numérique) | Ex: `1`, `3`, `6`, `12` |
| `renew_duration_unit` | `enum` | Unité de la durée | `days`, `months`, `years` |

### Migration

```php
// database/migrations/2025_12_01_134632_add_auto_renew_to_periode_items_table.php

$table->boolean('auto_renew')->default(false);
$table->integer('renew_duration_value')->nullable();
$table->enum('renew_duration_unit', ['days', 'months', 'years'])->default('months');
```

---

## ⚙️ Configuration d'une période à renouvellement automatique

### Dans l'interface admin

Lors de la création/modification d'une période :

1. ✅ Cocher **"Renouveler automatiquement après échéance"**
2. Définir la **durée** : `1`
3. Choisir l'**unité** : `Mois`
4. Enregistrer

### Exemple de configuration

```
Item                 : Certificat d'assurance
Période actuelle     : 01/01/2025 → 31/12/2025
Auto-renouvellement  : ✅ Oui
Durée renouvellement : 1 an

→ Le 01/01/2026, le système créera automatiquement :
   Nouvelle période  : 01/01/2026 → 31/12/2026
```

---

## 🤖 Commande Artisan

### Commande principale

```bash
php artisan periodes:renew-expired
```

**Ce qu'elle fait :**
1. Recherche toutes les périodes avec `auto_renew = true`
2. Filtre celles dont la `fin_periode < aujourd'hui`
3. Pour chacune :
   - Clôture l'ancienne période (`statut = 0`)
   - Crée une nouvelle période avec les mêmes paramètres
   - Conserve l'option `auto_renew` pour le prochain cycle

### Options

```bash
# Mode test (affiche ce qui serait fait sans l'exécuter)
php artisan periodes:renew-expired --dry-run
```

### Exemple de sortie

```
🔄 Recherche des périodes expirées avec renouvellement automatique...
📋 3 période(s) à renouveler trouvée(s).

   ✅ Période renouvelée: Certificat d'assurance
      Nouvelle période: 01/01/2026 → 31/12/2026

   ✅ Période renouvelée: Plan de formation
      Nouvelle période: 01/02/2026 → 28/02/2026

   ✅ Période renouvelée: Audit de sécurité
      Nouvelle période: 01/03/2026 → 31/03/2026

✅ Résumé: 3 période(s) renouvelée(s), 0 erreur(s)
```

---

## ⏰ Planification automatique (Scheduler)

### Configuration

La commande s'exécute **automatiquement tous les jours à 01h00** via le Laravel Scheduler.

**Fichier :** `app/Console/Kernel.php`

```php
$schedule->command('periodes:renew-expired')
    ->dailyAt('01:00')
    ->timezone('Africa/Libreville')
    ->emailOutputOnFailure(config('mail.admin_email'));
```

### Activation du scheduler

**Sur le serveur de production**, ajoutez cette ligne au crontab :

```bash
* * * * * cd /path/to/nedcore && php artisan schedule:run >> /dev/null 2>&1
```

**En développement local**, lancez :

```bash
php artisan schedule:work
```

---

## 📈 Logique de renouvellement

### Algorithme

```
POUR chaque période avec auto_renew = true ET fin_periode < aujourd'hui :

    1. Calculer nouvelle date de début = ancienne fin_periode + 1 jour
    2. Calculer nouvelle date de fin = début + durée
    3. Clôturer ancienne période (statut = 0)
    4. Créer nouvelle période avec :
       - debut_periode = nouvelle date de début
       - fin_periode = nouvelle date de fin
       - statut = '1'
       - auto_renew = true (conservation)
       - même durée de renouvellement
    5. Logger l'opération
```

### Calcul de la nouvelle date de fin

```php
private function calculateNewEndDate(Carbon $startDate, int $value, string $unit): Carbon
{
    return match ($unit) {
        'days' => $startDate->copy()->addDays($value),
        'months' => $startDate->copy()->addMonths($value),
        'years' => $startDate->copy()->addYears($value),
        default => $startDate->copy()->addMonths($value),
    };
}
```

---

## 🔍 Cas d'usage

### Cas 1 : Certificat annuel

```
Besoin : Renouveler le certificat d'assurance tous les ans

Configuration :
- auto_renew = true
- renew_duration_value = 1
- renew_duration_unit = years

Timeline :
01/01/2025 → 31/12/2025 (période 1) → auto-renouvelée
01/01/2026 → 31/12/2026 (période 2) → auto-renouvelée
01/01/2027 → 31/12/2027 (période 3) → ...
```

### Cas 2 : Audit trimestriel

```
Besoin : Effectuer un audit de sécurité tous les 3 mois

Configuration :
- auto_renew = true
- renew_duration_value = 3
- renew_duration_unit = months

Timeline :
01/01/2025 → 31/03/2025 (Q1) → auto-renouvelée
01/04/2025 → 30/06/2025 (Q2) → auto-renouvelée
01/07/2025 → 30/09/2025 (Q3) → ...
```

### Cas 3 : Formation mensuelle

```
Besoin : Rappel de formation tous les mois

Configuration :
- auto_renew = true
- renew_duration_value = 1
- renew_duration_unit = months

Timeline :
01/01/2025 → 31/01/2025
01/02/2025 → 28/02/2025
01/03/2025 → 31/03/2025
...
```

---

## 🎨 Interface utilisateur (à implémenter)

### Dans le formulaire de période

```blade
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox"
           wire:model="auto_renew" id="autoRenewCheck">
    <label class="form-check-label" for="autoRenewCheck">
        🔄 Renouveler automatiquement cette période après échéance
    </label>
</div>

@if($auto_renew)
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Durée du renouvellement</label>
            <input type="number" class="form-control"
                   wire:model="renew_duration_value"
                   min="1" placeholder="Ex: 1, 3, 6, 12">
        </div>
        <div class="col-md-6">
            <label class="form-label">Unité</label>
            <select class="form-select" wire:model="renew_duration_unit">
                <option value="days">Jours</option>
                <option value="months" selected>Mois</option>
                <option value="years">Années</option>
            </select>
        </div>
    </div>
@endif
```

---

## 📋 Logs et monitoring

### Logs d'exécution

Les renouvellements sont loggés dans `storage/logs/laravel.log` :

```
[2025-12-01 01:00:15] local.INFO: [RenewExpiredPeriodes] Période renouvelée {
    "item_id": "xxx-xxx",
    "item_nom": "Certificat d'assurance",
    "entreprise_id": "yyy-yyy",
    "old_periode_id": "aaa-aaa",
    "new_periode_id": "bbb-bbb",
    "old_dates": "2025-01-01 → 2025-12-31",
    "new_dates": "2026-01-01 → 2026-12-31"
}
```

### Erreurs

En cas d'erreur, l'admin reçoit un email et l'erreur est loggée :

```
[2025-12-01 01:00:15] local.ERROR: [RenewExpiredPeriodes] Erreur renouvellement {
    "periode_id": "xxx-xxx",
    "error": "...",
    "trace": "..."
}
```

---

## 🛠️ Maintenance

### Vérifier les périodes avec auto-renew

```sql
SELECT i.nom_item, pi.debut_periode, pi.fin_periode,
       pi.auto_renew, pi.renew_duration_value, pi.renew_duration_unit
FROM periode_items pi
JOIN items i ON i.id = pi.item_id
WHERE pi.auto_renew = true
  AND pi.statut = '1'
ORDER BY pi.fin_periode ASC;
```

### Désactiver temporairement le renouvellement

```sql
-- Pour un item spécifique
UPDATE periode_items
SET auto_renew = false
WHERE item_id = 'xxx-xxx' AND statut = '1';

-- Pour tous les items
UPDATE periode_items SET auto_renew = false;
```

### Tester manuellement

```bash
# Simuler (ne fait rien, juste affiche)
php artisan periodes:renew-expired --dry-run

# Exécuter réellement
php artisan periodes:renew-expired
```

---

## ⚠️ Points d'attention

### 1. Pas de renouvellement si désactivé

Si `auto_renew = false` ou `renew_duration_value = null`, la période **ne sera PAS renouvelée**.

### 2. Périodes clôturées manuellement

Si une période a été clôturée manuellement (`statut = 0`) **avant** sa date de fin, elle **ne sera PAS renouvelée**.

### 3. Soumissions et renouvellement

Quand une période est renouvelée :
- L'ancienne période est clôturée
- Les soumissions approuvées restent liées à l'ancienne période
- L'item redevient **"Non conforme"** (nouvelle période sans soumission)
- L'utilisateur doit **resoumettre** un document pour la nouvelle période

### 4. Notifications

⚠️ **À implémenter** : Système de notification pour avertir les utilisateurs qu'une nouvelle période a été créée automatiquement.

---

## 🚀 Améliorations futures

### 1. Notifications automatiques

Envoyer un email/notification quand :
- Une période est sur le point d'expirer (J-7, J-3, J-1)
- Une période a été renouvelée automatiquement
- Une période n'a pas pu être renouvelée (erreur)

### 2. Tableau de bord dédié

Interface admin pour :
- Voir toutes les périodes avec auto-renew
- Voir l'historique des renouvellements
- Activer/désactiver en masse
- Modifier les durées de renouvellement

### 3. Statistiques

- Nombre de renouvellements par mois
- Items avec le plus de renouvellements
- Taux de conformité après renouvellement

---

## 📚 Références

- **Commande** : `app/Console/Commands/RenewExpiredPeriodes.php`
- **Scheduler** : `app/Console/Kernel.php`
- **Migration** : `database/migrations/2025_12_01_134632_add_auto_renew_to_periode_items_table.php`
- **Modèle** : `app/Models/PeriodeItem.php`

---

**Version :** 1.0.0
**Date de création :** 2025-12-01
**Auteur :** System Nedcore
