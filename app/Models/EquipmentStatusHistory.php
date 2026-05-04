<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentStatusHistory extends Model
{
    protected $table = 'equipment_status_history';
    protected $fillable = ['equipment_item_id', 'old_status', 'new_status', 'changed_by', 'reason'];

    public function equipment()
    {
        return $this->belongsTo(EquipmentItem::class, 'equipment_item_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
