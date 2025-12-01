# 📚 Documentation - Système de Conformité

Bienvenue dans la documentation complète du système de gestion de conformité du Compliance Board.

---

## 🎯 Vue d'ensemble

Le système de conformité permet de gérer et suivre l'état de validité des documents/items d'une entreprise en fonction de périodes de validité définies par les administrateurs.

### Concept clé

**Un item est conforme** si :
- Il a une soumission approuvée pendant la période de validité active
- Ou il n'a pas de période active définie

**Un item est non conforme** si :
- Il a une période active mais aucune soumission
- Il a une période active mais la dernière soumission est obsolète (ancienne période)

---

## 📖 Documents disponibles

### 1. 🔄 [Workflow complet](workflow-conformite-complet.md) ⭐ **NOUVEAU**
**À lire en PREMIER - Document principal**

Documente le cycle de vie complet d'un item de conformité avec rôles et transitions.

**Contenu :**
- Vue d'ensemble du workflow complet
- Rôles et permissions (Admin vs Utilisateur)
- Cycle de vie détaillé en 5 étapes
- Matrice de décision COMPLÈTE avec `periode_state`
- États de période expliqués (`none`, `active`, `expired`, etc.)
- Scénarios complets (premier cycle, rejet, nouvelle période)
- **Règle métier CRITIQUE** : Pourquoi "Approuvé + Période active = Non conforme"

**Quand consulter :**
- 🎯 Pour comprendre le système COMPLET
- 🎯 Pour onboarding d'un nouveau développeur
- 🎯 Pour comprendre les rôles et permissions
- 🎯 Pour comprendre POURQUOI un item approuvé devient rouge

---

### 2. 📊 [Diagramme de cycle](diagramme-cycle-conformite.md) ⭐ **NOUVEAU**
**Représentation visuelle du workflow**

Diagrammes ASCII complets du cycle de vie et des transitions d'état.

**Contenu :**
- Diagramme du cycle de vie complet
- Légende des couleurs (Rouge, Jaune, Vert, Gris)
- Transitions d'état détaillées (6 transitions principales)
- Scénarios visuels (cycle normal, avec rejet, multi-périodes)
- Matrice des actions disponibles selon rôle
- Points d'attention pour développeurs
- Pièges à éviter
- Commandes de debugging

**Quand consulter :**
- 🎯 Pour visualiser le flux complet
- 🎯 Pour comprendre les transitions d'état
- 🎯 Pour débugger une transition inattendue
- 🎯 Pour formation visuelle des nouveaux développeurs

---

### 3. 📘 [Logique de conformité](logique-conformite-items.md)
**Détails techniques de la logique d'affichage**

Documente la logique complète d'affichage des statuts de conformité dans le code.

**Contenu :**
- Vue d'ensemble des états possibles (5 états)
- Règles métier détaillées avec exemples de code
- Service `PeriodeItemChecker` expliqué
- Structure d'affichage dans la vue
- Cas d'usage avec scénarios concrets
- Code source avec numéros de lignes

**Quand consulter :**
- Pour comprendre le code de calcul des statuts
- Avant de modifier la logique d'affichage
- Pour résoudre un bug d'affichage
- Pour comprendre les variables utilisées

---

### 4. 🧪 [Scénarios de test](test-scenarios-conformite.md)
**Pour la validation**

Liste complète des 10 scénarios à tester pour valider le fonctionnement.

**Contenu :**
- 10 scénarios de test détaillés
- Résultats attendus pour chaque scénario
- Guide de test visuel
- Checklist de validation
- Matrice de validation rapide
- Commandes utiles pour tester

**Quand consulter :**
- Après modification du code
- Pour reproduire un bug
- Pour validation avant déploiement
- Pour créer des données de test

---

### 5. 📝 [Changelog](CHANGELOG-conformite.md)
**Historique des modifications**

Historique complet du refactoring effectué le 2025-01-25.

**Contenu :**
- Problèmes identifiés
- Solutions apportées
- Fichiers modifiés
- Tests à effectuer
- Guide de déploiement
- Métriques de succès

