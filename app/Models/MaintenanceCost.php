<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceCost extends Model
{
    protected $table = 'maintenance_costs';
    protected $fillable = ['maintenance_record_id', 'description', 'amount', 'category'];

    public function maintenanceRecord()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }
}
