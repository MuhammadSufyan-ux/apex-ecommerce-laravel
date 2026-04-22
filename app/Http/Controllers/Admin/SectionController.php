<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Section::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%");
        }

        $sections = $query->orderBy('sort_order')->get();
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'scroll_type' => 'required|in:vertical,horizontal',
        ]);

        \App\Models\Section::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
            'scroll_type' => $request->scroll_type,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.sections.index')->with('success', 'Section created successfully!');
    }

    public function edit(\App\Models\Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, \App\Models\Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'scroll_type' => 'required|in:vertical,horizontal',
        ]);

        $section->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
            'scroll_type' => $request->scroll_type,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully!');
    }

    public function destroy(\App\Models\Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section deleted successfully!');
    }
}
