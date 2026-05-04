<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    protected $table = 'maintenance_records';
    protected $fillable = [
        'equipment_item_id', 'incident_report_id', 'maintenance_type', 'description',
        'scheduled_start', 'scheduled_end', 'actual_start', 'actual_end',
        'technician_id', 'work_done', 'cost', 'notes', 'status'
    ];
    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(EquipmentItem::class, 'equipment_item_id');
    }

    public function incidentReport()
    {
        return $this->belongsTo(IncidentReport::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function costs()
    {
        return $this->hasMany(MaintenanceCost::class);
    }

    public function getTotalCost()
    {
        return $this->cost ?? $this->costs()->sum('amount');
    }
}
