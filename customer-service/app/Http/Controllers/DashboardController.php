<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'cs':
                return redirect()->route('cs.dashboard');
            case 'hrd':
                return redirect()->route('hrd.dashboard');
            case 'customer':
                return redirect()->route('home');
            default:
                return redirect()->route('login');
        }
    }

    public function adminDashboard()
    {
        $stats = [
            'totalComplaints' => Complaint::count(),
            'totalCategories' => ComplaintCategory::count(),
            'totalUsers' => User::count(),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'escalatedComplaints' => Complaint::whereNotNull('escalation_to')->count(),
            'newComplaints' => Complaint::where('status', 'baru')->count(),
            'processingComplaints' => Complaint::where('status', 'diproses')->count(),
            'completedComplaints' => Complaint::where('status', 'selesai')->count(),
            'completionRate' => Complaint::count() > 0 ? round((Complaint::where('status', 'selesai')->count() / Complaint::count()) * 100) : 0,
            'avgResolutionTime' => $this->getAverageResolutionTime(),
        ];
        
        // Get recent complaints for admin
        $recentComplaints = Complaint::with(['customer.user', 'category', 'handledBy'])
            ->latest()
            ->take(10)
            ->get();
            
        // Get recent users
        $recentUsers = User::latest()->take(5)->get();
        
        // Analytics data for admin dashboard
        $analytics = [
            'complaintsByStatus' => Complaint::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'complaintsByCategory' => ComplaintCategory::withCount('complaints')->get(),
            'monthlyTrends' => $this->getMonthlyStats(),
            'topPerformers' => User::where('role', 'cs')
                ->withCount(['resolvedComplaints'])
                ->orderBy('resolved_complaints_count', 'desc')
                ->take(5)
                ->get(),
        ];
        return view('admin-dashboard.dashboard', compact('stats', 'recentComplaints', 'recentUsers', 'analytics'));
    }

    public function managerDashboard()
    {
        // Manager focuses on escalated complaints
        $totalEscalated = Complaint::whereNotNull('escalation_to')->count();
        $completedEscalated = Complaint::whereNotNull('escalation_to')
            ->where('status', 'selesai')->count();
        
        $stats = [
            'totalEscalations' => $totalEscalated,
            'completedEscalations' => $completedEscalated,
            'pendingEscalations' => Complaint::whereNotNull('escalation_to')
                ->where(function($query) {
                    $query->whereNull('action_notes')
                          ->orWhere('action_notes', 'not like', 'Manager Action:%');
                })->count(),
            'escalationCompletionRate' => $totalEscalated > 0 ? round(($completedEscalated / $totalEscalated) * 100, 1) : 0,
            'activeCS' => User::where('role', 'cs')->where('is_active', true)->count(),
        ];
        
        // Recent new complaints for manager to monitor
        $recentComplaints = Complaint::with(['customer', 'category', 'handledBy'])
            ->where('status', 'baru')
            ->latest()
            ->take(10)
            ->get();
        
        // Escalated complaints that need manager action
        $escalatedComplaints = Complaint::with(['customer', 'category', 'handledBy'])
            ->whereNotNull('escalation_to')
            ->where(function($query) {
                $query->whereNull('action_notes')
                      ->orWhere('action_notes', 'not like', 'Manager Action:%');
            })
            ->latest()
            ->take(5)
            ->get();
        
        return view('manager-dashboard.dashboard', compact('stats', 'recentComplaints', 'escalatedComplaints'));
    }


    public function csDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'totalComplaints' => Complaint::count(),
            'newComplaints' => Complaint::where('status', 'baru')->whereNull('handled_by')->count(),
            'processingComplaints' => Complaint::where('status', 'diproses')->count(),
            'completedComplaints' => Complaint::where('status', 'selesai')->count(),
            'myHandledComplaints' => Complaint::where('handled_by', $user->id)->count(),
            'myResolvedComplaints' => Complaint::where('resolved_by', $user->id)->count(),
        ];
        
        // CS hanya melihat:
        // 1. Komplain baru yang belum diambil (untuk diambil)
        // 2. Komplain yang sedang mereka tangani
        $recentComplaints = Complaint::with(['customer', 'category'])
            ->where(function($query) use ($user) {
                $query->where(function($q) {
                    // Komplain baru yang belum diambil siapa pun
                    $q->where('status', 'baru')
                      ->whereNull('handled_by');
                })
                ->orWhere(function($q) use ($user) {
                    // Komplain yang sedang ditangani oleh CS ini
                    $q->where('handled_by', $user->id)
                      ->whereIn('status', ['diproses']);
                });
            })
            ->latest()
            ->take(10)
            ->get();
            
        return view('customer-service-dashboard.dashboard', compact('stats', 'recentComplaints'));
    }
    
    

    public function analytics()
    {
        // Manager melihat semua data seperti admin
        if (auth()->user()->role === 'manager') {
            $analytics = [
                'complaintsByStatus' => Complaint::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get(),
                'complaintsByCategory' => ComplaintCategory::withCount('complaints')->get(),
                'monthlyTrends' => $this->getMonthlyStats(false), // false = all complaints
                'avgResolutionTime' => $this->getAverageResolutionTime(false), // false = all complaints
                'topPerformers' => User::where('role', 'cs')
                    ->withCount(['resolvedComplaints'])
                    ->orderBy('resolved_complaints_count', 'desc')
                    ->take(5)
                    ->get(),
            ];
        } else {
            // Admin melihat semua data sistem
            $analytics = [
                'complaintsByStatus' => Complaint::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get(),
                'complaintsByCategory' => ComplaintCategory::withCount('complaints')->get(),
                'monthlyTrends' => $this->getMonthlyStats(),
                'avgResolutionTime' => $this->getAverageResolutionTime(),
                'topPerformers' => User::where('role', 'cs')
                    ->withCount(['resolvedComplaints'])
                    ->orderBy('resolved_complaints_count', 'desc')
                    ->take(5)
                    ->get(),
            ];
        }
        
        return view('manager-dashboard.analytics', compact('analytics'));
    }

    public function reports()
    {
        return view('manager-dashboard.reports');
    }

    public function exportReport(Request $request)
    {
        // Implementation for PDF/Excel export
        return response()->json(['message' => 'Export functionality coming soon']);
    }
    
    private function getAverageResolutionTime($escalatedOnly = false)
    {
        $query = Complaint::whereNotNull('resolved_at');
        
        if ($escalatedOnly) {
            $query->whereNotNull('escalation_to');
        }
        
        $resolved = $query->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours'))
            ->first();
            
        return $resolved->avg_hours ? round($resolved->avg_hours, 1) : 0;
    }
    
    private function getMonthlyStats($escalatedOnly = false)
    {
        $query = Complaint::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as completed')
        );
        
        if ($escalatedOnly) {
            $query->whereNotNull('escalation_to');
        }
        
        return $query->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
    }
    
    private function getCompletionRate()
    {
        $total = Complaint::count();
        $completed = Complaint::where('status', 'selesai')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
    
    private function getEscalationCompletionRate()
    {
        $total = Complaint::whereNotNull('escalation_to')->count();
        $completed = Complaint::whereNotNull('escalation_to')->where('status', 'selesai')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
}
