<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ComplaintCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PublicController;

// Public Routes (No Login Required)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/faq', [PublicController::class, 'faq'])->name('faq');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
Route::get('/alur-komplain', [PublicController::class, 'complaintFlow'])->name('complaint-flow');

// Complaint routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/buat-komplain', [ComplaintController::class, 'createPublic'])->name('complaints.create');
    Route::post('/buat-komplain', [ComplaintController::class, 'storePublic'])->name('complaints.store');
});

// Authenticated complaint creation route (submit only; form is on homepage)
Route::middleware('auth')->group(function () {
    Route::post('/komplain-buat', [ComplaintController::class, 'store'])->name('complaints.store.authenticated');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});
// ... existing code ...

// Customer routes - hanya untuk my-complaints, tidak ada detail terpisah

// ... existing code ...

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Customer Routes
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer/complaints', [\App\Http\Controllers\CustomerComplaintController::class, 'index'])->name('customer.complaints');
        Route::post('/customer/complaints/{complaint}/mark-read', [\App\Http\Controllers\CustomerComplaintController::class, 'markAsRead'])->name('customer.complaints.mark-read');
        Route::delete('/customer/complaints/{complaint}/delete', [\App\Http\Controllers\CustomerComplaintController::class, 'delete'])->name('customer.complaints.delete');
    });
    
    
    // Admin Routes - Full system management
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    
    
    // CS Routes - View and manage complaints only
    Route::middleware(['role:cs,admin'])->group(function () {
        Route::get('cs/dashboard', [DashboardController::class, 'csDashboard'])->name('cs.dashboard');
        Route::get('cs/whatsapp-notifications', [DashboardController::class, 'whatsappNotifications'])->name('cs.whatsapp-notifications');
        Route::get('complaints/{complaint}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
        Route::patch('complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
        Route::patch('complaints/{complaint}/response', [ComplaintController::class, 'updateResponse'])->name('complaints.update-response');
        Route::post('complaints/{complaint}/take', [ComplaintController::class, 'takeComplaint'])->name('complaints.take');
        Route::get('complaints/{complaint}/escalate', [ComplaintController::class, 'escalateForm'])->name('complaints.escalate-form');
        Route::post('complaints/{complaint}/escalate', [ComplaintController::class, 'escalateToManager'])->name('complaints.escalate');
        Route::delete('complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
    });
    
    // Status update - accessible by CS and Manager (Manager can complete complaints)
    Route::middleware(['role:cs,manager,admin'])->group(function () {
        Route::patch('complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    });
    
    // Export PDF - accessible by CS, Manager, Admin (MUST be before show route to avoid conflict)
    Route::middleware(['role:cs,manager,admin'])->group(function () {
        Route::get('complaints/export-pdf', [ComplaintController::class, 'exportPdf'])->name('complaints.export-pdf');
    });
    
    // Management Routes - CS, Manager, and Admin access
    Route::middleware(['role:cs,manager,admin'])->group(function () {
        Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    });
    
    // Admin Management Routes - Admin can manage users, customers, categories
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('customers', CustomerController::class)->except(['create', 'store']);
        Route::resource('complaint-categories', ComplaintCategoryController::class);
    });
    
    // Complaint show route - accessible by all roles (controller handles customer redirect)
    Route::middleware(['role:customer,cs,manager,admin'])->group(function () {
        Route::get('complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    });
    
    
    // Manager Routes - Only managers can access these
    Route::middleware(['role:manager'])->group(function () {
        Route::get('manager/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
        Route::patch('complaints/{complaint}/verify', [ComplaintController::class, 'verifyComplaint'])->name('complaints.verify');
        Route::get('complaints/{complaint}/manager-action', [ComplaintController::class, 'managerActionForm'])->name('complaints.manager-action-form');
        Route::patch('complaints/{complaint}/manager-action', [ComplaintController::class, 'managerAction'])->name('complaints.manager-action');
    });
    
    // Analytics redirect - accessible by manager and admin
    Route::middleware(['role:manager,admin'])->group(function () {
        Route::get('analytics', function() {
            return redirect()->route('complaints.index');
        })->name('analytics');
    });
});
