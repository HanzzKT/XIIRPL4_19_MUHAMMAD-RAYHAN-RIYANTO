<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['customer', 'category', 'handledBy', 'resolvedBy', 'escalatedBy', 'escalatedTo']);
        
        // For Manager: Only show escalated complaints
        if (auth()->user()->role === 'manager') {
            $query->whereNotNull('escalation_to');
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $stats = $this->getReportStats($request);
        
        return view('reports.index', compact('complaints', 'stats'));
    }
    
    public function exportFilter()
    {
        $categories = ComplaintCategory::where('is_active', true)->get();
        return view('reports.export-filter', compact('categories'));
    }
    
    public function exportPdf(Request $request)
    {
        $query = Complaint::with(['customer', 'category', 'handledBy', 'resolvedBy']);
        
        // For Manager: Only show escalated complaints
        if (auth()->user()->role === 'manager') {
            $query->whereNotNull('escalation_to');
        }
        
        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply filters from complaints index page (for backward compatibility)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('category')) {
            $query->where('complaint_category_id', $request->category);
        }
        
        
        $complaints = $query->orderBy('created_at', 'desc')->get();
        $stats = $this->getReportStats($request);
        
        // Check if DomPDF package is installed
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            // Return HTML view with print styling for now
            return view('reports.pdf', compact('complaints', 'stats', 'request'))
                ->with('print_mode', true);
        }
        
        // Generate filename
        $filename = 'laporan-komplain-' . date('Y-m-d-H-i-s') . '.pdf';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('complaints', 'stats', 'request'));
        
        return $pdf->download($filename);
    }
    
    private function getReportStats($request)
    {
        $query = Complaint::query();
        
        // For Manager: Only count escalated complaints
        if (auth()->user()->role === 'manager') {
            $query->whereNotNull('escalation_to');
        }
        
        // Apply same filters as main query
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply filters from complaints index page (for backward compatibility)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('category')) {
            $query->where('complaint_category_id', $request->category);
        }
        
        
        $totalComplaints = $query->count();
        $newComplaints = (clone $query)->where('status', 'baru')->count();
        $processedComplaints = (clone $query)->where('status', 'diproses')->count();
        $completedComplaints = (clone $query)->where('status', 'selesai')->count();
        
        return [
            'total' => $totalComplaints,
            'new' => $newComplaints,
            'processed' => $processedComplaints,
            'completed' => $completedComplaints,
        ];
    }
}
