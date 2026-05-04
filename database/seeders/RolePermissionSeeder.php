<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị viên',
            'description' => 'Quản trị hệ thống toàn bộ'
        ]);

        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Thủ kho',
            'description' => 'Quản lý thiết bị và duyệt yêu cầu'
        ]);

        $employee = Role::create([
            'name' => 'employee',
            'display_name' => 'Nhân viên',
            'description' => 'Mượn và trả thiết bị'
        ]);

        // Create Permissions
        $permissions = [
            // Administration
            'view-dashboard' => 'Xem Dashboard',
            'manage-users' => 'Quản lý người dùng',
            'manage-roles' => 'Quản lý vai trò',
            'view-audit-logs' => 'Xem nhật ký hoạt động',

            // Equipment Management
            'view-equipment' => 'Xem danh sách thiết bị',
            'create-equipment' => 'Tạo thiết bị',
            'edit-equipment' => 'Sửa thiết bị',
            'delete-equipment' => 'Xóa thiết bị',
            'view-equipment-categories' => 'Xem loại thiết bị',
            'manage-equipment-categories' => 'Quản lý loại thiết bị',

            // Borrowing Management
            'view-borrow-requests' => 'Xem yêu cầu mượn',
            'create-borrow-request' => 'Tạo yêu cầu mượn',
            'approve-borrow-request' => 'Duyệt yêu cầu mượn',
            'reject-borrow-request' => 'Từ chối yêu cầu mượn',
            'view-all-borrow-requests' => 'Xem tất cả yêu cầu mượn',

            // Maintenance
            'view-maintenance' => 'Xem bảo trì',
            'create-maintenance' => 'Tạo bảo trì',
            'manage-maintenance' => 'Quản lý bảo trì',

            // Incidents
            'report-incident' => 'Báo cáo sự cố',
            'view-incidents' => 'Xem sự cố',
            'manage-incidents' => 'Quản lý sự cố',

            // Violations & Penalties
            'view-violations' => 'Xem vi phạm',
            'manage-violations' => 'Quản lý vi phạm',
            'view-penalties' => 'Xem phí phạt',
            'manage-penalties' => 'Quản lý phí phạt',

            // Reports
            'view-reports' => 'Xem báo cáo',
            'generate-reports' => 'Tạo báo cáo',
        ];

        foreach ($permissions as $name => $display_name) {
            Permission::create([
                'name' => $name,
                'display_name' => $display_name,
            ]);
        }

        // Assign Permissions to Roles
        
        // Admin - All permissions
        $admin->permissions()->attach(Permission::all());

        // Manager permissions
        $managerPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-equipment',
            'edit-equipment',
            'view-equipment-categories',
            'view-borrow-requests',
            'approve-borrow-request',
            'reject-borrow-request',
            'view-all-borrow-requests',
            'view-maintenance',
            'create-maintenance',
            'manage-maintenance',
            'view-incidents',
            'manage-incidents',
            'view-violations',
            'manage-violations',
            'view-penalties',
            'manage-penalties',
            'view-reports',
            'generate-reports',
        ])->get();
        $manager->permissions()->attach($managerPermissions);

        // Employee permissions
        $employeePermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-equipment',
            'view-borrow-requests',
            'create-borrow-request',
            'report-incident',
            'view-incidents',
            'view-penalties',
        ])->get();
        $employee->permissions()->attach($employeePermissions);
    }
}
