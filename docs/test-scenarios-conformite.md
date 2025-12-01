# Scénarios de Test - Affichage de Conformité

## Guide de test visuel pour validation

Ce document liste tous les scénarios à tester pour vérifier que l'affichage des statuts de conformité fonctionne correctement.

---

## Checklist des scénarios

### ✅ Scénario 1 : Item neutre
**Configuration :**
- ❌ Pas de période définie
- ❌ Aucune soumission

**Résultat attendu :**
- 🔲 Bordure : Gris (`#6c757d`)
- 🔲 Bloc période : "Aucune période définie" (orange)
- 🔲 Statut : "Aucune soumission" (gris clair, pointillé)
- 🔲 Actions : Bouton "Pas de période" désactivé

---

### ✅ Scénario 2 : Non conforme - Période active sans soumission
**Configuration :**
- ✅ Période active définie
- ❌ Aucune soumission

**Résultat attendu :**
- 🔴 Bordure : Rouge (`#dc3545`)
- 🟢 Bloc période : "Période active" (vert) avec dates
- 🔴 Statut : Bloc rouge avec "Non conforme" + "Période active sans soumission"
- 🔲 Actions : Bouton "Soumettre" actif

**Capture d'écran :** `/docs/screenshots/scenario-2.png`

---

### ✅ Scénario 3 : Soumission en attente
**Configuration :**
- ✅ Période active définie
- ✅ Dernière soumission : statut = "soumis"

**Résultat attendu :**
- 🟡 Bordure : Jaune (`#ffc107`)
- 🟢 Bloc période : "Période active" (vert)
- 🟡 Statut : Badge "En attente" avec icône hourglass + date de soumission
- 🔲 Actions : Bouton "Modifier" actif

---

### ✅ Scénario 4 : Soumission rejetée
**Configuration :**
- ✅ Période active définie
- ✅ Dernière soumission : statut = "rejeté"

**Résultat attendu :**
- 🔴 Bordure : Rouge (`#dc3545`)
- 🟢 Bloc période : "Période active" (vert)
- 🔴 Statut : Badge "Rejeté" avec icône X + date de soumission
- 🔲 Actions : Bouton "Resoumettre" actif

---

### ✅ Scénario 5 : Soumission approuvée (période correspondante)
**Configuration :**
- ❌ Pas de période active **OU** période active = période de la soumission
- ✅ Dernière soumission : statut = "approuvé"

**Résultat attendu :**
- 🟢 Bordure : Vert (`#28a745`)
- 🔲 Bloc période : Selon état (peut être "expirée", "aucune", etc.)
- 🟢 Statut : Badge "Approuvé" avec icône check + date de soumission
- 🔲 Actions : Pas de bouton de soumission (ou désactivé selon période)

---

### ✅ Scénario 6 : Non conforme - Nouvelle période après approbation (CAS CRITIQUE)
**Configuration :**
- ✅ Période active définie (nouvelle période)
- ✅ Dernière soumission : statut = "approuvé" (ancienne période)

**Résultat attendu :**
- 🔴 Bordure : Rouge (`#dc3545`)
- 🟢 Bloc période : "Période active" (vert) avec nouvelles dates
- 🔴 Statut : Badge "Non conforme" + date de l'ancienne soumission
- 🔲 Actions : Bouton "Soumettre" ou "Resoumettre" actif
- ⚠️ **Note importante** : Ce cas indique qu'une nouvelle période a commencé et que l'item nécessite une nouvelle soumission

---

