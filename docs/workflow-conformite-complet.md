# Workflow Complet - Système de Conformité

## 🎯 Vue d'ensemble

Ce document décrit le **workflow complet** du système de conformité, incluant les rôles, actions possibles, et transitions d'état.

---

## 👥 Rôles et permissions

### 1. **ValideAudit** (Administrateur/Validateur)
**Permissions :**
- ✅ Définir/modifier les périodes de validité
- ✅ Valider (approuver/rejeter) les soumissions
- ✅ Voir l'historique complet
- ❌ Ne peut PAS soumettre de documents

**Code détection :**
```php
auth()->user()->role?->nom === 'ValideAudit'
// OU
auth()->user()->role?->nom === 'SuperAdmin'
```

---

### 2. **Utilisateur Standard** (Soumissionnaire)
**Permissions :**
- ✅ Soumettre des documents pour items avec période active
- ✅ Modifier une soumission en attente
- ✅ Resoumettre après rejet
- ✅ Voir l'historique de ses soumissions
- ❌ Ne peut PAS définir de périodes
- ❌ Ne peut PAS valider

**Code détection :**
```php
auth()->user()->role?->nom !== 'ValideAudit'
&& auth()->user()->role?->nom !== 'SuperAdmin'
```

---

## 🔄 Workflow complet (Cycle de vie d'un item)

```
┌─────────────────────────────────────────────────────────────────┐
│                    ÉTAPE 1 : INITIALISATION                     │
└─────────────────────────────────────────────────────────────────┘

État initial : Item créé, AUCUNE période définie
├─ periode_state : 'none'
├─ hasActivePeriode : false
├─ lastSubmission : null
├─ Affichage : 🔲 Gris - "Aucune période définie"
└─ Actions disponibles :
    ├─ [ValideAudit] : Bouton "Période" (définir période)
    └─ [Utilisateur] : Bouton désactivé "Pas de période"

                        ↓
           [ValideAudit définit période]
                        ↓

┌─────────────────────────────────────────────────────────────────┐
│              ÉTAPE 2 : PÉRIODE ACTIVE CRÉÉE                     │
└─────────────────────────────────────────────────────────────────┘

État : Période active définie, AUCUNE soumission
├─ periode_state : 'active'
├─ hasActivePeriode : true
├─ lastSubmission : null
├─ Affichage : 🔴 ROUGE - "Non conforme"
├─ Message : "Période active sans soumission"
└─ Actions disponibles :
    ├─ [ValideAudit] : Bouton "Période" (modifier période)
    └─ [Utilisateur] : Bouton "Soumettre" (actif) ✅

                        ↓
           [Utilisateur soumet document]
                        ↓

┌─────────────────────────────────────────────────────────────────┐
│               ÉTAPE 3 : SOUMISSION EN ATTENTE                   │
└─────────────────────────────────────────────────────────────────┘

État : Soumission envoyée, en attente de validation
├─ periode_state : 'active'
├─ hasActivePeriode : true
├─ lastSubmission.status : 'soumis'
├─ Affichage : 🟡 JAUNE - "En attente"
├─ Badge : "En attente" avec date de soumission
└─ Actions disponibles :
    ├─ [ValideAudit] : Voir la soumission, valider/rejeter
    └─ [Utilisateur] : Bouton "Modifier" (modifier la soumission) ✅

                        ↓
           [ValideAudit prend décision]
                    ↙         ↘
              APPROUVÉ      REJETÉ

┌─────────────────────────────────────────────────────────────────┐
│        ÉTAPE 4a : SOUMISSION APPROUVÉE (Période active)         │
└─────────────────────────────────────────────────────────────────┘

État : Soumission approuvée PENDANT période active
├─ periode_state : 'active'
├─ hasActivePeriode : true
├─ lastSubmission.status : 'approuvé'
├─ ⚠️ RÈGLE SPÉCIALE : Approuvé + Période active = NON CONFORME
├─ Affichage : 🔴 ROUGE - "Non conforme"
├─ Badge : "Non conforme" (car nouvelle période nécessite nouvelle soumission)
└─ Actions disponibles :
    ├─ [ValideAudit] : Bouton "Période"
    └─ [Utilisateur] : Bouton "Soumettre" (pour nouvelle soumission)

POURQUOI ROUGE ? La période active indique qu'une NOUVELLE soumission
est attendue, même si l'ancienne était approuvée.

                        ↓
         [Période se termine naturellement]
                        ↓

┌─────────────────────────────────────────────────────────────────┐
│      ÉTAPE 4b : SOUMISSION APPROUVÉE (Période expirée)          │
└─────────────────────────────────────────────────────────────────┘

État : Soumission approuvée, période maintenant expirée
├─ periode_state : 'expired'
├─ hasActivePeriode : false
├─ lastSubmission.status : 'approuvé'
├─ Affichage : 🟢 VERT - "Approuvé"
├─ Badge : "Approuvé" avec date
└─ Actions disponibles :
    ├─ [ValideAudit] : Bouton "Période" (créer nouvelle période)
    └─ [Utilisateur] : Bouton désactivé "Période expirée"

                        ↓
        [ValideAudit crée NOUVELLE période]
                        ↓
              RETOUR à ÉTAPE 2 🔄

┌─────────────────────────────────────────────────────────────────┐
│                 ÉTAPE 5 : SOUMISSION REJETÉE                    │
└─────────────────────────────────────────────────────────────────┘

État : Soumission rejetée par ValideAudit
├─ periode_state : 'active'
├─ hasActivePeriode : true
├─ lastSubmission.status : 'rejeté'
├─ Affichage : 🔴 ROUGE - "Rejeté"
├─ Badge : "Rejeté" avec date et raison
└─ Actions disponibles :
    ├─ [ValideAudit] : Voir détails du rejet
    └─ [Utilisateur] : Bouton "Resoumettre" (corriger et renvoyer) ✅

                        ↓
           [Utilisateur resoumet]
                        ↓
              RETOUR à ÉTAPE 3 🔄
```

