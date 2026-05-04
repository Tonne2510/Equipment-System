<?php

namespace Database\Seeders;

use App\Models\BorrowHistory;
use App\Models\BorrowRequest;
use App\Models\BorrowRequestItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BorrowSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::where('role_id', 3)->get();
        $manager = User::where('role_id', 2)->first();

        $borrowRequests = [
            [
                'user_id' => $employees[0]->id,
                'approved_by' => $manager->id,
                'status' => 'borrowed',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(5),
                'actual_return_date' => null,
                'reason' => 'Mượn cho dự án Quý 2',
            ],
            [
                'user_id' => $employees[1]->id,
                'approved_by' => $manager->id,
                'status' => 'borrowed',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(10),
                'actual_return_date' => null,
                'reason' => 'Mượn cho buổi training',
            ],
            [
                'user_id' => $employees[2]->id,
                'approved_by' => $manager->id,
                'status' => 'returned',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(15),
                'actual_return_date' => Carbon::now()->subDays(14),
                'reason' => 'Mượn xong trả',
            ],
            [
                'user_id' => $employees[3]->id,
                'approved_by' => null,
                'status' => 'pending',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'actual_return_date' => null,
                'reason' => 'Yêu cầu chờ duyệt',
            ],
        ];

        foreach ($borrowRequests as $req) {
            BorrowRequest::create($req);
        }

        // Add borrow request items
        $requests = BorrowRequest::all();
        $items = [1, 2, 3, 4, 5];

        foreach ($requests as $i => $request) {
            BorrowRequestItem::create([
                'borrow_request_id' => $request->id,
                'equipment_item_id' => $items[$i % count($items)],
            ]);
        }
    }
}
