<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentModel extends Model
{
    protected $table = 'equipment_models';
    protected $fillable = ['category_id', 'brand_id', 'name', 'specifications', 'description', 'estimated_cost', 'status'];
    protected $casts = ['specifications' => 'array'];

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(EquipmentBrand::class, 'brand_id');
    }

    public function items()
    {
        return $this->hasMany(EquipmentItem::class, 'model_id');
    }
}
