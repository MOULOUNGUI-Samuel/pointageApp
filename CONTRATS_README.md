# Système de Gestion des Contrats

## Vue d'ensemble

Ce système complet de gestion des contrats permet de gérer efficacement les contrats des employés avec un historique complet, un versioning, et la possibilité de renouvellement manuel.

## Fonctionnalités

✅ **Gestion complète des contrats**
- Création, modification, visualisation de contrats
- Support de plusieurs types de contrats (CDI, CDD, Stage, Apprentissage, etc.)
- Gestion des états (Brouillon, Actif, Suspendu, Terminé, Résilié)

✅ **Renouvellement manuel**
- Interface dédiée pour renouveler un contrat
- Versioning automatique (v1, v2, v3, etc.)
- Lien entre l'ancien et le nouveau contrat

✅ **Historique complet**
- Traçabilité de toutes les modifications
- Enregistrement de qui a fait quoi et quand
- Comparaison avant/après pour chaque modification

✅ **Alertes et notifications**
- Contrats expirant bientôt
- Contrats expirés

✅ **Interface Livewire**
- Composants réactifs et modernes
- Filtres et recherche en temps réel
- Pagination

## Installation

### 1. Exécuter les migrations

```bash
cd c:\xampp\htdocs\Nedcore
php artisan migrate
```

Cela va créer :
- La table `contracts` pour stocker les contrats
- La table `contract_histories` pour l'historique
- Migrer automatiquement les données existantes depuis la table `users`

### 2. Vérification de la migration

Vérifiez que les données ont été migrées correctement :

```bash
php artisan tinker
```

Puis dans tinker :
```php
\App\Models\Contract::count(); // Nombre de contrats migrés
\App\Models\ContractHistory::count(); // Nombre d'entrées d'historique
```

### 3. Accéder au système

Connectez-vous à votre application et accédez à :
```
/contracts
```

## Structure des fichiers créés

### Migrations
- `database/migrations/2025_12_15_000001_create_contracts_table.php`
- `database/migrations/2025_12_15_000002_create_contract_histories_table.php`
- `database/migrations/2025_12_15_000003_migrate_contracts_from_users.php`

### Enums
- `app/Enums/ContractType.php` - Types de contrats
- `app/Enums/ContractStatus.php` - Statuts de contrats

### Modèles
- `app/Models/Contract.php` - Modèle principal
- `app/Models/ContractHistory.php` - Historique

### Service
- `app/Services/ContractService.php` - Logique métier centralisée

### Controller
- `app/Http/Controllers/ContractController.php`

### Composants Livewire
- `app/Livewire/ContractList.php` - Liste des contrats
- `app/Livewire/ContractForm.php` - Formulaire création/modification
- `app/Livewire/ContractRenewal.php` - Renouvellement
- `app/Livewire/ContractHistory.php` - Historique

### Vues
- `resources/views/contracts/index.blade.php` - Liste
- `resources/views/contracts/create.blade.php` - Création
- `resources/views/contracts/edit.blade.php` - Modification
- `resources/views/contracts/show.blade.php` - Détails
- `resources/views/contracts/renew.blade.php` - Renouvellement
- `resources/views/livewire/contract-list.blade.php`
- `resources/views/livewire/contract-form.blade.php`
- `resources/views/livewire/contract-renewal.blade.php`
- `resources/views/livewire/contract-history.blade.php`

## Utilisation

### Créer un nouveau contrat

1. Accédez à `/contracts`
2. Cliquez sur "Nouveau contrat"
3. Remplissez le formulaire
4. Enregistrez

### Modifier un contrat

1. Dans la liste des contrats, cliquez sur l'icône de modification
2. Seuls les contrats avec statut "Brouillon", "Actif" ou "Suspendu" peuvent être modifiés
3. Ajoutez un commentaire pour tracer la modification

### Renouveler un contrat

1. Affichez les détails d'un contrat
2. Cliquez sur "Renouveler"
3. Le formulaire est pré-rempli avec les données de l'ancien contrat
4. Ajustez les informations (dates, salaire, etc.)
5. Validez

