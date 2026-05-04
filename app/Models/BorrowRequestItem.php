<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequestItem extends Model
{
    protected $table = 'borrow_request_items';
    protected $fillable = ['borrow_request_id', 'equipment_item_id', 'notes'];
    public $timestamps = false;

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function equipmentItem()
    {
        return $this->belongsTo(EquipmentItem::class);
    }
}
