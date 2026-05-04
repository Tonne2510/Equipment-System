<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentBrand extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'status'];

    public function models()
    {
        return $this->hasMany(EquipmentModel::class, 'brand_id');
    }

    public function items()
    {
        return $this->hasManyThrough(EquipmentItem::class, EquipmentModel::class, 'brand_id', 'model_id');
    }
}
