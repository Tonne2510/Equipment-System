<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $table = 'incident_reports';
    protected $fillable = [
        'equipment_item_id', 'reported_by', 'incident_type', 'description',
        'image_path', 'severity', 'status', 'assigned_to', 'resolution_notes', 'resolved_at'
    ];
    protected $casts = [
        'image_path' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(EquipmentItem::class, 'equipment_item_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function maintenanceRecord()
    {
        return $this->hasOne(MaintenanceRecord::class);
    }
}
