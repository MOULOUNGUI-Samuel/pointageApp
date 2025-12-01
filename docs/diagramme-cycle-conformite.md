# Diagramme de Cycle de Conformité

## 🔄 Vue d'ensemble du cycle de vie

```
                         ┌──────────────────────────────────────┐
                         │     ITEM CRÉÉ (État initial)         │
                         │  periode_state: 'none'               │
                         │  Affichage: 🔲 Gris neutre           │
                         └──────────────┬───────────────────────┘
                                        │
                         [Admin définit période]
                                        │
                                        ▼
                         ┌──────────────────────────────────────┐
                         │   PÉRIODE ACTIVE SANS SOUMISSION     │
                         │  periode_state: 'active'             │
                         │  hasActivePeriode: true              │
                         │  lastSubmission: null                │
                         │  Affichage: 🔴 ROUGE "Non conforme"  │
                         │  Action: [Soumettre]                 │
                         └──────────────┬───────────────────────┘
                                        │
                       [Utilisateur soumet document]
                                        │
                                        ▼
                         ┌──────────────────────────────────────┐
                         │     SOUMISSION EN ATTENTE            │
                         │  periode_state: 'active'             │
                         │  lastSubmission.status: 'soumis'     │
                         │  Affichage: 🟡 JAUNE "En attente"    │
                         │  Action: [Modifier]                  │
                         └──────────────┬───────────────────────┘
                                        │
                           [Admin valide/rejette]
                                        │
                    ┌───────────────────┴───────────────────┐
                    │                                       │
                    ▼                                       ▼
     ┌──────────────────────────┐           ┌──────────────────────────┐
     │  SOUMISSION APPROUVÉE    │           │   SOUMISSION REJETÉE     │
     │  (Période toujours       │           │  periode_state: 'active' │
     │   active)                │           │  status: 'rejeté'        │
     │                          │           │  Affichage: 🔴 ROUGE     │
     │  status: 'approuvé'      │           │  "Rejeté"                │
     │  hasActivePeriode: true  │           │  Action: [Resoumettre]   │
     │  ⚠️ Affichage: 🔴 ROUGE  │           └──────────┬───────────────┘
     │  "Non conforme"          │                      │
     │  (car nouvelle soumission│              [Utilisateur corrige]
     │   attendue pour période) │                      │
     └──────────┬───────────────┘                      │
                │                                       │
                │                                       │
                │              ┌────────────────────────┘
                │              │
                │              ▼
                │   [Resoumettre] → Retour à "EN ATTENTE"
                │
       [Période expire naturellement]
                │
                ▼
     ┌──────────────────────────┐
     │  SOUMISSION APPROUVÉE    │
     │  (Période expirée)       │
     │                          │
     │  periode_state: 'expired'│
     │  hasActivePeriode: false │
     │  status: 'approuvé'      │
     │  Affichage: 🟢 VERT      │
     │  "Approuvé"              │
     └──────────┬───────────────┘
                │
   [Admin crée NOUVELLE période]
                │
                ▼
     ┌──────────────────────────┐
     │  NOUVELLE PÉRIODE ACTIVE │
     │  (Soumission obsolète)   │
     │                          │
     │  hasActivePeriode: true  │
     │  status: 'approuvé'      │
     │  (ancienne période)      │
     │  ⚠️ Affichage: 🔴 ROUGE  │
     │  "Non conforme"          │
     │  Action: [Soumettre]     │
     └──────────┬───────────────┘
                │
                └──────────→ RETOUR au début du cycle 🔄
```

---

## 🎯 Légende des couleurs

| Couleur | Code | Signification | État conforme ? |
|---------|------|---------------|-----------------|
| 🔴 **ROUGE** | `#dc3545` | Non conforme - Action requise | ❌ Non |
| 🟡 **JAUNE** | `#ffc107` | En attente de validation | ⏳ En cours |
| 🟢 **VERT** | `#28a745` | Approuvé et à jour | ✅ Oui |
| 🔲 **GRIS** | `#6c757d` | État neutre / Pas de période | — Neutre |

---

## 🔀 Transitions d'état détaillées

### Transition 1 : Création → Période active

```
AVANT                          APRÈS
┌─────────────────┐           ┌─────────────────┐
│  🔲 Aucune      │  Admin    │  🔴 Non         │
│     période     │ ────────> │     conforme    │
│                 │  définit  │                 │
│ [Désactivé]     │  période  │ [Soumettre]     │
└─────────────────┘           └─────────────────┘

Variables changées :
- periode_state: 'none' → 'active'
- hasActivePeriode: false → true
- borderColor: '#6c757d' → '#dc3545'
```

