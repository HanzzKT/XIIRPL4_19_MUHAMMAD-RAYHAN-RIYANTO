<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Admin can manage all users except customers
        $query = User::whereIn('role', ['cs', 'manager', 'admin']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('user-management.index', compact('users'));
    }
    
    public function create()
    {
        return view('user-management.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:cs,manager,admin',
            'is_active' => 'boolean',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }
    
    public function show(User $user)
    {
        // Load performance data only for CS users
        if ($user->role === 'cs') {
            $user->load(['handledComplaints', 'resolvedComplaints']);
        }
        
        return view('user-management.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        return view('user-management.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:cs,manager,admin',
            'is_active' => 'boolean',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }
    
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
