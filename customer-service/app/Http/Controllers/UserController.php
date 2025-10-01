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
        $currentUser = auth()->user();
        
        // Admin can manage all users except customers
        // Manager can only view CS users
        if ($currentUser->role === 'admin') {
            $query = User::whereIn('role', ['cs', 'manager', 'admin']);
        } else {
            // Manager hanya bisa lihat CS
            $query = User::where('role', 'cs');
        }
        
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
        // Only admin can create new users
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only admin can create new users.');
        }
        
        return view('user-management.create');
    }
    
    public function store(Request $request)
    {
        // Only admin can create new users
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only admin can create new users.');
        }
        
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
        $currentUser = auth()->user();
        
        // Manager can only edit CS users
        if ($currentUser->role === 'manager' && $user->role !== 'cs') {
            abort(403, 'Unauthorized. Manager can only edit CS users.');
        }
        
        return view('user-management.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        // Manager can only edit CS users
        if ($currentUser->role === 'manager' && $user->role !== 'cs') {
            abort(403, 'Unauthorized. Manager can only edit CS users.');
        }
        
        // Manager cannot change role
        $roleValidation = $currentUser->role === 'admin' ? 'required|in:cs,manager,admin' : 'required|in:cs';
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => $roleValidation,
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
