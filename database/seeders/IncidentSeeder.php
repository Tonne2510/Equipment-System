<?php

namespace Database\Seeders;

use App\Models\EquipmentItem;
use App\Models\IncidentReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::where('role_id', 3)->get();
        $manager = User::where('role_id', 2)->first();
        $items = EquipmentItem::limit(6)->get();

        $incidents = [
            [
                'equipment_item_id' => $items[0]->id,
                'reported_by' => $employees[0]->id,
                'incident_type' => 'damaged',
                'severity' => 'high',
                'description' => 'Màn hình bị nứt',
                'status' => 'open',
                'assigned_to' => $manager->id,
            ],
            [
                'equipment_item_id' => $items[1]->id,
                'reported_by' => $employees[1]->id,
                'incident_type' => 'malfunction',
                'severity' => 'medium',
                'description' => 'Phím bàn phím bị hỏng',
                'status' => 'in-progress',
                'assigned_to' => $manager->id,
            ],
            [
                'equipment_item_id' => $items[2]->id,
                'reported_by' => $employees[2]->id,
                'incident_type' => 'malfunction',
                'severity' => 'critical',
                'description' => 'Pin không sạc được',
                'status' => 'open',
                'assigned_to' => $manager->id,
            ],
            [
                'equipment_item_id' => $items[3]->id,
                'reported_by' => $employees[3]->id,
                'incident_type' => 'lost',
                'severity' => 'low',
                'description' => 'Mất một nút trên chuột',
                'status' => 'resolved',
                'assigned_to' => $manager->id,
            ],
        ];

        foreach ($incidents as $incident) {
            IncidentReport::create($incident);
        }
    }
}
