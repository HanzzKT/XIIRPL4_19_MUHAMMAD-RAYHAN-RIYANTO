<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerComplaintController extends Controller
{
    public function index()
    {
        // Get customer record for current user
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Data customer tidak ditemukan');
        }

        // Get complaints for this customer
        $complaints = Complaint::with(['category', 'handledBy', 'resolvedBy'])
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer-portal.complaints', compact('complaints'));
    }

    public function markAsRead(Request $request, Complaint $complaint)
    {
        // Verify this complaint belongs to the current customer
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer || $complaint->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Since we removed feedback tracking columns, just return success
        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil ditandai sebagai sudah dibaca'
        ]);
    }

    public function destroy(Complaint $complaint)
    {
        // Verify this complaint belongs to the current customer
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer data tidak ditemukan');
        }
        
        if ($complaint->customer_id !== $customer->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus komplain ini');
        }

        // Check if complaint can be deleted (only 'baru' or 'selesai' status)
        if (!in_array($complaint->status, ['baru', 'selesai'])) {
            return redirect()->back()->with('error', 'Komplain yang sedang diproses tidak dapat dihapus');
        }

        // Delete the complaint
        $complaint->delete();

        return redirect()->back()->with('success', 'Komplain berhasil dihapus');
    }
}
