<?php

namespace App\Http\Controllers;

use App\Models\ComplaintCategory;
use Illuminate\Http\Request;

class ComplaintCategoryController extends Controller
{
    public function index()
    {
        $categories = ComplaintCategory::withCount('complaints')->latest()->paginate(15);
        
        return view('category-management.index', compact('categories'));
    }
    
    public function create()
    {
        return view('category-management.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complaint_categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        ComplaintCategory::create($validated);
        
        return redirect()->route('complaint-categories.index')
            ->with('success', 'Kategori komplain berhasil ditambahkan');
    }
    
    public function edit(ComplaintCategory $complaintCategory)
    {
        return view('category-management.edit', compact('complaintCategory'));
    }
    
    public function update(Request $request, ComplaintCategory $complaintCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complaint_categories,name,' . $complaintCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $complaintCategory->update($validated);
        
        return redirect()->route('complaint-categories.index')
            ->with('success', 'Kategori komplain berhasil diperbarui');
    }
    
    public function destroy(ComplaintCategory $complaintCategory)
    {
        $complaintsCount = $complaintCategory->complaints()->count();
        
        if ($complaintsCount > 0) {
            // Pindahkan semua komplain ke kategori "Lainnya"
            $defaultCategory = ComplaintCategory::where('name', 'Lainnya')->first();
            
            if (!$defaultCategory) {
                // Buat kategori "Lainnya" jika belum ada
                $defaultCategory = ComplaintCategory::create([
                    'name' => 'Lainnya',
                    'description' => 'Komplain lainnya yang tidak termasuk dalam kategori di atas',
                    'is_active' => true
                ]);
            }
            
            // Update semua komplain ke kategori "Lainnya"
            $complaintCategory->complaints()->update([
                'complaint_category_id' => $defaultCategory->id
            ]);
            
            $complaintCategory->delete();
            
            return redirect()->route('complaint-categories.index')
                ->with('success', "Kategori berhasil dihapus. {$complaintsCount} komplain dipindahkan ke kategori 'Lainnya'");
        }
        
        $complaintCategory->delete();
        
        return redirect()->route('complaint-categories.index')
            ->with('success', 'Kategori komplain berhasil dihapus');
    }
}
