<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['image', 'name', 'phone', 'division_id', 'position'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
