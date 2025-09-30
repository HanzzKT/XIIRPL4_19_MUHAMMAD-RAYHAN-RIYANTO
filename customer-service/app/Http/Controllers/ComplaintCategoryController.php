<?php

namespace App\Http\Controllers;

use App\Models\ComplaintCategory;
use Illuminate\Http\Request;

class ComplaintCategoryController extends Controller
{
    public function index()
    {
        $categories = ComplaintCategory::withCount('complaints')->latest()->paginate(15);
        
        return view('complaint-categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('complaint-categories.create');
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
        return view('complaint-categories.edit', compact('complaintCategory'));
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
        if ($complaintCategory->complaints()->count() > 0) {
            return redirect()->route('complaint-categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki komplain');
        }
        
        $complaintCategory->delete();
        
        return redirect()->route('complaint-categories.index')
            ->with('success', 'Kategori komplain berhasil dihapus');
    }
}
