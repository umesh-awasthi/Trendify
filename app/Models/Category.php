<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'image', 'parent_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Parent relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Children relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Get all parent categories (for dropdown)
    public static function getParentCategories()
    {
        return self::whereNull('parent_id')
                  ->orWhere('id', '!=', request()->route('category.id'))
                  ->get();
    }

    // Get all available parent categories for a specific category
    public static function getAvailableParents($excludeId = null)
    {
        $query = self::whereNull('parent_id');
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get();
    }

    // Check if category has children
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // Get all descendants
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    // Get all ancestors
    public function ancestors()
    {
        return $this->parent()->with('ancestors')->get();
    }

    // Check if category can be parent of another category
    public function canBeParentOf($categoryId)
    {
        if ($this->id == $categoryId) {
            return false;
        }
        
        return !$this->ancestors()->pluck('id')->contains($categoryId);
    }

    // Add a new method to get ancestors as a collection
    public function getAncestorsAttribute()
    {
        $ancestors = collect();
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }
} 