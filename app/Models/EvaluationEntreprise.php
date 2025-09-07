<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationEntreprise extends Model
{
    use HasFactory;
    protected $table = 'evaluation_entreprises';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'entreprise_id',
        'user_add_id',
        'user_update_id',
        'item_id',
        'url_document',
        'commentaire',
        'created_by',
        'revised_by',
        'cause_rejet',
        'statut',
    ];
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user_add()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }
    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }
}