---

## 📊 Matrice de décision COMPLÈTE

### Avec prise en compte de `periode_state`

| periode_state | hasActivePeriode | lastSubmission | Bordure | Badge | Actions Utilisateur | Actions Admin |
|---------------|------------------|----------------|---------|-------|---------------------|---------------|
| **none** | ❌ false | ❌ null | 🔲 Gris | Aucune période | 🚫 Désactivé | ✅ Définir période |
| **active** | ✅ true | ❌ null | 🔴 Rouge | Non conforme | ✅ **Soumettre** | ✅ Modifier période |
| **active** | ✅ true | 🟡 soumis | 🟡 Jaune | En attente | ✅ **Modifier** | ✅ Valider/Rejeter |
| **active** | ✅ true | 🔴 rejeté | 🔴 Rouge | Rejeté | ✅ **Resoumettre** | ✅ Voir détails |
| **active** | ✅ true | 🟢 approuvé | 🔴 Rouge | **Non conforme** ⚠️ | ✅ **Soumettre** (nouvelle) | ✅ Modifier période |
| **expired** | ❌ false | 🟢 approuvé | 🟢 Vert | Approuvé | 🚫 Période expirée | ✅ Nouvelle période |
| **expired** | ❌ false | 🔴 rejeté | 🔴 Rouge | Rejeté | 🚫 Période expirée | ✅ Nouvelle période |
| **expired** | ❌ false | 🟡 soumis | 🟡 Jaune | En attente | 🚫 Période expirée | ✅ Valider |
| **disabled** | ❌ false | ❓ any | 🔲 Gris | Selon soumission | 🚫 Période clôturée | ✅ Réactiver |
| **upcoming** | ❌ false | ❓ any | 🔲 Gris | Selon soumission | 🚫 Pas encore ouverte | ✅ Modifier dates |

---

## ⚠️ Règle métier CRITIQUE (Cas spécial)

### Pourquoi "Approuvé + Période active = Non conforme" ?

```
SITUATION :
1. Admin définit période du 01/01/2025 au 31/12/2025
2. Utilisateur soumet document le 15/01/2025
3. Admin approuve la soumission le 16/01/2025
4. Item devient VERT (approuvé) ✅

CHANGEMENT :
5. Admin crée NOUVELLE période du 01/01/2026 au 31/12/2026
   (ou modifie la période active existante)

RÉSULTAT :
6. Item devient immédiatement ROUGE (non conforme) 🔴
7. hasActivePeriode = true (nouvelle période active)
8. lastSubmission.status = 'approuvé' (ancienne soumission)
9. Badge affiché : "Non conforme"
10. Raison : Nouvelle période nécessite nouvelle soumission

LOGIQUE :
Chaque période de validité nécessite sa propre soumission.
Une soumission approuvée pour une ancienne période ne couvre
pas automatiquement une nouvelle période.
```

