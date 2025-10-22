<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Complaint::with(['customer.user', 'category', 'handledBy', 'resolvedBy', 'managerClaimedBy']);
        
        // All roles can see all complaints for monitoring
        // CS, Manager, and Admin see all complaints
        
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
                  ->orWhereHas('customer.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $complaints = $query->latest()->paginate(15);
        $categories = ComplaintCategory::active()->get();
        
        // Check if current CS has active complaints (not completed)
        $csHasActiveComplaint = false;
        if ($user->role === 'cs') {
            $csHasActiveComplaint = Complaint::where('handled_by', $user->id)
                ->whereIn('status', ['baru', 'diproses'])
                ->exists();
        }
        
        return view('complaint-management.index', compact('complaints', 'categories', 'csHasActiveComplaint'));
    }

    public function exportPdf(Request $request)
    {
        $query = Complaint::with(['customer.user', 'category', 'handledBy', 'resolvedBy', 'managerClaimedBy']);
        
        // Different logic for Manager vs CS
        if (auth()->user()->role === 'cs') {
            // CS hanya melihat komplain yang mereka tangani
            $query->where('handled_by', auth()->id());
        }
        // Manager dan Admin melihat semua komplain
        
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
        
        // Filter by CS Handler (for Manager/Admin reports) - search by name
        if ($request->filled('cs_search') && $request->cs_search !== '' && $request->cs_search !== null) {
            $query->whereHas('handledBy', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cs_search . '%');
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('customer.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $complaints = $query->orderBy('created_at', 'desc')->get();
        
        // Generate dynamic filename based on filters
        $filename = $this->generatePdfFilename($request);
        
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
            'description' => 'required|string|max:200',
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

        // Check for duplicate complaint submission (within last 5 minutes)
        $recentComplaint = Complaint::where('customer_id', $customer->id)
            ->where('complaint_category_id', $request->complaint_category_id)
            ->where('description', $request->description)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if ($recentComplaint) {
            return redirect()->route('customer.complaints')
                ->with('info', 'Komplain yang sama sudah dikirim sebelumnya. Silakan tunggu respons dari tim CS kami atau buat komplain dengan detail yang berbeda.');
        }

        // Check for too many complaints in short time (rate limiting)
        $recentComplaintsCount = Complaint::where('customer_id', $customer->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recentComplaintsCount >= 3) {
            return redirect()->route('customer.complaints')
                ->with('warning', 'Anda telah mengirim terlalu banyak komplain dalam waktu singkat. Silakan tunggu beberapa menit sebelum mengirim komplain baru.');
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
        $customers = Customer::with('user')->get()->sortBy('user.name');
        $categories = ComplaintCategory::active()->get();
        
        return view('complaint-management.edit', compact('complaint', 'customers', 'categories'));
    }
    
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'complaint_category_id' => 'required|exists:complaint_categories,id',
            'description' => 'required|string|max:200',
            'status' => 'required|in:baru,diproses,selesai',
            'action_notes' => 'nullable|string',
        ]);
        
        // If status is changed to 'selesai', record resolution
        if ($validated['status'] === 'selesai' && $complaint->status !== 'selesai') {
            $validated['resolved_at'] = now();
            $validated['resolved_by'] = Auth::id();
        }
        
        $complaint->update($validated);
        
        return redirect()->route('complaints.index')->with('success', 'Komplain berhasil diupdate statusnya.');
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

        // Check if current CS already has active complaints
        $currentUser = auth()->user();
        if ($currentUser->role === 'cs') {
            $hasActiveComplaint = Complaint::where('handled_by', $currentUser->id)
                ->whereIn('status', ['baru', 'diproses'])
                ->exists();
                
            if ($hasActiveComplaint) {
                return redirect()->back()
                    ->with('error', 'Anda masih memiliki komplain yang belum selesai. Selesaikan atau kembalikan komplain tersebut terlebih dahulu sebelum mengambil komplain baru.');
            }
        }

        // Take the complaint and change status to 'diproses'
        $complaint->update([
            'handled_by' => auth()->id(),
            'status' => 'diproses'
        ]);

        return redirect()->back()
            ->with('success', 'Komplain berhasil diambil dan status diubah ke diproses');
    }

    public function releaseComplaint(Complaint $complaint)
    {
        // Check if complaint is handled by current CS
        if ($complaint->handled_by !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengembalikan komplain yang bukan Anda tangani');
        }

        // Check if complaint is not escalated
        if ($complaint->escalation_to) {
            return redirect()->back()
                ->with('error', 'Komplain yang sudah dieskalasi tidak dapat dikembalikan');
        }

        // Release the complaint back to 'baru' status
        $complaint->update([
            'handled_by' => null,
            'status' => 'baru',
            'cs_response' => null,
            'cs_response_updated_at' => null
        ]);

        return redirect()->back()
            ->with('success', 'Komplain berhasil dikembalikan dan dapat diambil oleh CS lain');
    }

    public function updateResponse(Request $request, Complaint $complaint)
    {
        // Cek apakah komplain sudah selesai
        if ($complaint->status === 'selesai') {
            return redirect()->back()->with('error', 'Tidak dapat mengupdate response karena komplain sudah selesai');
        }

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
        // Jika ada action_notes dari return sebelumnya, simpan sebagai history
        $previousNotes = $complaint->action_notes;
        $newActionNotes = null;
        
        if ($previousNotes && str_contains($previousNotes, 'Dikembalikan ke CS')) {
            // Ada instruksi dari manager sebelumnya, simpan sebagai history
            $newActionNotes = '[HISTORY] ' . $previousNotes;
        }
        
        $complaint->update([
            'escalation_to' => $manager->id,
            'escalated_at' => now(),
            'escalation_reason' => $request->escalation_reason,
            'escalated_by' => auth()->id(),
            'action_notes' => $newActionNotes, // Simpan history atau null
            'manager_claimed_by' => null, // Reset claim
            'manager_claimed_at' => null,
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

        // Check if current manager has claimed this escalation
        if ($complaint->manager_claimed_by !== auth()->id()) {
            return redirect()->route('complaints.show', $complaint)
                ->with('error', 'Anda harus mengambil eskalasi ini terlebih dahulu sebelum memberikan tindakan');
        }

        return view('complaint-management.manager-action-form', compact('complaint'));
    }

    public function managerAction(Request $request, Complaint $complaint)
    {
        $request->validate([
            'manager_action' => 'required|in:resolved,return_to_cs',
            'manager_notes' => 'nullable|string|max:1000'
        ]);

        // Pastikan hanya manager yang sudah claim yang bisa memberikan action
        if ($complaint->manager_claimed_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat memberikan tindakan pada eskalasi yang bukan Anda ambil.');
        }

        $managerName = auth()->user()->name;
        
        // Buat action notes
        $actionNotes = 'Manager Action: ' . $request->manager_action . ' by ' . $managerName;
        if ($request->manager_notes) {
            $actionNotes .= ' - Notes: ' . $request->manager_notes;
        }
        
        if ($request->manager_action === 'resolved') {
            // Manager menandai masalah sudah ditangani, tapi CS yang harus memberikan feedback final
            $complaint->update([
                'action_notes' => $actionNotes,
                // Status tetap 'diproses' karena CS harus memberikan feedback final
            ]);
            
            $message = 'Masalah berhasil ditandai sebagai sudah ditangani oleh ' . $managerName . '. CS akan memberikan feedback final ke customer.';
        } else {
            // Manager mengembalikan ke CS untuk ditangani lebih lanjut
            // Simpan instruksi manager agar CS bisa lihat
            $returnNotes = 'Dikembalikan ke CS oleh ' . $managerName;
            if ($request->manager_notes) {
                $returnNotes .= ' - Instruksi: ' . $request->manager_notes;
            }
            $returnNotes .= ' pada ' . now()->format('d M Y, H:i');
            
            $complaint->update([
                'action_notes' => $returnNotes, // Simpan instruksi untuk CS
                'escalation_to' => null, // Hapus eskalasi aktif
                'escalated_at' => null, // Reset tanggal eskalasi
                'escalation_reason' => null, // Reset alasan eskalasi
                'escalated_by' => null, // Reset yang eskalasi
                'manager_claimed_by' => null, // Reset claim
                'manager_claimed_at' => null,
            ]);
            
            $message = 'Komplain berhasil dikembalikan ke CS untuk penanganan lebih lanjut oleh ' . $managerName . '.';
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
    
    /**
     * Generate dynamic PDF filename based on filters and date
     */
    private function generatePdfFilename($request)
    {
        $baseFilename = 'Laporan-Komplain';
        
        // Add date range to filename if specified
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = date('d-m-Y', strtotime($request->start_date));
            $endDate = date('d-m-Y', strtotime($request->end_date));
            $baseFilename .= "_{$startDate}_sampai_{$endDate}";
        } elseif ($request->filled('start_date')) {
            $startDate = date('d-m-Y', strtotime($request->start_date));
            $baseFilename .= "_dari_{$startDate}";
        } elseif ($request->filled('end_date')) {
            $endDate = date('d-m-Y', strtotime($request->end_date));
            $baseFilename .= "_sampai_{$endDate}";
        } else {
            // If no date filter, use current date
            $currentDate = date('d-m-Y');
            $baseFilename .= "_{$currentDate}";
        }
        
        // Add status filter to filename if specified
        if ($request->filled('status') && $request->status !== '') {
            $statusName = ucfirst($request->status);
            $baseFilename .= "_{$statusName}";
        }
        
        // Add category filter to filename if specified
        if ($request->filled('category') && $request->category !== '') {
            $category = \App\Models\ComplaintCategory::find($request->category);
            if ($category) {
                $categoryName = str_replace(' ', '-', $category->name);
                $baseFilename .= "_{$categoryName}";
            }
        }
        
        // Add CS search filter to filename if specified
        if ($request->filled('cs_search') && $request->cs_search !== '') {
            $csSearch = str_replace(' ', '-', $request->cs_search);
            $baseFilename .= "_CS-{$csSearch}";
        }
        
        // Add timestamp to make it unique
        $timestamp = date('H-i-s');
        $baseFilename .= "_{$timestamp}";
        
        // Check if file exists and add counter if needed
        $counter = 1;
        $finalFilename = $baseFilename . '.pdf';
        $downloadPath = public_path('downloads');
        
        // Create downloads directory if it doesn't exist
        if (!file_exists($downloadPath)) {
            mkdir($downloadPath, 0755, true);
        }
        
        // Check for existing files and increment counter
        while (file_exists($downloadPath . '/' . $finalFilename)) {
            $finalFilename = $baseFilename . "({$counter}).pdf";
            $counter++;
        }
        
        return $finalFilename;
    }

    public function claimEscalation(Complaint $complaint)
    {
        // Hanya manager yang bisa claim
        if (auth()->user()->role !== 'manager') {
            abort(403, 'Hanya manager yang dapat mengambil eskalasi.');
        }

        // Pastikan komplain sudah dieskalasi
        if (!$complaint->escalation_to) {
            return redirect()->back()->with('error', 'Komplain ini belum dieskalasi.');
        }

        // Pastikan belum diclaim manager lain
        if ($complaint->manager_claimed_by) {
            return redirect()->back()->with('error', 'Eskalasi ini sudah diambil oleh ' . $complaint->managerClaimedBy->name);
        }

        // Claim eskalasi
        $complaint->update([
            'manager_claimed_by' => auth()->id(),
            'manager_claimed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Eskalasi berhasil diambil. Anda sekarang menangani komplain ini.');
    }

    public function releaseEscalation(Complaint $complaint)
    {
        // Hanya manager yang mengclaim yang bisa release
        if ($complaint->manager_claimed_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat melepas eskalasi yang bukan Anda ambil.');
        }

        // Release claim
        $complaint->update([
            'manager_claimed_by' => null,
            'manager_claimed_at' => null,
        ]);

        return redirect()->back()->with('success', 'Eskalasi berhasil dilepas dan dapat diambil manager lain.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai'
        ]);

        $user = auth()->user();

        // Validasi: Manager tidak bisa menyelesaikan komplain yang tidak di-eskalasi
        if ($user->role === 'manager' && $request->status === 'selesai') {
            // Manager hanya bisa menyelesaikan komplain yang di-eskalasi dan sudah diberi action
            if (!$complaint->escalation_to || !($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))) {
                return redirect()->back()->with('error', 'Manager hanya bisa menyelesaikan komplain yang sudah di-eskalasi dan diberi instruksi.');
            }
        }

        // Validasi: CS hanya bisa menyelesaikan komplain yang ditanganinya
        if ($user->role === 'cs' && $request->status === 'selesai') {
            if ($complaint->handled_by !== $user->id) {
                return redirect()->back()->with('error', 'Anda hanya bisa menyelesaikan komplain yang Anda tangani.');
            }
        }

        $updateData = [
            'status' => $request->status,
        ];

        // If status is changed to 'selesai', record resolution
        if ($request->status === 'selesai' && $complaint->status !== 'selesai') {
            $updateData['resolved_at'] = now();
            $updateData['resolved_by'] = auth()->id();
        }

        $complaint->update($updateData);

        return redirect()->back()->with('success', 'Status komplain berhasil diperbarui.');
    }
    
}
