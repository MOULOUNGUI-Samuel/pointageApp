<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class ConformityAnswer extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'conformity_answers';
    protected $fillable = [
        'submission_id','kind','value_text','value_json','file_path','position'
    ];
   
    protected $casts = [
        'value_json' => 'array',
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
    // Helpers
    public function selectedList(): ?string     { return data_get($this->value_json, 'selected'); }
    public function selectedMany(): array       { return (array) data_get($this->value_json, 'selected', []); }
    public function selectedLabel(): ?string    { return data_get($this->value_json, 'label'); }
    public function selectedLabels(): array     { return (array) data_get($this->value_json, 'labels', []); }
}
