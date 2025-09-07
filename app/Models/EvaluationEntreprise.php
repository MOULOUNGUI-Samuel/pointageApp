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
        'item_id',
        'url_document',
        'commentaire',
        'created_by',
        'revised_by',
        'cause_rejet',
        'statut',
    ];
}
