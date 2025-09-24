<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ConformitySubmission extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'conformity_submissions';
    protected $fillable = [
        'entreprise_id','item_id','periode_item_id',
        'submitted_by','submitted_at','status',
        'reviewed_by','reviewed_at','reviewer_notes'
    ];
    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];
    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
    public function periode(): BelongsTo { return $this->belongsTo(PeriodeItem::class, 'periode_item_id'); }
    public function submitter(): BelongsTo { return $this->belongsTo(User::class, 'submitted_by'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function answers(): HasMany { return $this->hasMany(ConformityAnswer::class, 'submission_id'); }

    public function isFinal(): bool { return in_array($this->status, ['approuvé','rejeté'], true); }
}

