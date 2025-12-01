# Guide d'utilisation du Debug Mode - Conformité

## 🎯 Objectif

Ce guide explique comment activer et utiliser le mode debug pour visualiser la logique de calcul des statuts de conformité.

---

## 🔧 Activation du debug mode

### Option 1 : Composant debug dans la vue (Recommandé)

Ajoutez le composant de debug dans la boucle des items :

```blade
<!-- resources/views/livewire/settings/compliance-board.blade.php -->

@forelse ($items as $item)
    <div class="col-12">
        <div class="card border-0 shadow-sm hover-shadow transition">
            <div class="card-body rounded" style="border-left: 10px solid {{ $borderColor }};">

                <!-- ... contenu existant de la carte ... -->

                <!-- AJOUT : Composant debug (uniquement en local) -->
                <x-debug-conformite :item="$item" />

            </div>
        </div>
    </div>
@empty
    <!-- ... -->
@endforelse
```

### Option 2 : Debug inline personnalisé

```blade
@if(app()->environment('local') && isset($item->debugConformiteStatus))
    @php
        $debug = $item->debugConformiteStatus;
    @endphp

    <div class="alert alert-info mt-2">
        <strong>🐛 DEBUG:</strong> {{ $debug['label'] }} ({{ $debug['reason'] }})
    </div>
@endif
```

---

## 📊 Informations affichées

Le composant debug affiche :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Status** | Code interne du statut | `non_conforme`, `approuve`, `soumis`, etc. |
| **Label** | Libellé affiché à l'utilisateur | "Non conforme", "Approuvé", etc. |
| **Couleur** | Couleur calculée | rouge, vert, jaune, gris |
| **Raison** | Explication de la décision | "Nouvelle période active, soumission approuvée obsolète" |
| **Période active** | Booléen `hasActivePeriode` | Oui / Non |
| **Dernière soumission** | Détails de la soumission | "approuvé (25/01/2025)" ou "Aucune" |

---

## 🎨 Exemple d'affichage visuel

### Cas 1 : Non conforme (nouvelle période)

```
┌─────────────────────────────────────────────┐
│ Item: Certificat d'assurance               │
│                                             │
│ [Période active: 01/01/2025 - 31/12/2025]  │
│ [Dernière soumission: Approuvé 15/12/2024] │
│                                             │
│ ╔═══════════════════════════════════════╗  │
│ ║ 🐛 DEBUG MODE                         ║  │
│ ║ Status: non_conforme                  ║  │
│ ║ Label: Non conforme                   ║  │
│ ║ Couleur: rouge                        ║  │
│ ║ Raison: Nouvelle période active,      ║  │
│ ║         soumission approuvée obsolète ║  │
│ ║ Période active: Oui                   ║  │
│ ║ Dernière soumission: approuvé         ║  │
│ ║                      (15/12/2024)     ║  │
│ ╚═══════════════════════════════════════╝  │
└─────────────────────────────────────────────┘
```

### Cas 2 : Approuvé (pas de nouvelle période)

```
┌─────────────────────────────────────────────┐
│ Item: Plan de formation                    │
│                                             │
│ [Période expirée: 01/01/2024 - 31/12/2024] │
│ [Dernière soumission: Approuvé 15/12/2024] │
│                                             │
│ ╔═══════════════════════════════════════╗  │
│ ║ 🐛 DEBUG MODE                         ║  │
│ ║ Status: approuvé                      ║  │
│ ║ Label: Approuvé                       ║  │
│ ║ Couleur: vert                         ║  │
│ ║ Raison: Statut de la dernière        ║  │
│ ║         soumission                    ║  │
│ ║ Période active: Non                   ║  │
│ ║ Dernière soumission: approuvé         ║  │
│ ║                      (15/12/2024)     ║  │
│ ╚═══════════════════════════════════════╝  │
└─────────────────────────────────────────────┘
```

---

## 🔍 Cas d'utilisation du debug

### 1. Vérification après modification de code

**Scénario :** Vous avez modifié la logique dans `ComplianceBoard.php`

**Action :**
1. Activer le composant debug dans la vue
2. Rafraîchir le Compliance Board
3. Vérifier que tous les items affichent les bonnes informations
4. Comparer "Label" affiché vs "Raison" calculée

**Validation :**
```
✅ Bordure rouge = Label "Non conforme"
✅ Bordure verte = Label "Approuvé"
✅ Bordure jaune = Label "En attente"
✅ Raison cohérente avec l'état visible
```

---

### 2. Investigation d'un bug signalé

**Scénario :** Un utilisateur signale qu'un item approuvé est devenu rouge

**Action :**
1. Reproduire le cas en local
2. Activer le debug mode
3. Consulter les informations affichées

**Exemple de diagnostic :**
```
Status: non_conforme
Label: Non conforme
Couleur: rouge
Raison: Nouvelle période active, soumission approuvée obsolète
Période active: Oui  ← CAUSE IDENTIFIÉE
Dernière soumission: approuvé (15/12/2024)
```

**Conclusion :** Comportement normal, une nouvelle période a été créée.

---

### 3. Test de tous les scénarios

**Scénario :** Vous voulez valider les 10 scénarios de test