Le système va :
- Terminer automatiquement l'ancien contrat
- Créer un nouveau contrat (version incrémentée)
- Lier les deux contrats
- Enregistrer tout dans l'historique

### Suspendre un contrat

1. Affichez les détails d'un contrat actif
2. Cliquez sur "Suspendre"
3. Ajoutez un commentaire expliquant la raison
4. Confirmez

### Résilier un contrat

1. Affichez les détails d'un contrat actif
2. Cliquez sur "Résilier"
3. Ajoutez obligatoirement un commentaire
4. Confirmez (action irréversible)

## Routes disponibles

```
GET  /contracts              - Liste des contrats
GET  /contracts/create       - Formulaire de création
GET  /contracts/{id}         - Détails d'un contrat
GET  /contracts/{id}/edit    - Formulaire de modification
GET  /contracts/{id}/renew   - Formulaire de renouvellement
POST /contracts/{id}/suspend - Suspendre un contrat
POST /contracts/{id}/reactivate - Réactiver un contrat
POST /contracts/{id}/terminate - Terminer un contrat
POST /contracts/{id}/terminate-early - Résilier un contrat
```

## API du Service

Le `ContractService` centralise toute la logique métier :

```php
use App\Services\ContractService;

$service = new ContractService();

// Créer un contrat
$contract = $service->createContract($data, $user);

// Mettre à jour un contrat
$contract = $service->updateContract($contract, $data, $user);

// Renouveler un contrat
$newContract = $service->renewContract($oldContract, $data, $user);

// Suspendre un contrat
$contract = $service->suspendContract($contract, $user, $comment);

// Réactiver un contrat
$contract = $service->reactivateContract($contract, $user, $comment);

// Terminer un contrat
$contract = $service->terminateContract($contract, $user, $comment);

// Résilier un contrat
$contract = $service->terminateContractEarly($contract, $user, $comment);

// Obtenir les contrats expirant bientôt
$contracts = $service->getExpiringContracts($entrepriseId, 30);

// Obtenir l'historique d'un contrat
$history = $service->getContractHistory($contract);
```

## Notes importantes

### Migration des données existantes

- Les données de contrat dans la table `users` ont été automatiquement migrées vers `contracts`
- Les colonnes `type_contrat`, `date_embauche`, `date_fin_contrat`, `salaire` dans `users` sont **conservées** pour compatibilité
- Vous pourrez les supprimer plus tard quand vous serez sûr que tout fonctionne

### Compatibilité

Le système est entièrement compatible avec votre architecture existante :
- Utilise Bootstrap pour le style
- Suit les conventions de votre codebase
- S'intègre avec votre système d'authentification
- Respecte le système d'entreprises multi-tenants

### Sécurité

- Toutes les routes sont protégées par le middleware `auth`
- Les modifications sont tracées avec l'utilisateur qui a effectué l'action
- Les données sensibles (salaires) ne sont accessibles qu'aux utilisateurs authentifiés

## Évolutions futures possibles

1. **Notifications automatiques**
   - Email X jours avant expiration du contrat
   - Notification au manager

2. **Documents**
   - Upload du contrat PDF
   - Génération automatique du document

3. **Signatures électroniques**
   - Intégration d'un système de signature

4. **Rapports**
   - Statistiques sur les types de contrats
   - Tableau de bord des renouvellements

5. **Validation workflow**
   - Approbation RH avant activation
   - Validation du manager

## Support

Pour toute question ou problème :
1. Vérifiez les logs Laravel : `storage/logs/laravel.log`
2. Testez avec `php artisan tinker`
3. Consultez la documentation Laravel Livewire : https://livewire.laravel.com/

## Maintenance

### Commandes utiles

```bash
# Vérifier les contrats expirés
php artisan tinker
>>> \App\Models\Contract::expired()->count();

# Vérifier les contrats expirant dans 30 jours
>>> \App\Models\Contract::expiringSoon(30)->count();

# Statistiques
>>> \App\Models\Contract::groupBy('statut')->selectRaw('statut, count(*) as total')->get();
```

Bon travail avec votre nouveau système de gestion des contrats ! 🎉
