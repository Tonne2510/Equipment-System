<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
        
        $users = $query->paginate(15);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Người dùng được tạo thành công!');
    }

    public function show(User $user)
    {
        $user->load('role');
        $roles = Role::all();
        
        return view('admin.users.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        $user->load('role');
        $roles = Role::all();
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Thông tin người dùng được cập nhật!');
    }

    public function destroy(User $user)
    {
        // Không xóa user hiện tại
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa!');
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update(['role_id' => $validated['role_id']]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Vai trò đã được cập nhật!');
    }

    public function resetPassword(User $user)
    {
        // Reset password thành 'password'
        $user->update(['password' => bcrypt('password')]);

        return back()->with('success', "Mật khẩu của {$user->name} đã được đặt lại thành 'password'!");
    }

    public function toggleStatus(User $user)
    {
        // Không thay đổi trạng thái của admin hiện tại
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể vô hiệu hóa tài khoản của chính mình!');
        }

        $newStatus = $user->status === 1 ? 0 : 1;
        $user->update(['status' => $newStatus]);

        $message = $newStatus === 1 ? 'kích hoạt' : 'vô hiệu hóa';

        return back()->with('success', "Tài khoản đã được {$message}!");
    }
}
