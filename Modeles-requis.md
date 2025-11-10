# 📋 Modèles Eloquent à Vérifier/Adapter

Ce fichier liste les modèles Eloquent qui doivent exister dans votre application pour que le système de notifications fonctionne correctement.

## ✅ Modèles Requis

### 1. Entreprise

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    use HasUuids;

    protected $fillable = [
        'nom_entreprise',
        'code_entreprise',
        'logo',
        // ... autres champs
    ];

    /**
     * Relations
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'entreprise_id');
    }

    public function domaines()
    {
        return $this->belongsToMany(Domaine::class, 'entreprise_domaines')
            ->withTimestamps();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationConformite::class, 'entreprise_id');
    }
}
```

### 2. Domaine

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domaine extends Model
{
    use HasUuids;

    protected $fillable = [
        'nom_domaine',
        'description',
        'statut',
        'user_add_id',
        'user_update_id',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(CategorieDomaine::class, 'domaine_id');
    }

    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'entreprise_domaines')
            ->withTimestamps();
    }
}
```

### 3. CategorieDomaine

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieDomaine extends Model
{
    use HasUuids;

    protected $fillable = [
        'nom_categorie',
        'code_categorie',
        'description',
        'domaine_id',
        'statut',
        'user_add_id',
        'user_update_id',
    ];

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'categorie_domaine_id');
    }
}
```

### 4. Item

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasUuids;

    protected $fillable = [
        'nom_item',
        'description',
        'type',
        'categorie_domaine_id',
        'statut',
        'user_add_id',
        'user_update_id',
    ];

    public function categorieDomaine(): BelongsTo
    {
        return $this->belongsTo(CategorieDomaine::class, 'categorie_domaine_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(ItemOption::class, 'item_id');
    }

    public function periodes(): HasMany
    {
        return $this->hasMany(PeriodeItem::class, 'item_id');
    }

    public function soumissions(): HasMany
    {
        return $this->hasMany(ConformitySubmission::class, 'item_id');
    }
}
```

### 5. ItemOption

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemOption extends Model
{
    use HasUuids;

    protected $fillable = [
        'item_id',
        'kind',
        'label',
        'value',
        'position',
        'statut',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
```

### 6. PeriodeItem

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeItem extends Model
{
    use HasUuids;

    protected $table = 'periode_items';

    protected $fillable = [
        'item_id',
        'entreprise_id',
        'debut_periode',
        'fin_periode',
        'statut',
        'user_add_id',
        'user_update_id',
    ];

    protected $casts = [
        'debut_periode' => 'date',
        'fin_periode' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function soumissions(): HasMany
    {
        return $this->hasMany(ConformitySubmission::class, 'periode_item_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationConformite::class, 'periode_item_id');
    }

    /**
     * Vérifier si la période est active
     */
    public function estActive(): bool
    {
        return $this->statut == 1 
            && now()->between($this->debut_periode, $this->fin_periode);
    }

    /**
     * Vérifier si la période est expirée
     */
    public function estExpiree(): bool
    {
        return now()->gt($this->fin_periode);
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public function joursRestants(): int
    {
        return now()->diffInDays($this->fin_periode, false);
    }
}
```

### 7. ConformitySubmission

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConformitySubmission extends Model
{
    use HasUuids;

    protected $fillable = [
        'entreprise_id',
        'item_id',
        'periode_item_id',
        'submitted_by',
        'reviewed_by',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewer_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function periodeItem(): BelongsTo
    {
        return $this->belongsTo(PeriodeItem::class, 'periode_item_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ConformityAnswer::class, 'submission_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationConformite::class, 'soumission_id');
    }

    /**
     * Scopes
     */
    public function scopeEnAttente($query)
    {
        return $query->where('status', 'soumis');
    }

    public function scopeApprouvees($query)
    {
        return $query->where('status', 'approuvé');
    }

    public function scopeRejetees($query)
    {
        return $query->where('status', 'rejeté');
    }
}
```

### 8. ConformityAnswer

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConformityAnswer extends Model
{
    use HasUuids;

    protected $fillable = [
        'submission_id',
        'kind',
        'value_text',
        'value_json',
        'file_path',
        'item_option_id',
        'position',
    ];

    protected $casts = [
        'value_json' => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ConformitySubmission::class, 'submission_id');
    }

    public function itemOption(): BelongsTo
    {
        return $this->belongsTo(ItemOption::class, 'item_option_id');
    }
}
```

### 9. User (Modifications à ajouter)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasUuids, Notifiable;

    // ... votre configuration existante ...

    /**
     * Relations à ajouter
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationConformite::class, 'user_id');
    }

    /**
     * Méthodes utiles
     */
    public function estAdmin(): bool
    {
        return $this->super_admin || ($this->role && $this->role->nom === 'ValideAudit');
    }

    public function estSuperAdmin(): bool
    {
        return $this->super_admin == 1;
    }

    public function notificationsNonLues()
    {
        return $this->notifications()
            ->where('lue', false)
            ->orderBy('created_at', 'desc');
    }
}
```

### 10. Role

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasUuids;

    protected $fillable = [
        'nom',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
```

## ⚠️ Points Importants

### 1. Traits UUID
Tous les modèles utilisent `HasUuids` pour les identifiants UUID. Si vous n'utilisez pas d'UUID, adaptez en conséquence.

### 2. Relations
Vérifiez que toutes les relations sont bien définies dans vos modèles existants.

### 3. Rôles
Le système nécessite un rôle nommé **"ValideAudit"** pour les administrateurs qui valident les soumissions.

### 4. Champs Timestamp
Les modèles utilisent les champs `created_at` et `updated_at`. Vérifiez que `timestamps()` est bien dans vos migrations.

## 🔧 Vérification

Pour vérifier que tous vos modèles sont correctement configurés, exécutez :

```php
// Dans tinker
php artisan tinker

// Vérifier qu'un modèle existe
use App\Models\Entreprise;
Entreprise::first();

// Vérifier les relations
$entreprise = Entreprise::first();
$entreprise->users;
$entreprise->domaines;
$entreprise->notifications;
```

## 📝 Adaptation

Si vos modèles ont des noms différents ou une structure différente :
1. Modifiez les références dans `NotificationConformite.php`
2. Modifiez les méthodes dans `NotificationConformiteService.php`
3. Adaptez les relations en conséquence

---

**Note** : Ces exemples sont basés sur les migrations fournies. Adaptez-les selon votre structure existante.