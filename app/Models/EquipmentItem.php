<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentItem extends Model
{
    protected $table = 'equipment_items';
    protected $fillable = [
        'model_id', 'serial_number', 'asset_tag', 'status', 'location',
        'notes', 'purchase_date', 'purchase_cost', 'image', 'warranty_until'
    ];
    protected $casts = [
        'purchase_date' => 'date',
        'warranty_until' => 'date',
    ];

    public function model()
    {
        return $this->belongsTo(EquipmentModel::class, 'model_id');
    }

    public function borrowRequests()
    {
        return $this->belongsToMany(BorrowRequest::class, 'borrow_request_items', 'equipment_item_id', 'borrow_request_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(EquipmentStatusHistory::class, 'equipment_item_id')->orderByDesc('created_at');
    }

    public function borrowHistory()
    {
        return $this->hasMany(BorrowHistory::class, 'equipment_item_id')->orderByDesc('created_at');
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class, 'equipment_item_id');
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class, 'equipment_item_id');
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
