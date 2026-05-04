<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = ['borrow_request_id', 'status', 'notes', 'approved_by', 'reason'];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
