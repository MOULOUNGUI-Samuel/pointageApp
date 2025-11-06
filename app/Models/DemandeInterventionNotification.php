<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DemandeInterventionNotification extends Model
{
    use HasUuids;

    protected $table = 'demande_intervention_notifications';
    protected $fillable = [
        'demande_intervention_id','user_id','channel','mailable','status','transport_id','error','sent_at',
    ];

    public function demande() { return $this->belongsTo(Demande_intervention::class, 'demande_intervention_id'); }
    public function user()    { return $this->belongsTo(User::class); }
}
