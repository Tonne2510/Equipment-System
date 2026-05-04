<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'status'];

    public function brands()
    {
        return $this->hasMany(EquipmentBrand::class, 'category_id');
    }

    public function models()
    {
        return $this->hasMany(EquipmentModel::class, 'category_id');
    }

    public function items()
    {
        return $this->hasManyThrough(EquipmentItem::class, EquipmentModel::class, 'category_id', 'model_id');
    }
}
