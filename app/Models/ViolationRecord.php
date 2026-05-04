<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationRecord extends Model
{
    protected $fillable = [
        'user_id', 'borrow_request_id', 'violation_type', 'description',
        'status', 'violation_date', 'resolved_date', 'resolution_notes'
    ];
    protected $casts = [
        'violation_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }
}
