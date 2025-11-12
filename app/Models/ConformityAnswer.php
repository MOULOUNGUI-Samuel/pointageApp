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

    /**
     * Retourne toujours un tableau des valeurs sélectionnées.
     * Compatible ancien format (array) et nouveau (string/radio).
     */
    public function selectedMany(): array
    {
        $sel = data_get($this->value_json, 'selected');
        if (is_array($sel)) {
            return array_values(array_filter($sel, fn($v) => filled($v)));
        }
        return filled($sel) ? [(string) $sel] : [];
    }

    /**
     * Retourne un tableau des labels sélectionnés.
     * Priorise value_json.labels (array) -> value_json.label (string) -> selectedMany().
     */
    public function selectedLabels(): array
    {
        $labels = data_get($this->value_json, 'labels');
        if (is_array($labels) && !empty($labels)) {
            return array_values($labels);
        }

        $label = data_get($this->value_json, 'label');
        if (filled($label)) {
            return [(string) $label];
        }

        // fallback : si pas de labels dédiés, on renvoie les valeurs brutes
        return $this->selectedMany();
    }

    /**
     * Nombre de sélections (utile si besoin ailleurs).
     */
    public function selectedCount(): int
    {
        return count($this->selectedMany());
    }
}