**Quand consulter :**
- Pour comprendre pourquoi le code a été refactoré
- Pour connaître l'historique des changements
- Avant de faire un rollback
- Pour documentation projet

---

### 6. 🐛 [Guide debug](exemples-utilisation-debug.md)
**Outils de développement**

Guide complet d'utilisation du mode debug pour visualiser les calculs de conformité.

**Contenu :**
- Activation du debug mode
- Informations affichées
- Cas d'utilisation du debug
- Commandes utiles (Tinker, etc.)
- Exercices pratiques
- FAQ

**Quand consulter :**
- Pour débugger un problème d'affichage
- Pour vérifier les calculs de statut
- Pour investigation de bug
- Pour comprendre le flux de décision

---

## 🗺️ Cartographie du code

### Fichiers principaux

```
app/
├── Livewire/Settings/
│   └── ComplianceBoard.php              # Controller principal
│       ├── loadStats()                   # Stats globales
│       ├── loadDomaineStats()            # Stats par domaine
│       ├── calculateConformiteStatus()   # 🔍 Helper debug
│       └── render()                      # Logique d'affichage
│
├── Models/
│   ├── Item.php                          # Modèle Item
│   ├── PeriodeItem.php                   # Modèle Période
│   └── ConformitySubmission.php          # Modèle Soumission
│
└── Services/
    └── PeriodeItemChecker.php            # 🎯 Service vérification période
        ├── hasActivePeriod()             # Booléen: période active?
        ├── getActivePeriod()             # Récupère période active
        └── hasNewActivePeriod()          # Détecte nouvelle période

resources/views/
├── livewire/settings/
│   └── compliance-board.blade.php        # 🎨 Vue principale
│       ├── Calcul $borderColor          # Ligne 293-320
│       ├── Calcul $conformiteStatus     # Ligne 410-468
│       └── Affichage unifié             # Ligne 470-499
│
└── components/
    └── debug-conformite.blade.php        # 🐛 Composant debug

docs/
├── README-conformite.md                  # 📚 Ce fichier (index)
├── workflow-conformite-complet.md        # 🔄 ⭐ Workflow complet (NOUVEAU)
├── diagramme-cycle-conformite.md         # 📊 ⭐ Diagrammes visuels (NOUVEAU)
├── logique-conformite-items.md           # 📘 Documentation logique technique
├── test-scenarios-conformite.md          # 🧪 Scénarios test
├── CHANGELOG-conformite.md               # 📝 Historique
└── exemples-utilisation-debug.md         # 🐛 Guide debug
```

---

## 🚀 Quick Start

### Pour développeurs débutants sur le projet

**Étape 1 : Comprendre le système (NOUVEAU parcours)**
```bash
# Lire dans l'ordre (MISE À JOUR) :
1. docs/README-conformite.md                    # Ce fichier (index)
2. docs/workflow-conformite-complet.md          # ⭐ LE PLUS IMPORTANT - Workflow complet
3. docs/diagramme-cycle-conformite.md           # ⭐ Visualisation du cycle
4. docs/logique-conformite-items.md             # Détails techniques du code
5. docs/test-scenarios-conformite.md            # Les tests à effectuer
```

**Ordre recommandé :**
1. 🔄 **Workflow complet** → Comprendre QUOI et POURQUOI
2. 📊 **Diagrammes** → Visualiser le flux et transitions
3. 📘 **Logique technique** → Comprendre COMMENT dans le code
4. 🧪 **Tests** → Valider que tout fonctionne

**Étape 2 : Explorer le code**
```bash
# Ouvrir les fichiers dans cet ordre :
1. app/Services/PeriodeItemChecker.php                    # Service simple
2. app/Livewire/Settings/ComplianceBoard.php              # Controller
3. resources/views/livewire/settings/compliance-board.blade.php  # Vue
```

