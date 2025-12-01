# Logique de Conformité des Items - Compliance Board

## Vue d'ensemble

Ce document décrit la logique complète d'affichage des statuts de conformité pour chaque item dans le tableau de bord de conformité.

## États possibles

### 1. **Non Conforme** (Rouge - `#dc3545`)
Un item est considéré comme **non conforme** dans ces cas :

- ✅ **Période active + Aucune soumission**
  - L'item a une période de validité active (`hasActivePeriode = true`)
  - Aucune soumission n'existe pour cet item
  - **Action requise** : L'utilisateur doit soumettre un document

- ✅ **Période active + Dernière soumission approuvée**
  - L'item a une période de validité active
  - La dernière soumission est au statut "approuvé"
  - **Raison** : Une nouvelle période a commencé, nécessitant une nouvelle soumission
  - **Action requise** : L'utilisateur doit resoumettre un document pour la nouvelle période

### 2. **En Attente** (Jaune - `#ffc107`)
- La dernière soumission est au statut "soumis"
- En attente de validation par un validateur

### 3. **Approuvé** (Vert - `#28a745`)
- La dernière soumission est au statut "approuvé"
- **ET** il n'y a pas de période active (ou la période active est la même que celle de la soumission)

### 4. **Rejeté** (Rouge - `#dc3545`)
- La dernière soumission est au statut "rejeté"
- L'utilisateur doit modifier et resoumettre

### 5. **Aucune Soumission** (Gris - `#6c757d`)
- Aucune soumission n'existe
- **ET** aucune période active n'est définie
- État neutre, aucune action requise pour le moment

## Logique d'affichage dans le code

### Calcul de la bordure gauche
```php
// Fichier: resources/views/livewire/settings/compliance-board.blade.php (lignes 293-320)

if ($lastSub) {
    // Règle métier : Si période active ET statut approuvé → NON CONFORME
    if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
        $borderColor = '#dc3545'; // rouge - non conforme
    } else {
        // Sinon, couleur selon le statut réel
        $borderColor = match ($lastSub->status) {
            'approuvé' => '#28a745', // vert
            'rejeté' => '#dc3545',   // rouge
            'soumis' => '#ffc107',   // jaune
            default => '#6c757d',    // gris
        };
    }
} else {
    // Pas de soumission
    if ($item->hasActivePeriode) {
        $borderColor = '#dc3545'; // rouge - non conforme
    } else {
        $borderColor = '#6c757d'; // gris - neutre
    }
}
```

### Calcul du badge de statut
```php
// Fichier: resources/views/livewire/settings/compliance-board.blade.php (lignes 410-468)

if ($lastSub) {
    // Règle métier principale
    if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
        $conformiteStatus = 'non_conforme';
        $conformiteLabel = 'Non conforme';
        $conformiteIcon = 'ti-circle-x';
        $conformiteBadgeClass = 'bg-danger-subtle text-danger';
    } else {
        // Switch selon le statut réel
        switch ($lastSub->status) {
            case 'approuvé':
                // vert - approuvé
            case 'rejeté':
                // rouge - rejeté
            case 'soumis':
                // jaune - en attente
        }
    }
} else {
    // Pas de soumission
    if ($item->hasActivePeriode) {
        // NON CONFORME
    } else {
        // État neutre
    }
}
```

## Vérification de la période active

### Service utilisé
```php
// Fichier: app/Services/PeriodeItemChecker.php

// Vérifie si l'item a une période de validité active (statut = '1')
PeriodeItemChecker::hasActivePeriod($itemId, $entrepriseId): bool

// Récupère la période active
PeriodeItemChecker::getActivePeriod($itemId, $entrepriseId): ?PeriodeItem
```

### Dans le Controller
```php
// Fichier: app/Livewire/Settings/ComplianceBoard.php (lignes 365-370)

foreach ($items as $item) {
    // Vérifier s'il y a une période de validité active (statut = 1)
    $item->hasActivePeriode = PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId);

    // Récupérer la période active (pour les dates)
    $item->activePeriode = PeriodeItemChecker::getActivePeriod($item->id, $entrepriseId);
}
```

## Affichage visuel

### Structure de la carte item

```
┌─────────────────────────────────────────────────┐
│ ║ [Bordure colorée]                            │
│ ║                                               │
│ ║ Nom de l'item                      [Type]    │
│ ║ Catégorie                                    │
│ ║                                               │
│ ║ [État de période]                            │
│ ║ [Statut de conformité]                       │
│ ║                                               │
│ ║                        [Actions]             │
└─────────────────────────────────────────────────┘
```

### Blocs d'affichage

1. **Bloc période** (lignes 354-407)
   - Active (vert)
   - Expirée (orange)
   - Clôturée (gris)
   - À venir (bleu)
   - Aucune période (orange)

2. **Bloc conformité** (lignes 470-499)
   - Avec soumission : Badge + date
   - Sans soumission : Bloc avec message

## Cas d'usage

### Scénario 1 : Nouvelle période définie
**État initial** : Item approuvé, période terminée
**Action** : Admin définit nouvelle période active
**Résultat** :
- `hasActivePeriode` = true
- Dernière soumission = "approuvé" (ancienne période)
- **Affichage** : Bordure rouge + Badge "Non conforme"

### Scénario 2 : Première soumission
**État initial** : Aucune soumission, période active
**Résultat** :
- `hasActivePeriode` = true
- `lastSub` = null
- **Affichage** : Bordure rouge + "Non conforme - Période active sans soumission"

### Scénario 3 : Soumission en attente
**État** : Soumission envoyée, en attente de validation
**Résultat** :
- `lastSub->status` = "soumis"
- **Affichage** : Bordure jaune + Badge "En attente"

### Scénario 4 : Soumission approuvée (même période)
**État** : Soumission approuvée pendant période active
**Résultat** :
- `lastSub->status` = "approuvé"
- `hasActivePeriode` = false (ou période correspond)
- **Affichage** : Bordure verte + Badge "Approuvé"

## Points clés à retenir

1. **La règle principale** : Une soumission approuvée devient non conforme si une nouvelle période active est créée
2. **Période active** : Déclencheur principal de non-conformité
3. **Cohérence** : Bordure ET badge doivent toujours être synchronisés
4. **Logique centralisée** : Éviter les conditions éparpillées dans la vue

## Maintenance

### Pour modifier la logique
1. Modifier le calcul de `$borderColor` (lignes 293-320)
2. Modifier le calcul de `$conformiteStatus` (lignes 410-468)
3. S'assurer que les deux restent cohérents
4. Mettre à jour ce document

### Tests à effectuer
- [ ] Item sans période, sans soumission → Gris neutre
- [ ] Item avec période active, sans soumission → Rouge non conforme
- [ ] Item avec période active, soumission approuvée → Rouge non conforme
- [ ] Item sans période active, soumission approuvée → Vert approuvé
- [ ] Item avec soumission en attente → Jaune
- [ ] Item avec soumission rejetée → Rouge rejeté
