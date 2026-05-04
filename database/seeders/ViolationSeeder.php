<?php

namespace Database\Seeders;

use App\Models\BorrowRequest;
use App\Models\Penalty;
use App\Models\User;
use App\Models\ViolationRecord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ViolationSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::where('role_id', 3)->get();
        $borrows = BorrowRequest::all();

        // Create violations
        $violations = [
            [
                'user_id' => $employees[0]->id,
                'borrow_request_id' => $borrows[0]->id ?? null,
                'violation_type' => 'overdue',
                'description' => 'Trả thiết bị quá hạn 5 ngày',
                'status' => 'active',
                'violation_date' => Carbon::now()->subDays(8),
            ],
            [
                'user_id' => $employees[1]->id,
                'borrow_request_id' => $borrows[1]->id ?? null,
                'violation_type' => 'equipment_damaged',
                'description' => 'Làm hỏng hàng hóa mượn',
                'status' => 'resolved',
                'violation_date' => Carbon::now()->subDays(15),
            ],
            [
                'user_id' => $employees[2]->id,
                'borrow_request_id' => null,
                'violation_type' => 'equipment_lost',
                'description' => 'Mất cáp sạc',
                'status' => 'active',
                'violation_date' => Carbon::now()->subDays(20),
            ],
            [
                'user_id' => $employees[3]->id,
                'borrow_request_id' => $borrows[2]->id ?? null,
                'violation_type' => 'overdue',
                'description' => 'Trả thiết bị quá hạn 3 ngày',
                'status' => 'active',
                'violation_date' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($violations as $violation) {
            ViolationRecord::create($violation);
        }

        // Create penalties
        $records = ViolationRecord::all();
        $penaltyTypes = ['overdue_fee', 'damage_fee', 'loss_fee'];
        $amounts = [500000, 2000000, 1000000];

        foreach ($records as $i => $record) {
            Penalty::create([
                'user_id' => $record->user_id,
                'violation_record_id' => $record->id,
                'penalty_type' => $penaltyTypes[$i % 3],
                'amount' => $amounts[$i % 3],
                'status' => $i % 2 == 0 ? 'unpaid' : 'paid',
                'due_date' => Carbon::now()->addDays(7),
                'paid_date' => $i % 2 == 0 ? null : Carbon::now()->subDays(1),
            ]);
        }
    }
}
