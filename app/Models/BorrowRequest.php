<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $fillable = [
        'user_id', 'approved_by', 'status', 'start_date', 'end_date',
        'actual_return_date', 'reason', 'rejection_reason'
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->belongsToMany(EquipmentItem::class, 'borrow_request_items', 'borrow_request_id', 'equipment_item_id');
    }

    public function history()
    {
        return $this->hasMany(BorrowHistory::class);
    }

    public function renewalRequests()
    {
        return $this->hasMany(RenewalRequest::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function violations()
    {
        return $this->hasMany(ViolationRecord::class);
    }

    public function isOverdue()
    {
        return $this->status === 'borrowed' && now()->isAfter($this->end_date);
    }
}
