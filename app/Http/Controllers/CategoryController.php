<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
                            ->withCount('products')
                            ->latest()
                            ->paginate(2);
        return view('category.list', ['categories' => $categories]);
    }

    public function create()
    {
        $parentCategories = Category::getParentCategories();
        return view('category.add', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);
        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
                                  ->whereNull('parent_id')
                                  ->get();
        return view('category.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $data = $request->all();
        
        // Prevent category from being its own parent
        if ($data['parent_id'] == $category->id) {
            $data['parent_id'] = null;
        }
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);
        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        return view('category.show', [
            'category' => $category,
            'showSubcategories' => $category->parent_id === null,
            'showProducts' => $category->parent_id !== null
        ]);
    }
} 