### Transition 2 : Non conforme → En attente

```
AVANT                          APRÈS
┌─────────────────┐           ┌─────────────────┐
│  🔴 Non         │  User     │  🟡 En          │
│     conforme    │ ────────> │     attente     │
│                 │  soumet   │                 │
│ [Soumettre]     │  document │ [Modifier]      │
└─────────────────┘           └─────────────────┘

Variables changées :
- lastSubmission: null → ConformitySubmission
- lastSubmission.status: null → 'soumis'
- borderColor: '#dc3545' → '#ffc107'
```

### Transition 3 : En attente → Approuvé (période active)

```
AVANT                          APRÈS
┌─────────────────┐           ┌─────────────────┐
│  🟡 En          │  Admin    │  🔴 Non         │
│     attente     │ ────────> │     conforme    │
│                 │  approuve │  ⚠️ SURPRISE    │
│ [Modifier]      │           │ [Soumettre]     │
└─────────────────┘           └─────────────────┘

⚠️ ATTENTION : Redevient ROUGE car période toujours active

Variables changées :
- lastSubmission.status: 'soumis' → 'approuvé'
- borderColor: '#ffc107' → '#dc3545' (!)
- conformiteLabel: 'En attente' → 'Non conforme' (!)

RAISON : hasActivePeriode = true + status = 'approuvé' = NON CONFORME
```

### Transition 4 : En attente → Rejeté

```
AVANT                          APRÈS
┌─────────────────┐           ┌─────────────────┐
│  🟡 En          │  Admin    │  🔴 Rejeté      │
│     attente     │ ────────> │                 │
│                 │  rejette  │                 │
│ [Modifier]      │           │ [Resoumettre]   │
└─────────────────┘           └─────────────────┘

Variables changées :
- lastSubmission.status: 'soumis' → 'rejeté'
- borderColor: '#ffc107' → '#dc3545'
- conformiteLabel: 'En attente' → 'Rejeté'
```

### Transition 5 : Approuvé (active) → Approuvé (expirée)

```
AVANT                          APRÈS
┌─────────────────┐           ┌─────────────────┐
│  🔴 Non         │  Temps    │  🟢 Approuvé    │
│     conforme    │ ────────> │                 │
│  (active)       │  passe    │                 │
│ [Soumettre]     │           │ [Désactivé]     │
└─────────────────┘           └─────────────────┘

Changement automatique (fin_periode < aujourd'hui)

Variables changées :
- periode_state: 'active' → 'expired'
- hasActivePeriode: true → false
- borderColor: '#dc3545' → '#28a745'
- conformiteLabel: 'Non conforme' → 'Approuvé'
```

### Transition 6 : Approuvé (expirée) → Non conforme (nouvelle période)

```
AVANT                          APRÈS
┌─────────────────┐           ┌─────────────────┐
│  🟢 Approuvé    │  Admin    │  🔴 Non         │
│  (expirée)      │ ────────> │     conforme    │
│                 │  crée     │                 │
│ [Désactivé]     │  période  │ [Soumettre]     │
└─────────────────┘           └─────────────────┘

⚠️ CAS CRITIQUE : Nouvelle période invalide ancienne approbation

Variables changées :
- periode_state: 'expired' → 'active'
- hasActivePeriode: false → true
- borderColor: '#28a745' → '#dc3545'
- conformiteLabel: 'Approuvé' → 'Non conforme'
- conformiteStatus: 'approuve' → 'non_conforme'
```

---

## 🎭 Scénarios visuels

### Scénario A : Cycle normal (tout fonctionne)

```
Jour 1      Jour 3           Jour 5        Jour 7         1 an après
  │           │                │             │                │
  ▼           ▼                ▼             ▼                ▼
┌───┐      ┌───┐           ┌───┐        ┌───┐           ┌───┐
│ 🔲│ ───> │ 🔴│  ──────>  │ 🟡│  ───>  │🔴 │  ──────>  │ 🟢│
└───┘      └───┘           └───┘        └───┘           └───┘
Créé     Période        Soumis      Approuvé        Période
         définie                    (active!)        expirée

Actions:
  [—]    [Soumettre]    [Modifier]  [Soumettre]    [—]
```

### Scénario B : Avec rejet et correction

```
Jour 1      Jour 3           Jour 5        Jour 6      Jour 8        1 an
  │           │                │             │           │             │
  ▼           ▼                ▼             ▼           ▼             ▼
┌───┐      ┌───┐           ┌───┐        ┌───┐      ┌───┐        ┌───┐
│ 🔲│ ───> │ 🔴│  ──────>  │ 🟡│  ───>  │ 🔴│ ──>  │🔴 │  ───>  │ 🟢│
└───┘      └───┘           └───┘        └───┘      └───┘        └───┘
Créé     Période        Soumis      Rejeté   Approuvé     Expirée
         définie                              (active)

Actions:
  [—]    [Soumettre]    [Modifier]  [Resou-  [Soumet]     [—]
                                     mettre]
```

