<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GroupePermission extends Model
{
    //
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = $model->id ?? (string) Str::uuid());
    }

    protected $fillable = ['nom','module_id', 'description'];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'groupe_id');
    }
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