**Action :**
1. Créer des données de test pour chaque scénario
2. Activer le debug mode
3. Parcourir tous les items
4. Vérifier que "Raison" correspond au scénario attendu

**Checklist :**
```
✅ Scénario 1: Raison = "Pas de période active, pas de soumission"
✅ Scénario 2: Raison = "Période active sans soumission"
✅ Scénario 3: Raison = "Statut de la dernière soumission" + Status = soumis
✅ Scénario 4: Raison = "Statut de la dernière soumission" + Status = rejeté
✅ Scénario 5: Raison = "Statut de la dernière soumission" + Status = approuvé
✅ Scénario 6: Raison = "Nouvelle période active, soumission approuvée obsolète"
```

---

## 🛠️ Commandes utiles

### Activer/Désactiver le debug

Le debug est automatiquement actif en environnement local. Pour forcer :

```bash
# Vérifier l'environnement
php artisan env

# Changer temporairement
APP_ENV=local php artisan serve
```

### Inspecter un item spécifique avec Tinker

```bash
php artisan tinker

>>> $item = App\Models\Item::find('item-id');
>>> $entrepriseId = 'entreprise-id';

# Vérifier période active
>>> App\Services\PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId);
=> true

# Récupérer période active
>>> $periode = App\Services\PeriodeItemChecker::getActivePeriod($item->id, $entrepriseId);
>>> $periode->debut_periode;
=> "2025-01-01"
>>> $periode->fin_periode;
=> "2025-12-31"

# Vérifier dernière soumission
>>> $lastSub = $item->lastSubmission()->where('entreprise_id', $entrepriseId)->first();
>>> $lastSub->status;
=> "approuvé"
>>> $lastSub->submitted_at;
=> "2024-12-15 10:30:00"

# Calculer manuellement le statut
>>> $debug = (new App\Livewire\Settings\ComplianceBoard)->calculateConformiteStatus($item);
// Note: Cette ligne ne fonctionnera pas directement car la méthode est privée
// Utilisez plutôt le composant debug dans la vue
```

---

## 📸 Captures d'écran attendues

### Debug activé

![Debug mode actif](screenshots/debug-mode-active.png)

Devrait montrer :
- Bordure colorée cohérente
- Badge de statut visible
- Bloc debug en bas avec toutes les infos
- Couleur du debug correspond à la couleur calculée

### Debug désactivé (production)

![Production sans debug](screenshots/production-no-debug.png)

Devrait montrer :
- Bordure colorée
- Badge de statut
- **AUCUN** bloc debug visible

---

## ⚠️ Précautions

### Ne PAS utiliser en production

Le composant debug est automatiquement masqué en production grâce à :

```blade
@if(app()->environment('local') && isset($item->debugConformiteStatus))
    <!-- Debug content -->
@endif
```

### Performance

Le calcul du debug status est effectué uniquement en environnement local :

```php
// ComplianceBoard.php
if (app()->environment('local')) {
    $item->debugConformiteStatus = $this->calculateConformiteStatus($item);
}
```

**Impact :** Aucun impact sur les performances en production.

---

## 🎓 Exercices pratiques

### Exercice 1 : Identifier le cas critique

1. Créer un item avec une soumission approuvée
2. Vérifier que la bordure est verte
3. Admin crée une nouvelle période active
4. Rafraîchir la page
5. Activer le debug
6. **Question :** Que montre le debug ? Pourquoi la bordure est rouge ?

**Réponse attendue :**
```
Status: non_conforme
Raison: Nouvelle période active, soumission approuvée obsolète
```

### Exercice 2 : Tracer le flux de décision

1. Prendre un item au hasard
2. Noter les valeurs :
   - `hasActivePeriode` : __________
   - `lastSubmission.status` : __________
3. Consulter le debug
4. Vérifier que la décision suit la logique documentée

### Exercice 3 : Créer un nouveau scénario

1. Imaginer un nouveau cas d'usage
2. Créer les données nécessaires
3. Prédire le résultat attendu
4. Activer le debug et vérifier
5. Si différent, comprendre pourquoi

---

## 📚 Ressources complémentaires

- **Logique complète** : `docs/logique-conformite-items.md`
- **Scénarios de test** : `docs/test-scenarios-conformite.md`
- **Changelog** : `docs/CHANGELOG-conformite.md`
- **Code source** : `app/Livewire/Settings/ComplianceBoard.php` (ligne 42)

---

## ❓ FAQ

**Q: Le debug ne s'affiche pas**
R: Vérifiez :
- Environnement local ? (`php artisan env`)
- Variable `$item->debugConformiteStatus` définie ?
- Composant inclus dans la vue ?

**Q: Puis-je personnaliser l'affichage du debug ?**
R: Oui, modifiez `resources/views/components/debug-conformite.blade.php`

**Q: Comment désactiver temporairement le debug ?**
R: Commentez la ligne dans la vue :
```blade
{{-- <x-debug-conformite :item="$item" /> --}}
```

**Q: Le debug ralentit-il l'application ?**
R: Non, il est actif uniquement en local et n'a aucun impact en production.

---

**Dernière mise à jour :** 2025-01-25
**Version :** 1.0
