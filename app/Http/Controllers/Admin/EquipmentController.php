<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use App\Models\EquipmentModel;
use App\Models\EquipmentCategory;
use App\Models\EquipmentBrand;
use App\Models\EquipmentStatusHistory;
use App\Models\BorrowRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentItem::with(['model.category', 'model.brand']);

        if ($request->filled('category')) {
            $query->whereHas('model.category', fn($q) => $q->where('id', $request->category));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%$search%")
                  ->orWhere('asset_tag', 'like', "%$search%")
                  ->orWhereHas('model', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        $equipment = $query->paginate(15);
        $categories = EquipmentCategory::where('status', 1)->get();

        return view('admin.equipment.index', compact('equipment', 'categories'));
    }

    public function create()
    {
        $models = EquipmentModel::where('status', 1)->with('category', 'brand')->get();
        $categories = EquipmentCategory::where('status', 1)->get();
        $brands = EquipmentBrand::where('status', 1)->get();

        return view('admin.equipment.create', compact('models', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'model_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'serial_number' => 'required|unique:equipment_items',
            'status' => 'required|in:available,borrowed,maintenance,damaged,lost',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'warranty_until' => 'nullable|date|after:purchase_date',
            'image' => 'nullable|image|max:2048'
        ]);

        // Create or find brand if provided
        $brand_id = null;
        if (!empty($validated['brand_name'])) {
            $brand = EquipmentBrand::where('name', $validated['brand_name'])->first();
            if (!$brand) {
                $brand = EquipmentBrand::create([
                    'name' => $validated['brand_name'],
                    'slug' => Str::slug($validated['brand_name']),
                    'status' => 1
                ]);
            }
            $brand_id = $brand->id;
        }

        // Check if model exists, if not create it
        $model = EquipmentModel::where('name', $validated['model_name'])
            ->where('category_id', $validated['category_id'])
            ->first();

        if (!$model) {
            $model = EquipmentModel::create([
                'name' => $validated['model_name'],
                'category_id' => $validated['category_id'],
                'brand_id' => $brand_id,
                'slug' => Str::slug($validated['model_name']),
                'status' => 1
            ]);
        } else if ($brand_id && !$model->brand_id) {
            // If model exists but doesn't have a brand, update it
            $model->update(['brand_id' => $brand_id]);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        // Create equipment item with model_id
        $equipment = EquipmentItem::create([
            'model_id' => $model->id,
            'serial_number' => $validated['serial_number'],
            'status' => $validated['status'],
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'purchase_date' => $validated['purchase_date'] ?? null,
            'purchase_cost' => $validated['purchase_cost'] ?? null,
            'warranty_until' => $validated['warranty_until'] ?? null,
            'image' => $validated['image'] ?? null
        ]);

        EquipmentStatusHistory::create([
            'equipment_item_id' => $equipment->id,
            'old_status' => null,
            'new_status' => $validated['status'],
            'changed_by' => auth()->id(),
            'reason' => 'Tạo mới thiết bị'
        ]);

        return redirect()->route('admin.equipment.show', $equipment)->with('success', 'Thiết bị được tạo thành công');
    }

    public function show(EquipmentItem $equipment)
    {
        $equipment->load(['model.category', 'model.brand', 'statusHistory', 'incidentReports', 'maintenanceRecords']);
        
        // Get status history
        $statusHistory = $equipment->statusHistory;
        
        // Get counts
        $incidentCount = $equipment->incidentReports->count();
        $maintenanceCount = $equipment->maintenanceRecords->count();
        
        // Get borrow requests that include this equipment
        $recentBorrows = BorrowRequest::whereHas('items', function($q) use ($equipment) {
            $q->where('equipment_item_id', $equipment->id);
        })->with('user')->latest()->take(5)->get();
        $borrowCount = BorrowRequest::whereHas('items', function($q) use ($equipment) {
            $q->where('equipment_item_id', $equipment->id);
        })->count();
        
        return view('admin.equipment.show', compact('equipment', 'statusHistory', 'borrowCount', 'incidentCount', 'maintenanceCount', 'recentBorrows'));
    }

    public function edit(EquipmentItem $equipment)
    {
        $models = EquipmentModel::where('status', 1)->with('category', 'brand')->get();
        return view('admin.equipment.edit', compact('equipment', 'models'));
    }

    public function update(Request $request, EquipmentItem $equipment)
    {
        $validated = $request->validate([
            'model_id' => 'required|exists:equipment_models,id',
            'asset_tag' => 'nullable|unique:equipment_items,asset_tag,' . $equipment->id,
            'status' => 'required|in:available,borrowed,maintenance,damaged,lost',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'warranty_until' => 'nullable|date',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        $oldStatus = $equipment->status;
        $equipment->update($validated);

        if ($oldStatus !== $validated['status']) {
            EquipmentStatusHistory::create([
                'equipment_item_id' => $equipment->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'changed_by' => auth()->id(),
                'reason' => $request->input('status_reason', '')
            ]);
        }

        return redirect()->route('admin.equipment.show', $equipment)->with('success', 'Cập nhật thiết bị thành công');
    }

    public function destroy(EquipmentItem $equipment)
    {
        $equipment->delete();
        return redirect()->route('admin.equipment.index')->with('success', 'Xóa thiết bị thành công');
    }
}