**Code correspondant :**
```php
// Ligne 299-300 de compliance-board.blade.php
if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
    $borderColor = '#dc3545'; // Rouge - NON CONFORME
}
```

---

## 🔍 États de période (`periode_state`)

### Définition dans Item.php (lignes 131-145)

```php
public function getPeriodeStateAttribute(): string
{
    $p = $this->lastPeriode;
    if (!$p) return 'none';           // Aucune période définie

    if ($p->statut !== '1') return 'disabled'; // Période clôturée manuellement

    $today = now()->startOfDay();
    $debut = Carbon::parse($p->debut_periode)->startOfDay();
    $fin   = Carbon::parse($p->fin_periode)->endOfDay();

    if ($today->betweenIncluded($debut, $fin)) return 'active';   // Aujourd'hui dans période
    if ($today->lt($debut))                    return 'upcoming'; // Période future
    return 'expired';                                              // Période passée
}
```

### 1. **'none'** - Aucune période
- Aucune période n'a été définie pour cet item
- Admin doit créer une période
- Utilisateur ne peut pas soumettre

### 2. **'active'** - Période active
- `statut = '1'` (période ouverte)
- `debut_periode <= aujourd'hui <= fin_periode`
- Utilisateur PEUT soumettre des documents

### 3. **'expired'** - Période expirée
- `statut = '1'` (période ouverte)
- `fin_periode < aujourd'hui`
- La période est terminée naturellement
- Utilisateur ne peut plus soumettre

### 4. **'disabled'** - Période clôturée
- `statut != '1'` (période fermée manuellement)
- Admin a clôturé la période avant sa fin naturelle
- Utilisateur ne peut plus soumettre

### 5. **'upcoming'** - Période à venir
- `statut = '1'` (période ouverte)
- `debut_periode > aujourd'hui`
- La période n'a pas encore commencé
- Utilisateur ne peut pas encore soumettre

---

## 🎨 Affichage visuel selon `periode_state`

### Dans la vue (lignes 359-411)

```php
@if ($state === 'active')
    <!-- Bloc VERT : Période active -->
    <div class="bg-success bg-opacity-10 border border-success-subtle">
        <i class="ti ti-calendar-check text-success"></i>
        Période active : 01/01/2025 — 31/12/2025
    </div>

@elseif ($state === 'expired')
    <!-- Bloc ORANGE : Période expirée -->
    <div class="bg-warning bg-opacity-10 border border-warning-subtle">
        <i class="ti ti-alert-triangle text-warning"></i>
        Période expirée : 01/01/2024 — 31/12/2024
    </div>

@elseif ($state === 'disabled')
    <!-- Bloc GRIS : Période clôturée -->
    <div class="bg-secondary bg-opacity-10 border border-secondary-subtle">
        <i class="ti ti-lock text-secondary"></i>
        Période clôturée
    </div>

@elseif ($state === 'upcoming')
    <!-- Bloc BLEU : Période à venir -->
    <div class="bg-info bg-opacity-10 border border-info-subtle">
        <i class="ti ti-calendar-stats text-info"></i>
        Période à venir : 01/01/2026 — 31/12/2026
    </div>

@else
    <!-- Bloc ORANGE : Aucune période -->
    <div class="bg-warning bg-opacity-10 border border-warning-subtle">
        <i class="ti ti-alert-triangle text-warning"></i>
        Aucune période définie
    </div>
@endif
```

---

## 🔐 Actions selon rôle et état

### Conditions pour afficher "Soumettre" (Utilisateur)

```php
// Ligne 513 de compliance-board.blade.php
@if (auth()->user()->role?->nom !== 'ValideAudit' && auth()->user()->role?->nom !== 'SuperAdmin')
    @if ($state === 'active')  // Période active REQUISE

        @if ($lastSub && $lastSub->status === 'soumis')
            <!-- Bouton "Modifier" -->

        @elseif ($lastSub && $lastSub->status === 'rejeté')
            <!-- Bouton "Resoumettre" -->

        @else
            <!-- Bouton "Soumettre" -->
            <!-- Cas : pas de soumission OU soumission approuvée avec nouvelle période -->
        @endif

    @endif
@endif
```