**Étape 3 : Tester en local**
```bash
# Activer le mode debug
# Modifier : resources/views/livewire/settings/compliance-board.blade.php
# Ajouter après la ligne 554 (dans la boucle @forelse) :

<x-debug-conformite :item="$item" />

# Rafraîchir le Compliance Board et observer
```

**Étape 4 : Reproduire les scénarios**
```bash
# Suivre le guide :
docs/test-scenarios-conformite.md

# Vérifier chaque scénario avec le debug actif
```

---

### Pour modifier la logique

**⚠️ ATTENTION : Modifications sensibles**

Si vous devez modifier la logique de conformité :

**Étape 1 : Comprendre l'existant**
- [ ] Lire `docs/logique-conformite-items.md` entièrement
- [ ] Identifier la règle métier à modifier
- [ ] Comprendre l'impact sur les 10 scénarios

**Étape 2 : Modifier le code**
Vous devez modifier **2 blocs de code en parallèle** :

```php
// resources/views/livewire/settings/compliance-board.blade.php

// 1. Calcul de la bordure (lignes 293-320)
@php
    if ($lastSub) {
        if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
            $borderColor = '#dc3545'; // MODIFIER ICI
        }
        // ...
    }
@endphp

// 2. Calcul du statut (lignes 410-468)
@php
    if ($lastSub) {
        if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
            $conformiteStatus = 'non_conforme'; // MODIFIER ICI AUSSI
            // ...
        }
    }
@endphp
```

**Étape 3 : Tester**
- [ ] Activer le debug mode
- [ ] Tester les 10 scénarios
- [ ] Vérifier la cohérence bordure = badge
- [ ] Valider les actions disponibles

**Étape 4 : Documenter**
- [ ] Mettre à jour `docs/logique-conformite-items.md`
- [ ] Ajouter un commit dans `docs/CHANGELOG-conformite.md`
- [ ] Mettre à jour `docs/test-scenarios-conformite.md` si nécessaire

---

## 🎓 Concepts clés

### 1. Période active (`hasActivePeriode`)

```php
// Service: app/Services/PeriodeItemChecker.php

// Vérifie si l'item a une période de validité active
PeriodeItemChecker::hasActivePeriod($itemId, $entrepriseId);
// Retourne: true/false

// Critères pour qu'une période soit "active":
// - statut = '1' (pas clôturée)
// - debut_periode <= aujourd'hui <= fin_periode
```

**Importance :** C'est le déclencheur principal de non-conformité.

---

### 2. Dernière soumission (`lastSubmission`)

```php
// Relation dans le modèle Item
$item->lastSubmission; // Dernière soumission pour cet item

// Statuts possibles:
// - 'soumis'   : En attente de validation
// - 'approuvé' : Validée par un admin
// - 'rejeté'   : Refusée, à corriger
```

**Importance :** Combinée avec `hasActivePeriode`, détermine l'état de conformité.

---

### 3. Règle métier principale

```
SI (période active ET dernière soumission = "approuvé")
ALORS
    → Item NON CONFORME (rouge)
    → Raison: Nouvelle période nécessite nouvelle soumission
SINON
    → Suivre le statut de la dernière soumission
FIN SI
```

**Pourquoi ?** Chaque période de validité nécessite une nouvelle soumission de document.

---

### 4. États de conformité

| Code | Label | Couleur | Signification |
|------|-------|---------|---------------|
| `non_conforme` | Non conforme | 🔴 Rouge | Action requise de l'utilisateur |
| `approuve` | Approuvé | 🟢 Vert | Tout est en ordre |
| `soumis` | En attente | 🟡 Jaune | En attente de validation admin |
| `rejete` | Rejeté | 🔴 Rouge | À corriger et resoumettre |
| `aucune` | Aucune soumission | 🔲 Gris | État neutre, pas de période active |

---

## 🔧 Maintenance

### Checklist de maintenance mensuelle

- [ ] Vérifier les logs d'erreur liés au Compliance Board
- [ ] Valider que les 10 scénarios fonctionnent toujours
- [ ] Vérifier les performances (pas de N+1 queries)
- [ ] Mettre à jour la documentation si changements
- [ ] Vérifier que le debug mode fonctionne en local

