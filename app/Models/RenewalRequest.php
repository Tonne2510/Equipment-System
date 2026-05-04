<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalRequest extends Model
{
    protected $fillable = ['borrow_request_id', 'new_end_date', 'status', 'reason', 'approved_by'];
    protected $casts = [
        'new_end_date' => 'date',
    ];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