### Scénario C : Cycle multi-périodes

```
2024                   2025                     2026
  │                      │                        │
  ▼                      ▼                        ▼
┌───┐  cycle 1      ┌───┐    Admin crée     ┌───┐  cycle 2
│🟢 │  ──────────>  │ 🔴│    période 2025   │🟢 │  ────────>
└───┘               └───┘  ──────────────>  └───┘
Approu.            Non conf.  User soumet   Approu.
période            nouvelle   + Admin       période
2024               période    approuve      2025

⚠️ Note : Même si approuvé en 2024, redevient NON CONFORME en 2025
```

---

## 📊 Matrice des actions disponibles

### Selon `periode_state` et `role`

| periode_state | Utilisateur standard | Admin (ValideAudit) |
|---------------|----------------------|---------------------|
| **none** | 🚫 Aucune action | ✅ [Définir période] |
| **active** | ✅ [Soumettre/Modifier/Resoumettre] | ✅ [Période] + [Valider] |
| **expired** | 🚫 "Période expirée" | ✅ [Nouvelle période] |
| **disabled** | 🚫 "Période clôturée" | ✅ [Réactiver] |
| **upcoming** | 🚫 "Pas encore ouverte" | ✅ [Modifier dates] |

### Selon `lastSubmission.status`

| Status | periode_state = 'active' | periode_state ≠ 'active' |
|--------|--------------------------|--------------------------|
| **null** | ✅ [Soumettre] | 🚫 Désactivé |
| **'soumis'** | ✅ [Modifier] | 🚫 Désactivé |
| **'rejeté'** | ✅ [Resoumettre] | 🚫 Désactivé |
| **'approuvé'** | ✅ [Soumettre] (nouvelle) | 🚫 Désactivé |

---

## 🎓 Points d'attention pour développeurs

### 1. Ne pas confondre les variables

```php
// DIFFÉRENTES variables avec des rôles différents :

$item->periode_state           // String: 'none', 'active', 'expired', etc.
                               // → Détermine si l'utilisateur PEUT agir

$item->hasActivePeriode        // Boolean: true/false
                               // → Détermine la CONFORMITÉ

$item->periodeActive           // PeriodeItem | null
                               // → Objet période active (pour dates)

$item->lastPeriode             // PeriodeItem | null
                               // → Dernière période (tous statuts)

$lastSub->status               // String: 'soumis', 'approuvé', 'rejeté'
                               // → Statut de la dernière soumission
```

### 2. La logique de calcul

```php
// TOUJOURS utiliser cette logique dans cet ordre :

// 1. Vérifier si soumission existe
if ($lastSub) {

    // 2. Vérifier la règle spéciale
    if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
        // → NON CONFORME (nouvelle période)
    } else {
        // → Suivre le statut de la soumission
    }

} else {

    // 3. Pas de soumission
    if ($item->hasActivePeriode) {
        // → NON CONFORME (pas de soumission)
    } else {
        // → NEUTRE (pas de période)
    }
}
```

### 3. Les pièges à éviter

❌ **ERREUR :** Penser qu'approuvé = toujours vert
```php
// FAUX :
if ($lastSub->status === 'approuvé') {
    $borderColor = '#28a745'; // Toujours vert
}
```

✅ **CORRECT :** Vérifier hasActivePeriode
```php
// JUSTE :
if ($lastSub->status === 'approuvé') {
    if ($item->hasActivePeriode) {
        $borderColor = '#dc3545'; // Rouge si période active
    } else {
        $borderColor = '#28a745'; // Vert si période expirée
    }
}
```

---

## 🔧 Debugging du cycle

### Commandes utiles

```bash
php artisan tinker

# Vérifier l'état d'un item
>>> $item = App\Models\Item::find('item-id');
>>> $item->periode_state;
=> "active"

>>> $item->hasActivePeriode;
=> true

>>> $item->lastSubmission?->status;
=> "approuvé"

# Comprendre pourquoi l'item est rouge alors qu'il est approuvé
>>> $item->hasActivePeriode && $item->lastSubmission->status === 'approuvé';
=> true  // C'est normal ! Nouvelle période nécessite nouvelle soumission
```

---

**Date de création :** 2025-01-25
**Version :** 1.0.0
**Statut :** ✅ Diagrammes complets
