<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $guard_name = 'web'; // cohérent avec config
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'entreprise_id',
        'openproject_api_token',
        'nom',
        'prenom',
        'matricule',
        'password',
        'email',
        'email_professionnel',
        'date_naissance',
        'date_embauche',
        'date_fin_contrat',
        'service_id',
        'role_id',
        'lieu_naissance',
        'nationalite',
        'numero_securite_sociale',
        'etat_civil',
        'nombre_enfant',
        'photo',
        'adresse',
        'adresse_complementaire',
        'code_postal',
        'superieur_hierarchique',
        'niveau_etude',
        'competence',
        'ville_id',
        'pays_id',
        'categorie_professionel_id',
        'type_contrat',
        'salaire',
        'salairebase',
        'mode_paiement',
        'iban',
        'bic',
        'titulaire_compte',
        'nom_banque',
        'nom_agence',
        'telephone',
        'telephone_professionnel',
        'fonction',
        'cv',
        'permis_conduire',
        'piece_identite',
        'diplome',
        'certificat_travail',
        'statu_user',
        'statut_vue_entreprise',
        'statut_vue_caisse',
        'super_admin',
        'statut',
        'nom_completaire',
        'lien_completaire',
        'contact_completaire',
        'formation_completaire',
        'commmentaire_completaire',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // Relations

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function categorieProfessionnelle()
    {
        return $this->belongsTo(CategorieProfessionnelle::class);
    }

    // App\Models\User.php

    public function pointage()
    {
        return $this->hasMany(Pointage::class);
    }
    public function demande_intervention()
    {
        return $this->hasMany(Demande_intervention::class);
    }
    public function domaines()
    {
        return $this->hasMany(Domaine::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function categorieDomaines()
    {
        return $this->hasMany(CategorieDomaine::class);
    }
    public function periodeItems()
    {
        return $this->hasMany(PeriodeItem::class);
    }
    // public function evaluationEntreprises()
    // {
    //     return $this->hasMany(EvaluationEntreprise::class);
    // }
    public function categorieDomaine()
    {
        return $this->hasMany(CategorieDomaine::class);
    }

    public function demandesInterventionRecues()
    {
        return $this->belongsToMany(\App\Models\Demande_intervention::class, 'demande_intervention_recipients')
            ->withPivot(['type', 'selected_at']);
    }
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

    public function notificationsConformite(): HasMany
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
