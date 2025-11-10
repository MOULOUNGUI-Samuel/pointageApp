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