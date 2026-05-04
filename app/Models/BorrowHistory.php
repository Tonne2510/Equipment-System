<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowHistory extends Model
{
    protected $table = 'borrow_history';
    protected $fillable = ['borrow_request_id', 'equipment_item_id', 'user_id', 'action', 'action_at', 'action_by', 'notes'];
    protected $casts = [
        'action_at' => 'datetime',
    ];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function equipmentItem()
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
