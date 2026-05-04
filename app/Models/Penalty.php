<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'user_id', 'violation_record_id', 'penalty_type', 'amount',
        'status', 'due_date', 'paid_date', 'notes'
    ];
    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function violationRecord()
    {
        return $this->belongsTo(ViolationRecord::class);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isOverdue()
    {
        return $this->status === 'unpaid' && now()->isAfter($this->due_date);
    }
}