### Conditions pour gérer période (Admin)

```php
// Ligne 548 de compliance-board.blade.php
@if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
    <!-- Bouton "Période" toujours disponible pour admin -->
    <!-- Peu importe l'état de la période -->
@endif
```

---

## 📈 Scénarios complets

### Scénario 1 : Premier cycle complet

**Jour 1 :**
- Admin crée item
- État : `periode_state = 'none'`, Affichage : 🔲 Gris

**Jour 2 :**
- Admin définit période : 01/06/2025 - 31/12/2025
- État : `periode_state = 'active'`, `hasActivePeriode = true`
- Affichage : 🔴 Rouge "Non conforme"
- Actions utilisateur : Bouton "Soumettre" actif

**Jour 3 :**
- Utilisateur soumet document
- État : `lastSubmission.status = 'soumis'`
- Affichage : 🟡 Jaune "En attente"
- Actions utilisateur : Bouton "Modifier" actif

**Jour 4 :**
- Admin approuve la soumission
- État : `lastSubmission.status = 'approuvé'`, `hasActivePeriode = true`
- ⚠️ Affichage : 🔴 Rouge "Non conforme" (car période toujours active)
- Actions utilisateur : Bouton "Soumettre" (pour nouvelle soumission si besoin)

**01/01/2026 (période expirée) :**
- État : `periode_state = 'expired'`, `hasActivePeriode = false`
- Affichage : 🟢 Vert "Approuvé"
- Actions utilisateur : Bouton désactivé "Période expirée"

---

### Scénario 2 : Cycle avec rejet

**État initial :**
- Période active, pas de soumission
- Affichage : 🔴 Rouge "Non conforme"

**Action :**
- Utilisateur soumet document incomplet
- Affichage : 🟡 Jaune "En attente"

**Décision admin :**
- Admin rejette avec raison : "Document illisible"
- Affichage : 🔴 Rouge "Rejeté"
- Actions utilisateur : Bouton "Resoumettre" actif

**Correction :**
- Utilisateur corrige et resoumet
- Affichage : 🟡 Jaune "En attente"

**Validation :**
- Admin approuve
- Affichage : 🔴 Rouge "Non conforme" (période toujours active)

---

### Scénario 3 : Nouvelle période après approbation

**État initial :**
- Période 2024 expirée, soumission approuvée
- Affichage : 🟢 Vert "Approuvé"

**Admin crée nouvelle période 2025 :**
- `hasActivePeriode` passe à `true`
- **Transition automatique** : 🟢 Vert → 🔴 Rouge
- Affichage : 🔴 Rouge "Non conforme"
- Raison : "Nouvelle période active, soumission approuvée obsolète"
- Actions utilisateur : Bouton "Soumettre" actif

**Utilisateur soumet pour 2025 :**
- Affichage : 🟡 Jaune "En attente"

**Admin approuve :**
- Affichage : 🔴 Rouge "Non conforme" (toujours période active)

**Période 2025 expire :**
- Affichage : 🟢 Vert "Approuvé"

---

## 🎯 Points clés à retenir

1. **`periode_state`** : Détermine si l'utilisateur PEUT agir
   - `active` → Actions disponibles
   - Autre → Actions désactivées (sauf pour admin)

2. **`hasActivePeriode`** : Détermine la CONFORMITÉ
   - `true` + Approuvé = Non conforme (nouvelle soumission requise)
   - `false` + Approuvé = Conforme

3. **Rôles :**
   - **Admin** : Gère périodes, valide soumissions
   - **Utilisateur** : Soumet documents pendant périodes actives

4. **Cycle de vie :**
   ```
   Aucune période → Période définie → Soumission → Validation
        ↑                                                ↓
        └────────── Nouvelle période ←──────────────────┘
   ```

5. **Règle d'or :**
   > Une période active + soumission approuvée = NON CONFORME
   > Car chaque période nécessite sa propre soumission

---

**Date de création :** 2025-01-25
**Version :** 2.0.0
**Statut :** ✅ Workflow complet documenté
