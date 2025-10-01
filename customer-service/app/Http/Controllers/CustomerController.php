<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Show users with customer role (registered customer accounts)
        $query = User::where('role', 'customer')
                    ->with(['customer.complaints']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('phone', 'like', "%{$search}%")
                                   ->orWhere('address', 'like', "%{$search}%");
                  });
            });
        }
        
        $customers = $query->latest()->paginate(15);
        
        return view('customer-management.index', compact('customers'));
    }
    
    
    public function show(User $customer)
    {
        // Load the customer profile and related data
        $customer->load(['customer', 'customer.complaints.category']);
        
        return view('customer-management.show', compact('customer'));
    }
    
    public function edit(User $customer)
    {
        // Load the customer profile
        $customer->load('customer');
        
        return view('customer-management.edit', compact('customer'));
    }
    
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255',
        ]);
        
        // Update user data
        $customer->update([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);
        
        // Update customer profile data
        if ($customer->customer) {
            $customer->customer->update([
                'phone' => $validated['phone'],
                'address' => $validated['address']
            ]);
        }
        
        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil diperbarui');
    }
    
    public function destroy(User $customer)
    {
        // Check if customer has complaints through the customer profile
        if ($customer->customer && $customer->customer->complaints()->count() > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Tidak dapat menghapus customer yang memiliki riwayat komplain');
        }
        
        // Delete customer profile first, then user account
        if ($customer->customer) {
            $customer->customer->delete();
        }
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