### Checklist avant déploiement

- [ ] Tests manuels des 10 scénarios
- [ ] Vérification cohérence bordure = badge
- [ ] Vérification que debug est désactivé en production
- [ ] Review du code par un autre développeur
- [ ] Backup de la base de données
- [ ] Plan de rollback préparé

---

## 📊 Métriques et monitoring

### Métriques importantes

```sql
-- Nombre d'items non conformes
SELECT COUNT(*) FROM items i
JOIN periode_items pi ON pi.item_id = i.id
WHERE pi.statut = '1'
  AND pi.debut_periode <= CURDATE()
  AND pi.fin_periode >= CURDATE()
  AND NOT EXISTS (
    SELECT 1 FROM conformity_submissions cs
    WHERE cs.item_id = i.id
      AND cs.status = 'approuvé'
      AND cs.submitted_at >= pi.debut_periode
  );

-- Taux de conformité par domaine
SELECT d.nom_domaine,
       COUNT(DISTINCT i.id) as total_items,
       COUNT(DISTINCT CASE WHEN ... THEN i.id END) as conformes
FROM domaines d
JOIN categorie_domaines cd ON cd.domaine_id = d.id
JOIN items i ON i.categorie_domaine_id = cd.id
-- ...
GROUP BY d.id;
```

---

## 🆘 Dépannage

### Problème : Item approuvé devient rouge

**Diagnostic :**
1. Activer le debug mode
2. Vérifier `hasActivePeriode` → probablement `true`
3. Vérifier `lastSubmission.status` → probablement `approuvé`

**Cause :** Règle métier normale - nouvelle période active créée

**Solution :** Comportement attendu, l'utilisateur doit resoumettre

---

### Problème : Bordure et badge incohérents

**Diagnostic :**
1. Vérifier les 2 blocs de calcul (lignes 293 et 410)
2. S'assurer qu'ils suivent la même logique

**Cause :** Modifications manuelles désynchronisées

**Solution :**
1. Restaurer depuis git
2. Remodifier les 2 blocs en parallèle
3. Tester tous les scénarios

---

### Problème : Debug ne s'affiche pas

**Diagnostic :**
```bash
php artisan env
# Vérifier : APP_ENV=local

php artisan tinker
>>> app()->environment('local')
# Devrait retourner: true
```

**Cause :** Environnement pas en local

**Solution :**
```bash
# Modifier .env
APP_ENV=local

# Ou forcer temporairement
APP_ENV=local php artisan serve
```

---

## 📞 Support et contribution

### Besoin d'aide ?

1. **Consulter la doc** : Lire les 4 documents dans l'ordre
2. **Activer le debug** : Utiliser le composant debug
3. **Consulter les logs** : `storage/logs/laravel.log`
4. **Tinker** : Inspecter les données manuellement

### Contribuer

Pour améliorer cette documentation :

1. Créer une branche : `git checkout -b doc/amelioration-conformite`
2. Modifier les fichiers dans `docs/`
3. Tester les exemples de code
4. Créer une PR avec description détaillée

---

## 📅 Historique

| Date | Version | Changement | Auteur |
|------|---------|-----------|--------|
| 2025-01-25 | 1.0.0 | Création documentation complète | Claude Code |
| 2025-01-25 | 1.0.0 | Refactoring logique conformité | Claude Code |

---

## 🔗 Liens utiles

### Documentation Laravel
- [Livewire Documentation](https://livewire.laravel.com/)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)

### Ressources internes
- Schéma de base de données : `docs/database-schema.md`
- Guide développeur : `docs/developer-guide.md`
- Architecture globale : `docs/architecture.md`

---

**📚 Cette documentation est maintenue par l'équipe de développement**
**📧 Questions ? Consultez d'abord les 4 documents listés ci-dessus**
**🐛 Bug ? Créer une issue avec les informations du debug mode**

---

**Dernière mise à jour :** 2025-01-25
**Version :** 1.0.0
**Statut :** ✅ Documentation complète et validée
