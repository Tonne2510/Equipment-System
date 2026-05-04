<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use App\Models\EquipmentModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentCategory::withCount('models', 'items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->latest()->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipment_categories,name',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:0,1'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = EquipmentCategory::create($validated);

        return redirect()->route('admin.categories.index')
                       ->with('success', "Danh mục '{$category->name}' đã được tạo thành công");
    }

    public function edit(EquipmentCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, EquipmentCategory $category)
    {
        $validated = $request->validate([
            'name' => "required|string|max:255|unique:equipment_categories,name,{$category->id}",
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:0,1'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                       ->with('success', "Danh mục '{$category->name}' đã được cập nhật thành công");
    }

    public function destroy(EquipmentCategory $category)
    {
        // Check if category has models
        if ($category->models()->exists()) {
            return back()->withErrors("Không thể xóa danh mục '{$category->name}' vì nó đang có các model thiết bị liên kết");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
                       ->with('success', "Danh mục '{$categoryName}' đã được xóa thành công");
    }
}
