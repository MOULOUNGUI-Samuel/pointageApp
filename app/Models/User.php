<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'entreprise_id',
        'nom',
        'prenom',
        'matricule',
        'password',
        'email',
        'email_professionnel',
        'date_naissance',
        'date_embauche',
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

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

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

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permissions_users');
    }
    // App\Models\User.php

    public function pointage()
    {
        return $this->hasMany(Pointage::class);
    }
}
