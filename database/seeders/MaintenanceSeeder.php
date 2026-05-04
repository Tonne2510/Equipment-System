<?php

namespace Database\Seeders;

use App\Models\EquipmentItem;
use App\Models\MaintenanceCost;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('role_id', 2)->first();
        $items = EquipmentItem::limit(5)->get();

        $maintenanceRecords = [
            [
                'equipment_item_id' => $items[0]->id,
                'maintenance_type' => 'preventive',
                'scheduled_start' => Carbon::now()->subDays(20),
                'scheduled_end' => Carbon::now()->subDays(18),
                'actual_start' => Carbon::now()->subDays(20),
                'actual_end' => Carbon::now()->subDays(18),
                'description' => 'Vệ sinh, kiểm tra toàn bộ',
                'status' => 'completed',
                'technician_id' => $manager->id,
            ],
            [
                'equipment_item_id' => $items[1]->id,
                'maintenance_type' => 'corrective',
                'scheduled_start' => Carbon::now()->subDays(5),
                'scheduled_end' => Carbon::now()->addDays(2),
                'actual_start' => Carbon::now()->subDays(5),
                'actual_end' => null,
                'description' => 'Sửa chữa ổ cứng',
                'status' => 'in-progress',
                'technician_id' => $manager->id,
            ],
            [
                'equipment_item_id' => $items[2]->id,
                'maintenance_type' => 'preventive',
                'scheduled_start' => Carbon::now()->subDays(15),
                'scheduled_end' => Carbon::now()->subDays(13),
                'actual_start' => Carbon::now()->subDays(15),
                'actual_end' => Carbon::now()->subDays(13),
                'description' => 'Thay thermal paste',
                'status' => 'completed',
                'technician_id' => $manager->id,
            ],
        ];

        foreach ($maintenanceRecords as $record) {
            MaintenanceRecord::create($record);
        }

        // Add maintenance costs
        $records = MaintenanceRecord::all();
        $categories = ['labor', 'parts', 'service'];
        $amounts = [500000, 1500000, 2000000];

        foreach ($records as $i => $record) {
            MaintenanceCost::create([
                'maintenance_record_id' => $record->id,
                'category' => $categories[$i % 3],
                'amount' => $amounts[$i % 3],
                'description' => 'Chi phí ' . $categories[$i % 3],
            ]);
        }
    }
}
