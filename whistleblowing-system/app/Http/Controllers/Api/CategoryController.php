<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::query();

        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Display a listing of active categories for public use.
     */
    public function publicList(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->select(['id', 'name', 'description'])
            ->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json(['category' => $category]);
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        // Check if category has reports
        if ($category->reports()->exists()) {
            return response()->json([
                'error' => 'Cannot delete category',
                'message' => 'This category has associated reports and cannot be deleted.'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category): JsonResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'message' => 'Category status updated successfully',
            'category' => $category
        ]);
    }
}