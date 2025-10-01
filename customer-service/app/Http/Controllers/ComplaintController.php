<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Complaint::with(['customer', 'category', 'handledBy', 'resolvedBy']);
        
        // Manager only sees escalated complaints
        if ($user->role === 'manager') {
            $query->whereNotNull('escalation_to');
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('complaint_category_id', $request->category);
        }
        
        // Search by customer name or complaint description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $complaints = $query->latest()->paginate(15);
        $categories = ComplaintCategory::active()->get();
        
        return view('complaint-management.index', compact('complaints', 'categories'));
    }

    public function exportPdf(Request $request)
    {
        $query = Complaint::with(['customer', 'category', 'handledBy', 'resolvedBy']);
        
        // All roles can see all complaints
        
        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category') && $request->category !== '' && $request->category !== null) {
            $query->where('complaint_category_id', $request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $complaints = $query->orderBy('created_at', 'desc')->get();
        
        // Generate filename
        $filename = 'laporan-komplain-' . date('Y-m-d-H-i-s') . '.pdf';
        
        // Generate PDF using DOMPDF
        $pdf = Pdf::loadView('complaint-management.pdf', compact('complaints', 'request'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => false,
            ]);
        
        return $pdf->download($filename);
    }

    public function customerComplaints()
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            $complaints = collect();
        } else {
            $complaints = Complaint::with(['category'])
                ->where('customer_id', $customer->id)
                ->latest()
                ->get();
        }
        
        return view('customer-portal.complaints', compact('complaints'));
    }

    public function create()
    {
        $categories = ComplaintCategory::active()->get();
        
        return view('complaint-management.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'complaint_category_id' => 'required|exists:complaint_categories,id',
            'description' => 'required|string',
        ]);

        // Get authenticated user
        $user = Auth::user();
        
        // Get or create customer profile
        $customer = $user->customer;
        if (!$customer) {
            $customer = Customer::create([
                'user_id' => $user->id,
                'phone' => $user->phone ?? '0000000000', // Default phone if not set
                'address' => $user->address ?? 'Alamat belum diisi', // Default address if not set
                'created_by' => $user->id, // Customer created their own profile
            ]);
        } else {
            // Update customer data if user has phone/address
            if ($user->phone && $user->phone !== $customer->phone) {
                $customer->update(['phone' => $user->phone]);
            }
            if ($user->address && $user->address !== $customer->address) {
                $customer->update(['address' => $user->address]);
            }
        }

        // Create complaint
        Complaint::create([
            'customer_id' => $customer->id,
            'complaint_category_id' => $request->complaint_category_id,
            'description' => $request->description,
            'customer_phone' => $customer->phone,
            'status' => 'baru',
        ]);

        return redirect()->route('customer.complaints')->with('success', 'Komplain Anda berhasil dikirim! Tim CS kami akan segera menghubungi Anda.');
    }
    
    public function show(Complaint $complaint)
    {
        // If user is customer, redirect to their complaints page
        if (auth()->user()->role === 'customer') {
            return redirect()->route('customer.complaints')
                ->with('info', 'Silakan lihat komplain Anda di halaman Komplain Saya');
        }
        
        $complaint->load(['customer.user', 'category', 'handledBy', 'resolvedBy']);
        
        return view('complaint-management.show', compact('complaint'));
    }
    
    public function edit(Complaint $complaint)
    {
        $customers = Customer::orderBy('name')->get();
        $categories = ComplaintCategory::active()->get();
        
        return view('complaint-management.edit', compact('complaint', 'customers', 'categories'));
    }
    
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'complaint_category_id' => 'required|exists:complaint_categories,id',
            'description' => 'required|string',
            'status' => 'required|in:baru,diproses,selesai',
            'action_notes' => 'nullable|string',
        ]);
        
        // If status is changed to 'selesai', record resolution
        if ($validated['status'] === 'selesai' && $complaint->status !== 'selesai') {
            $validated['resolved_at'] = now();
            $validated['resolved_by'] = Auth::id();
        }
        
        $complaint->update($validated);
        
        return redirect()->route('complaints.index')
            ->with('success', 'Komplain berhasil diperbarui');
    }
    
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
            'action_notes' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'handled_by' => auth()->id(),
        ];

        // Only add action_notes if it exists in the request
        if ($request->has('action_notes')) {
            $updateData['action_notes'] = $validated['action_notes'];
        }

        $complaint->update($updateData);

        if ($validated['status'] === 'selesai') {
            $complaint->update([
                'resolved_at' => now(),
                'resolved_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Status komplain berhasil diperbarui');
    }


    public function createPublic()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk membuat komplain.');
        }
        
        $categories = ComplaintCategory::where('is_active', true)->get();
        return view('public-pages.complaint-form', compact('categories'));
    }

    public function storePublic(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk membuat komplain.');
        }

        // For authenticated users, use the same logic as store() method
        return $this->store($request);
    }
    
    public function takeComplaint(Complaint $complaint)
    {
        // Check if complaint is already taken
        if ($complaint->handled_by) {
            return redirect()->back()
                ->with('error', 'Komplain sudah diambil oleh CS lain');
        }

        // Take the complaint and change status to 'diproses'
        $complaint->update([
            'handled_by' => auth()->id(),
            'status' => 'diproses'
        ]);

        return redirect()->back()
            ->with('success', 'Komplain berhasil diambil dan status diubah ke diproses');
    }

    public function updateResponse(Request $request, Complaint $complaint)
    {
        $request->validate([
            'cs_response' => 'required|string|max:1000'
        ]);

        $complaint->update([
            'cs_response' => $request->cs_response,
            'cs_response_updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Response berhasil disimpan dan akan dikirim ke customer');
    }

    public function escalateForm(Complaint $complaint)
    {
        // Check if complaint is already escalated
        if ($complaint->escalation_to) {
            return redirect()->route('complaints.show', $complaint)
                ->with('error', 'Komplain sudah dieskalasi sebelumnya');
        }

        return view('complaint-management.escalate-form', compact('complaint'));
    }

    public function escalateToManager(Request $request, Complaint $complaint)
    {
        $request->validate([
            'escalation_reason' => 'required|string|min:10|max:500'
        ]);

        // Check if complaint is already escalated
        if ($complaint->escalation_to) {
            return redirect()->route('complaints.show', $complaint)
                ->with('error', 'Komplain sudah dieskalasi sebelumnya');
        }

        // Find a manager to escalate to
        $manager = \App\Models\User::where('role', 'manager')->first();
        
        if (!$manager) {
            return redirect()->back()
                ->with('error', 'Tidak ada Manager yang tersedia untuk eskalasi');
        }

        // Update complaint with escalation info
        // Status tetap 'diproses' karena manager tidak menyelesaikan langsung
        $complaint->update([
            'escalation_to' => $manager->id,
            'escalated_at' => now(),
            'escalation_reason' => $request->escalation_reason,
            'escalated_by' => auth()->id()
        ]);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Komplain berhasil dieskalasi ke Manager: ' . $manager->name . '. Manager akan menangani masalah dan CS akan memberikan feedback ke customer.');
    }


    public function managerActionForm(Complaint $complaint)
    {
        // Check if complaint is escalated to manager
        if (!$complaint->escalation_to) {
            return redirect()->route('complaints.show', $complaint)
                ->with('error', 'Komplain belum dieskalasi ke Manager');
        }

        // Check if manager action already taken
        if ($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action:')) {
            return redirect()->route('complaints.show', $complaint)
                ->with('error', 'Tindakan Manager sudah diambil sebelumnya');
        }

        return view('complaint-management.manager-action-form', compact('complaint'));
    }

    public function managerAction(Request $request, Complaint $complaint)
    {
        $request->validate([
            'manager_action' => 'required|in:resolved,return_to_cs',
            'manager_notes' => 'nullable|string|max:1000'
        ]);

        if ($request->manager_action === 'resolved') {
            // Manager menandai masalah sudah ditangani, tapi CS yang harus memberikan feedback final
            $complaint->update([
                'action_notes' => 'Manager Action: ' . $request->manager_action . 
                                ($request->manager_notes ? ' - Notes: ' . $request->manager_notes : ''),
                // Status tetap 'diproses' karena CS harus memberikan feedback final
            ]);
            
            $message = 'Masalah berhasil ditandai sebagai sudah ditangani. CS akan memberikan feedback final ke customer.';
        } else {
            // Manager mengembalikan ke CS untuk ditangani lebih lanjut
            $complaint->update([
                'action_notes' => 'Manager Action: ' . $request->manager_action . 
                                ($request->manager_notes ? ' - Notes: ' . $request->manager_notes : ''),
                'escalation_to' => null, // Hapus eskalasi
                'escalated_at' => null,
                'escalation_reason' => null,
                'escalated_by' => null,
            ]);
            
            $message = 'Komplain berhasil dikembalikan ke CS untuk penanganan lebih lanjut.';
        }

        return redirect()->route('complaints.show', $complaint)
            ->with('success', $message);
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        
        return redirect()->route('complaints.index')
            ->with('success', 'Komplain berhasil dihapus');
    }
    
}
