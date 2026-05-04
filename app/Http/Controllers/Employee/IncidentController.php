<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function create()
    {
        $myEquipment = EquipmentItem::where('status', 'borrowed')
            ->whereHas('borrowHistory', function($q) {
                $q->where('user_id', auth()->id())
                  ->where('action', 'borrowed')
                  ->latest('action_at')
                  ->limit(1);
            })
            ->with('model')
            ->get();

        return view('employee.incidents.create', compact('myEquipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_item_id' => 'required|exists:equipment_items,id',
            'incident_type' => 'required|in:damaged,malfunction,lost,theft,other',
            'description' => 'required|string|min:6',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:2048',
            'severity' => 'required|in:low,medium,high'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('incidents', 'public');
            }
        }

        $report = IncidentReport::create([
            'equipment_item_id' => $validated['equipment_item_id'],
            'reported_by' => auth()->id(),
            'incident_type' => $validated['incident_type'],
            'description' => $validated['description'],
            'image_path' => $images,
            'severity' => $validated['severity'],
            'status' => 'open'
        ]);

        return redirect()->route('employee.incidents.show', $report)->with('success', 'Báo cáo sự cố đã được gửi');
    }

    public function index()
    {
        $incidents = auth()->user()->incidentReports()
            ->with('equipment.model')
            ->latest()
            ->paginate(20);

        return view('employee.incidents.index', compact('incidents'));
    }

    public function show(IncidentReport $report)
    {
        abort_if($report->reported_by !== auth()->id(), 403);

        $report->load(['equipment.model', 'assignedTo']);
        return view('employee.incidents.show', compact('report'));
    }

    public function adminIndex()
    {
        $incidents = IncidentReport::with(['equipment.model.category', 'equipment.model.brand', 'reportedBy', 'assignedTo'])
            ->latest()
            ->paginate(20);

        return view('admin.incidents.index', compact('incidents'));
    }

    public function adminShow(IncidentReport $report)
    {
        $report->load(['equipment', 'reportedBy', 'assignedTo', 'maintenanceRecord']);
        
        // Only show admin and manager users for assignment
        $adminManagerRoles = \App\Models\Role::whereIn('name', ['admin', 'manager'])->pluck('id');
        $staff = \App\Models\User::whereIn('role_id', $adminManagerRoles)->get();
        
        return view('admin.incidents.show', compact('report', 'staff'));
    }

    public function assignStaff(Request $request, IncidentReport $report)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $report->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'assigned'
        ]);

        return redirect()->route('admin.incidents.show', $report)->with('success', 'Đã gán nhân viên xử lý!');
    }

    public function resolve(Request $request, IncidentReport $report)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string|min:10',
        ]);

        $report->update([
            'status' => 'resolved',
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Đã giải quyết sự cố!');
    }

    public function destroy(IncidentReport $report)
    {
        $reportId = $report->id;
        $report->delete();

        return redirect()->route('admin.incidents.index')
                       ->with('success', "Sự cố #$reportId đã được xóa thành công");
    }
}