### ✅ Scénario 7 : Période expirée avec soumission approuvée
**Configuration :**
- ⏰ Période expirée (fin < aujourd'hui)
- ✅ Dernière soumission : statut = "approuvé"

**Résultat attendu :**
- 🟢 Bordure : Vert (`#28a745`)
- 🟠 Bloc période : "Période expirée" (orange) avec dates
- 🟢 Statut : Badge "Approuvé" + date de soumission
- 🔲 Actions : Bouton "Période expirée" désactivé

---

### ✅ Scénario 8 : Période à venir
**Configuration :**
- 📅 Période à venir (début > aujourd'hui)
- ❓ Avec ou sans soumission

**Résultat attendu :**
- 🔲 Bordure : Selon dernière soumission
- 🔵 Bloc période : "Période à venir" (bleu) avec dates
- 🔲 Statut : Selon dernière soumission
- 🔲 Actions : Bouton "Pas encore ouverte" désactivé

---

### ✅ Scénario 9 : Période clôturée (statut = 0)
**Configuration :**
- 🔒 Période clôturée (statut = '0')
- ❓ Avec ou sans soumission

**Résultat attendu :**
- 🔲 Bordure : Selon dernière soumission
- ⚫ Bloc période : "Période clôturée" (gris) avec icône cadenas
- 🔲 Statut : Selon dernière soumission
- 🔲 Actions : Bouton "Période clôturée" désactivé

---

### ✅ Scénario 10 : Multiple soumissions (vérifier "dernière")
**Configuration :**
- ✅ Période active
- ✅ Plusieurs soumissions pour le même item
- ✅ Dernière = "soumis"
- ✅ Avant-dernière = "approuvé"

**Résultat attendu :**
- 🟡 Bordure : Jaune (selon dernière soumission)
- 🟢 Bloc période : "Période active"
- 🟡 Statut : Badge "En attente" + date de la **dernière** soumission
- 🔲 Actions : Bouton "Modifier"
- ⚠️ Vérifier que seule la dernière soumission est prise en compte

---

## Comment tester

### 1. Préparation des données de test
```sql
-- Créer des items avec différentes configurations
-- Voir fichier: tests/seeds/ComplianceBoardTestSeeder.php
```

### 2. Navigation
1. Se connecter avec un compte utilisateur standard
2. Accéder au Compliance Board
3. Vérifier visuellement chaque scénario

### 3. Validation visuelle
Pour chaque scénario, vérifier :
- ✅ Couleur de la bordure gauche
- ✅ Contenu et couleur du bloc période
- ✅ Contenu et couleur du badge/bloc de statut
- ✅ Boutons d'actions disponibles
- ✅ État des boutons (actif/désactivé)

### 4. Tests avec filtres
Tester chaque scénario avec :
- Filtre de domaine activé
- Filtre de catégorie activé
- Filtre de période (active, expirée, etc.)
- Filtre de statut soumission

---

## Captures d'écran de référence

### Créer les captures pour chaque scénario :

```bash
# Structure des dossiers
docs/
  screenshots/
    scenario-1-neutre.png
    scenario-2-non-conforme-sans-soumission.png
    scenario-3-en-attente.png
    scenario-4-rejete.png
    scenario-5-approuve.png
    scenario-6-non-conforme-nouvelle-periode.png  # LE PLUS IMPORTANT
    scenario-7-periode-expiree.png
    scenario-8-periode-a-venir.png
    scenario-9-periode-cloturee.png
    scenario-10-multiple-soumissions.png
```

---

## Bugs connus / Points d'attention

### ⚠️ Point critique à surveiller

**Scénario 6** : Lorsqu'une nouvelle période est créée alors qu'une soumission est déjà approuvée.

**Comportement attendu :**
- L'item doit passer de "Approuvé" (vert) à "Non conforme" (rouge)
- Ceci est intentionnel car chaque période nécessite une nouvelle soumission

**Comment vérifier :**
1. Item avec soumission approuvée (bordure verte)
2. Admin crée nouvelle période active
3. Rafraîchir la page
4. ✅ Vérifier : bordure devient rouge
5. ✅ Vérifier : badge affiche "Non conforme"
6. ✅ Vérifier : bouton "Soumettre" est actif

---

## Test de régression

Après toute modification de la logique, vérifier :

- [ ] Tous les scénarios 1-10 ci-dessus
- [ ] Cohérence bordure = badge (toujours synchronisés)
- [ ] Performance : pas de requêtes N+1
- [ ] Filtres fonctionnent correctement
- [ ] Pagination préserve les filtres
- [ ] Actions disponibles selon le rôle utilisateur

---

## Commandes utiles

### Réinitialiser les données de test
```bash
php artisan db:seed --class=ComplianceBoardTestSeeder
```

### Vérifier les logs
```bash
tail -f storage/logs/laravel.log | grep ComplianceBoard
```

### Inspecter une période active
```bash
php artisan tinker
>>> App\Services\PeriodeItemChecker::hasActivePeriod('item-id', 'entreprise-id')
>>> App\Services\PeriodeItemChecker::getActivePeriod('item-id', 'entreprise-id')
```

---

## Matrice de validation rapide

| Période Active | Dernière Soumission | Bordure | Badge | Action |
|----------------|---------------------|---------|-------|--------|
| ❌ Non | ❌ Aucune | 🔲 Gris | Aucune soumission | Désactivé |
| ✅ Oui | ❌ Aucune | 🔴 Rouge | Non conforme | Soumettre |
| ✅ Oui | 🟡 Soumis | 🟡 Jaune | En attente | Modifier |
| ✅ Oui | 🔴 Rejeté | 🔴 Rouge | Rejeté | Resoumettre |
| ✅ Oui | 🟢 Approuvé | 🔴 Rouge | Non conforme | Soumettre |
| ❌ Non | 🟢 Approuvé | 🟢 Vert | Approuvé | Désactivé |
| ❌ Non | 🔴 Rejeté | 🔴 Rouge | Rejeté | Désactivé |
| ❌ Non | 🟡 Soumis | 🟡 Jaune | En attente | Voir période |

---

**Date de création :** 2025-01-25
**Dernière mise à jour :** 2025-01-25
**Version :** 1.0
